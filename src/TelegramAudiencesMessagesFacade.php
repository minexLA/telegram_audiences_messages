<?php

namespace Minex\TelegramAudiencesMessages;

use Illuminate\Support\Facades\Facade;

class TelegramAudiencesMessagesFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'telegram-audiences-messages';
    }
}
