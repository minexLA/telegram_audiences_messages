<?php

namespace Minex\TelegramAudiencesMessages\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Minex\TelegramAudiencesMessages\TelegramMessageRecipient;

interface ISendHelper
{
    public function beforeEachSend(TelegramMessageRecipient $recipient, Model $recipientModel, array $data): array;
}
