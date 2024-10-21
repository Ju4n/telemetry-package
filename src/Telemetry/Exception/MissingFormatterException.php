<?php

declare(strict_types=1);

namespace Telemetry\Exception;

use Exception;

class MissingFormatterException extends Exception
{
    public function __construct(
        string $message = "You are trying to use a Formattable Driver, please assign a Formatter to the Driver",
        int $code = 0,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
