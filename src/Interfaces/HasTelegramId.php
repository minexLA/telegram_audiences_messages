<?php

namespace Minex\TelegramAudiencesMessages\Interfaces;

interface HasTelegramId
{
    function getTelegramId(): int|null;
}