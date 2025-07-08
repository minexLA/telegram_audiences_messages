<?php

namespace Minex\TelegramAudiencesMessages\MatchTypes;


use Minex\TelegramAudiencesMessages\Interfaces\IMatchType;

class Contains implements IMatchType
{
    public function getKey(): string
    {
        return 'contains';
    }

    public function getTitle(): string
    {
        return 'contains';
    }
}