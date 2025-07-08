<?php

namespace Minex\TelegramAudiencesMessages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TelegramMessageRecipient extends Model
{
    use HasFactory;

    protected $table = 'telegram_message_recipients';

    protected $fillable = [
        'message_id',
        'user_type',
        'user_id',
        'send_status',
        'bot_token',
        'telegram_message_id',
    ];

    protected $casts = [
        'bot_token' => 'encrypted',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    public function message(): HasOne
    {
        return $this->hasOne(TelegramMessage::class, 'id', 'message_id');
    }

    public function markAsFailed(): void
    {
        $this->send_status = 'fail';
        $this->save();
    }

    public function markAsSuccess(int $message_id): void
    {
        $this->send_status = 'success';
        $this->telegram_message_id = $message_id;
        $this->save();
    }
}
