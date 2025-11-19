<?php

namespace Minex\TelegramAudiencesMessages;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $message_id
 * @property string $path
 * @property string $type
 * @property int|null $duration
 * @property int|null $width
 * @property int|null $height
 * @property string|null $thumbnail_path
 * @property int|null $telegram_file_id
 */
class TelegramMessageMedia extends Model
{
    use HasFactory;

    protected $table = 'telegram_message_media';

    protected $fillable = [
        'message_id',
        'path',
        'type',
        'duration',
        'width',
        'height',
        'thumbnail_path',
        'telegram_file_id',
    ];

    protected $casts = [
        'telegram_file_id' => 'int',
    ];

    public function message(): HasOne
    {
        return $this->hasOne(TelegramMessage::class, 'id', 'message_id');
    }
}
