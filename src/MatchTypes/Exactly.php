<?php

namespace Minex\TelegramAudiencesMessages\MatchTypes;


use Minex\TelegramAudiencesMessages\Interfaces\IMatchType;

class Exactly implements IMatchType
{
    public function getKey(): string
    {
        return 'exactly';
    }

    public function getTitle(): string
    {
        return 'exactly';
    }
}