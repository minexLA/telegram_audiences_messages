<?php

namespace Minex\TelegramAudiencesMessages\MatchTypes;


use Minex\TelegramAudiencesMessages\Interfaces\IMatchType;

class LessThan implements IMatchType
{
    public function getKey(): string
    {
        return 'less_than';
    }

    public function getTitle(): string
    {
        return 'less than';
    }
}