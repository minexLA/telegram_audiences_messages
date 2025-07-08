<?php

namespace Minex\TelegramAudiencesMessages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Minex\TelegramAudiencesMessages\Exceptions\TypeNotFoundException;

/**
 * @property string $type
 * @property string $name
 */
class TelegramAudience extends Model
{
    use HasFactory;

    protected $table = 'telegram_audiences';

    protected $fillable = [
        'type',
        'name',
    ];

    public function filters(): HasMany
    {
        return $this->hasMany(TelegramAudienceFilter::class, 'audience_id', 'id');
    }

    /**
     * @throws TypeNotFoundException
     */
    public function getAudienceModelClass(): string
    {
        $classString = config("telegram-audiences-messages.audiences.$this->type.class", null);
        if (! $classString) {
            throw new TypeNotFoundException($this->type);
        }

        return $classString;
    }

    function getBotTokenAttribute(): string
    {
        $token = config("telegram-audiences-messages.audiences.$this->type.bot_token", null);
        if (! $token) {
            throw new TypeNotFoundException($this->type);
        }

        return $token;
    }

    /**
     * Resolve al filter and get a collection of items
     *
     * @return Collection
     * @throws TypeNotFoundException
     */
    public function resolve(): Collection
    {
        $query = $this->buildQuery();

        return $query->get();
    }

    /**
     * Build a query
     *
     * @return Builder
     * @throws TypeNotFoundException
     */
    public function buildQuery(): Builder
    {
        $query = $this->getAudienceModelClass()::query();

        $filters = $this->filters;

        /** @var TelegramAudienceFilter $filter */
        foreach ($filters as $filter) {
            $query = $filter->apply($query);
        }

        return $query;
    }
}