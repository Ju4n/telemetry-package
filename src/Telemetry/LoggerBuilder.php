<?php

declare(strict_types=1);

namespace Telemetry;

use Telemetry\Driver\CLIDriver;
use Telemetry\Driver\DriverInterface;
use Telemetry\Formatter\LineFormatter;

final class LoggerBuilder
{
    public static function build(?DriverInterface $driver = null): Logger
    {
        if (!$driver) {
            $driver = new CLIDriver(new LineFormatter());
        }

        return new Logger($driver);
    }
}
