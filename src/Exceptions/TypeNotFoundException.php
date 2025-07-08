<?php

namespace Minex\TelegramAudiencesMessages\Exceptions;

use Exception;
use Throwable;

class TypeNotFoundException extends Exception
{
    public function __construct(string $type = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("'$type' type is not configured in telegram-audiences-messages.php file", $code, $previous);
    }
}