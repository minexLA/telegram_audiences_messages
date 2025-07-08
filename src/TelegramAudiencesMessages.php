<?php

namespace Minex\TelegramAudiencesMessages;

class TelegramAudiencesMessages
{
    public function getAudienceTypes(): array
    {
        return config('telegram-audiences-messages.audiences');
    }
}
