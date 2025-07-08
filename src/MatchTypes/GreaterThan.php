<?php

namespace Minex\TelegramAudiencesMessages\MatchTypes;

use Minex\TelegramAudiencesMessages\Interfaces\IMatchType;

class GreaterThan implements IMatchType
{
    public function getKey(): string
    {
        return 'greater_than';
    }

    public function getTitle(): string
    {
        return 'greater than';
    }
}
