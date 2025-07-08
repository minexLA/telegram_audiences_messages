<?php

namespace Minex\TelegramAudiencesMessages\MatchTypes;


use Minex\TelegramAudiencesMessages\Interfaces\IMatchType;

class Not implements IMatchType
{
    public function getKey(): string
    {
        return 'not';
    }

    public function getTitle(): string
    {
        return 'doesnt exactly match';
    }
}