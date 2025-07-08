<?php

namespace Minex\TelegramAudiencesMessages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $message_id
 * @property string $label
 * @property string $content
 * @property string $type
 */
class TelegramMessageButton extends Model
{
    use HasFactory;

    protected $table = 'telegram_message_buttons';

    protected $fillable = [
        'message_id',
        'label',
        'content',
        'type',
    ];

    public function message(): HasOne
    {
        return $this->hasOne(TelegramMessage::class, 'id', 'message_id');
    }
}
