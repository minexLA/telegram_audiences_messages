<?php

namespace Minex\TelegramAudiencesMessages\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface IAudienceFilter
{
    public function getTitle(): string;

    public function getKey(): string;

    public function getMatchTypes(): array;

    public function getType(): string;

    public function getSettings(): array|null;

    public function applyToQuery(Builder $query, string $matchType, array $data): Builder;
}
