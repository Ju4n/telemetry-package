<?php

declare(strict_types=1);

namespace Telemetry;

use Psr\Log\LogLevel;

enum Level: string
{
    case EMERGENCY = LogLevel::EMERGENCY;
    case ALERT     = LogLevel::ALERT;
    case CRITICAL  = LogLevel::CRITICAL;
    case ERROR     = LogLevel::ERROR;
    case WARNING   = LogLevel::WARNING;
    case NOTICE    = LogLevel::NOTICE;
    case INFO      = LogLeveL::INFO;
    case DEBUG     = LogLevel::DEBUG;

    public static function getLevels()
    {
        return array_map(fn ($case) => $case->value, Level::cases());
    }
}
