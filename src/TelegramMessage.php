<?php

namespace Minex\TelegramAudiencesMessages;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Minex\TelegramAudiencesMessages\Exceptions\TypeNotFoundException;
use Minex\TelegramAudiencesMessages\Interfaces\HasTelegramId;

class TelegramMessage extends Model
{
    use HasFactory;

    protected $table = 'telegram_messages';

    protected $fillable = [
        'related_type',
        'related_id',
        'type',
        'trigger',
        'text',
        'send_status',
        'send_at',
    ];

    protected $casts = [
        'send_at' => 'datetime',
    ];

    public function audiences(): BelongsToMany
    {
        return $this->belongsToMany(TelegramAudience::class, 'telegram_message_audiences', 'message_id', 'audience_id');
    }

    public function audiencesRelation(): HasMany
    {
        return $this->hasMany(TelegramMessageAudience::class, 'message_id', 'id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(TelegramMessageMedia::class, 'message_id', 'id');
    }

    public function buttons(): HasMany
    {
        return $this->hasMany(TelegramMessageButton::class, 'message_id', 'id');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(TelegramMessageRecipient::class, 'message_id', 'id');
    }

    public function send(): void
    {
        $text = $this->text;
        $media = $this->media;
        $buttons = $this->buttons;
        $messageType = 'text';

        if ($media->isNotEmpty()) {
            $messageType = $media->first()->type;
        }

        $this->recipients()->where([
            'send_status' => 'to_send'
        ])->chunk(500, function ($chunk) use ($text, $messageType, $buttons, &$media) {
            /** @var TelegramMessageRecipient $recipient */
            foreach ($chunk as $recipient) {
                $apiUrl = "https://api.telegram.org/bot$recipient->bot_token/";
                $recipientModel = $recipient->user;

                if (! $recipientModel) {
                    $recipient->markAsFailed();
                    continue;
                }

                $data = [
                    'chat_id' => $recipientModel->getTelegramId(),
                    $messageType == 'text' ? 'text' : 'caption' => $text,
                    'parse_mode' => 'html',
                ];
                if ($buttons->isNotEmpty()) {
                    $data['reply_markup']['inline_keyboard'] = $this->formatButtons($buttons);
                }

                try {
                    switch ($messageType) {
                        case 'text':
                            $result = Http::post($apiUrl . 'sendMessage', $data);

                            $this->checkSendResult($result, $recipient);
                            break;
                        case 'image':
                            $photo = $media->first();

                            if ($photo->telegram_file_id) {

                            }
                            else {
                                $data = $this->formatDataForMultipartRequest($data);

                                $result = Http::attach(
                                    'photo',
                                    contents: Storage::disk('local')->readStream($photo->path),
                                    filename: Str::afterLast($photo->path, '/'),
                                )->post($apiUrl . 'sendPhoto', $data);
                            }

                            $this->checkSendResult($result, $recipient, $photo);
                            break;
                    }
                }
                catch (\Exception $e) {
                    $recipient->markAsFailed();
                    dd($e);
                    continue;
                }

                usleep(1 / 15 * 1_000_000);
            }
        });
    }

    /**
     * Sync all recipients for current message. Don`t work with trigger type message
     *
     * @return void
     * @throws TypeNotFoundException
     */
    public function syncRecipients(): void
    {
        $audiences = $this->audiences;

        /** @var TelegramAudience $audience */
        foreach ($audiences as $audience) {
            $recipients = $audience->resolve();
            $botToken = $audience->bot_token;

            foreach ($recipients as $recipient) {
                $recipientInstance = $this->recipients()->where([
                    'user_type' => get_class($recipient),
                    'user_id' => $recipient->getKey(),
                ])->first();

                if (! $recipientInstance) {
                    $this->addRecipient($recipient, $botToken);
                }
            }
        }
    }

    /**
     * Add a new recipient for current message instance
     *
     * @param Model $recipient
     * @param string $botToken
     * @return TelegramMessageRecipient
     */
    public function addRecipient(Model $recipient, string $botToken): TelegramMessageRecipient
    {
        if (! $recipient instanceof HasTelegramId) {
            throw new InvalidArgumentException('Recipient must be an Eloquent Model implementing HasTelegramId.');
        }

        return $this->recipients()->create([
            'user_type' => get_class($recipient),
            'user_id' => $recipient->getKey(),
            'bot_token' => $botToken,
            'status' => 'to_send'
        ]);
    }

    protected function formatButtons(Collection $buttons): array
    {
        return $buttons->map(function (TelegramMessageButton $button) {
            return [
                [
                    'text' => $button->label,
                    'url' => $button->content,
                ]
            ];
        })->toArray();
    }

    protected function checkSendResult(\Illuminate\Http\Client\Response $result, TelegramMessageRecipient $recipient, TelegramMessageMedia|null &$media = null): void
    {
        $result = $result->json();
        if ($result['ok']??false) {
            $recipient->markAsSuccess($result['result']['message_id']);

            if ($media) {
                $file = $result['result']['photo'][0];
                $media->telegram_file_id = $file['file_id'];
            }
        }
        else {
            $recipient->markAsFailed();
        }
    }

    protected function formatDataForMultipartRequest(array $data): array
    {
        $data['reply_markup'] = json_encode($data['reply_markup']);

        return collect($data)
            ->map(fn($value, $key) => ['name' => $key, 'contents' => $value])
            ->values()
            ->all();
    }
}
