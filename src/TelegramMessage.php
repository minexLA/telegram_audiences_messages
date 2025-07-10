<?php

namespace Minex\TelegramAudiencesMessages;

use App\Models\Webinar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Minex\TelegramAudiencesMessages\Exceptions\TypeNotFoundException;
use Minex\TelegramAudiencesMessages\Interfaces\HasTelegramId;
use Minex\TelegramAudiencesMessages\Interfaces\ISendHelper;

/**
 * @property string|null $related_type
 * @property int|null $related_id
 * @property string $name
 * @property string $type
 * @property string|null $trigger
 * @property string $text
 * @property string $send_status
 * @property Carbon|null $send_at
 */
class TelegramMessage extends Model
{
    use HasFactory;

    protected $table = 'telegram_messages';

    protected $fillable = [
        'related_type',
        'related_id',
        'name',
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

    public function scopeWhereRelated(Builder $query, Model $model): Builder
    {
        return $query->where([
            'related_type' => get_class($model),
            'related_id' => $model->getKey(),
        ]);
    }

    public function scopeWhereTrigger(Builder $query, string $trigger): Builder
    {
        return $query->where([
            'type' => 'trigger',
            'trigger' => $trigger,
        ]);
    }

    /**
     * Send message. If recipient param passed send message only to him. Otherwise, send to all recipients with status to_send
     *
     * @param TelegramMessageRecipient|null $recipient
     * @return void
     */
    public function send(TelegramMessageRecipient|null $recipient = null): void
    {
        $text = $this->text;
        $media = $this->media;
        $buttons = $this->buttons;
        $messageType = 'text';
        $delay = (1 / config('telegram-audiences-messages.messages_per_sec', 10)) * 1_000_000;
        $helper = $this->getHelper();

        if ($media->isNotEmpty()) {
            $messageType = $media->first()->type;
        }

        $this->recipients()
            ->when((bool) $recipient, function (Builder $query) use ($recipient) {
                $query->where('id', $recipient->id);
            })
            ->where([
                'send_status' => 'to_send',
            ])->chunk(500, function ($chunk) use ($text, $messageType, $buttons, &$media, $delay, $helper) {
            /** @var TelegramMessageRecipient $recipient */
            foreach ($chunk as $recipient) {
                $apiUrl = "https://api.telegram.org/bot$recipient->bot_token/";
                $recipientModel = $recipient->user;

                if (!$recipientModel) {
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
                    if ($helper) {
                        $data = $helper->beforeEachSend($recipient, $recipientModel, $data);
                    }

                    switch ($messageType) {
                        case 'text':
                            $result = Http::post($apiUrl . 'sendMessage', $data);

                            $this->checkSendResult($result, $recipient);
                            break;

                        case 'image':
                            $photo = $media->first();

                            if ($photo->telegram_file_id) {
                                $data['photo'] = $photo->telegram_file_id;

                                $result = Http::post($apiUrl . 'sendPhoto', $data);
                            } else {
                                $data = $this->formatDataForMultipartRequest($data);

                                $result = Http::attach(
                                    'photo',
                                    contents: Storage::disk('local')->readStream($photo->path),
                                    filename: Str::afterLast($photo->path, '/'),
                                )->post($apiUrl . 'sendPhoto', $data);
                            }

                            $this->checkSendResult($result, $recipient);
                            $this->updateMediaFileId($result, $photo);
                            break;

                        case 'video':
                            $video = $media->first();

                            if ($video->telegram_file_id) {
                                $data['video'] = $video->telegram_file_id;

                                $result = Http::post($apiUrl . 'sendVideo', $data);
                            } else {
                                $data = $this->formatDataForMultipartRequest($data);

                                $result = Http::attach(
                                    'video',
                                    contents: Storage::disk('local')->readStream($video->path),
                                    filename: Str::afterLast($video->path, '/'),
                                )->post($apiUrl . 'sendVideo', $data);
                            }

                            $this->checkSendResult($result, $recipient);
                            $this->updateMediaFileId($result, $video);
                            break;
                    }
                } catch (\Exception $e) {
                    $recipient->markAsFailed();

                    report($e);
                    continue;
                }

                usleep($delay);
            }
        });
    }

    /**
     * Sync all recipients for current message. Don`t work with trigger type message.
     * All recipients who have "to_send" status and don't align to current audiences anymore will be deleted from recipients list
     *
     * @throws TypeNotFoundException
     */
    public function syncRecipients(): void
    {
        $audiences = $this->audiences;

        $updatedIdsList = [];
        /** @var TelegramAudience $audience */
        foreach ($audiences as $audience) {
            $recipients = $audience->resolve();
            $botToken = $audience->bot_token;

            foreach ($recipients as $recipient) {
                $recipientInstance = $this->recipients()->where([
                    'user_type' => get_class($recipient),
                    'user_id' => $recipient->getKey(),
                ])->first();

                if (!$recipientInstance) {
                    $recipientInstance = $this->addRecipient($recipient, $botToken);
                }

                $updatedIdsList[] = $recipientInstance->id;
            }
        }

        $this->recipients()->whereNotIn('id', $updatedIdsList)->where('send_status', 'to_send')->delete();
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
        if (!$recipient instanceof HasTelegramId) {
            throw new \InvalidArgumentException('Recipient must be an Eloquent Model implementing HasTelegramId.');
        }

        return $this->recipients()->create([
            'user_type' => get_class($recipient),
            'user_id' => $recipient->getKey(),
            'bot_token' => $botToken,
            'status' => 'to_send',
        ]);
    }

    protected function formatButtons(Collection $buttons): array
    {
        return $buttons->map(function (TelegramMessageButton $button) {
            return [
                [
                    'text' => $button->label,
                    'url' => $button->content,
                ],
            ];
        })->toArray();
    }

    protected function checkSendResult(
        \Illuminate\Http\Client\Response $result,
        TelegramMessageRecipient         $recipient
    ): void
    {
        $result = $result->json();

        if ($result['ok'] ?? false) {
            $recipient->markAsSuccess($result['result']['message_id']);
        } else {
            $recipient->markAsFailed();
        }
    }

    protected function updateMediaFileId(\Illuminate\Http\Client\Response $result, TelegramMessageMedia &$media): void
    {
        $result = $result->json();

        if (($result['ok'] ?? false) && !$media->telegram_file_id) {
            $file_id = null;
            if (isset($result['result']['photo'])) {
                $file_id = $result['result']['photo'][0]['file_id'];
            }
            if (isset($result['result']['video'])) {
                $file_id = $result['result']['video']['file_id'];
            }

            if ($file_id) {
                $media->telegram_file_id = $file_id;
                $media->save();
            }
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

    protected function getHelper(): ISendHelper|null
    {
        $helperClass = config('telegram-audiences-messages.helper');
        if (!$helperClass) {
            return null;
        }

        return new $helperClass;
    }
}
