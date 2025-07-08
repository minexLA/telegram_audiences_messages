<?php

namespace Minex\TelegramAudiencesMessages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TelegramMessageMedia extends Model
{
    use HasFactory;

    protected $table = 'telegram_message_media';

    protected $fillable = [
        'message_id',
        'path',
        'type',
        'telegram_file_id'
    ];

    public function message(): HasOne
    {
        return $this->hasOne(TelegramMessage::class, 'id', 'message_id');
    }
}
