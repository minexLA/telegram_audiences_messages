<?php

namespace Minex\TelegramAudiencesMessages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TelegramMessageAudience extends Model
{
    use HasFactory;

    protected $table = 'telegram_message_audiences';

    protected $fillable = [
        'message_id',
        'audience_id',
    ];

    public function message(): HasOne
    {
        return $this->hasOne(TelegramMessage::class, 'id', 'message_id');
    }

    public function audience(): HasOne
    {
        return $this->hasOne(TelegramAudience::class, 'id', 'audience_id');
    }
}
