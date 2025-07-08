<?php

namespace Minex\TelegramAudiencesMessages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Minex\TelegramAudiencesMessages\Interfaces\IAudienceFilter;

/**
 * @property int $audience_id
 * @property string $key
 * @property string $match_type
 * @property array $data
 */
class TelegramAudienceFilter extends Model
{
    use HasFactory;

    protected $table = 'telegram_audience_filters';

    protected $fillable = [
        'audience_id',
        'key',
        'match_type',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected $attributes = [
        'data' => '[]',
    ];

    protected $appends = [
        'filter',
    ];

    public function getFilterAttribute(): IAudienceFilter|null
    {
        if (! $this->key) {
            return null;
        }

        $filterClass = config("telegram-audiences-messages.filters.$this->key", null);
        if (! $filterClass) {
            throw new \Exception("Missing map for $this->key filter");
        }

        return new $filterClass;
    }

    public function audience(): BelongsTo
    {
        return $this->belongsTo(TelegramAudience::class, 'audience_id');
    }

    public function apply(Builder $builder): Builder
    {
        $filter = $this->filter;
        if (! $filter) {
            return $builder;
        }

        return $filter->applyToQuery($builder, $this->match_type, $this->data);
    }
}
