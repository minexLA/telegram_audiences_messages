<?php

namespace Minex\TelegramAudiencesMessages\Interfaces;

use Illuminate\Database\Eloquent\Builder;

interface IAudienceFilter
{
    function getTitle(): string;

    function getKey(): string;

    function getMatchTypes(): array;

    function getType(): string;

    function getSettings(): array|null;

    function applyToQuery(Builder $query, string $matchType, array $data): Builder;
}