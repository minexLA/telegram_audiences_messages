<?php

namespace Minex\TelegramAudiencesMessages\MatchTypes;


use Minex\TelegramAudiencesMessages\Interfaces\IMatchType;

class DoesntContain implements IMatchType
{
    public function getKey(): string
    {
        return 'doesnt_contain';
    }

    public function getTitle(): string
    {
        return 'does not contain';
    }
}