<?php

namespace Minex\TelegramAudiencesMessages\Interfaces;

interface HasTelegramId
{
    public function getTelegramId(): int|null;
}
