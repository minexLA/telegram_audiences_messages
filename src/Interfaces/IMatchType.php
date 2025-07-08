<?php

namespace Minex\TelegramAudiencesMessages\Interfaces;

interface IMatchType
{
    public function getKey(): string;
    public function getTitle(): string;
}