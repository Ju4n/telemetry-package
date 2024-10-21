<?php

declare(strict_types=1);

namespace Telemetry;

use Telemetry\Driver\CLIDriver;
use Telemetry\Driver\DriverInterface;
use Telemetry\Driver\FormattableDriverInterface;
use Telemetry\Formatter\FormatterInterface;
use Telemetry\Formatter\LineFormatter;

final class LoggerBuilder
{
    public static function build(?DriverInterface $driver = null, ?FormatterInterface $formatter = null): Logger
    {
        if (!$driver) {
            $driver = new CLIDriver();
        }

        if ($driver instanceof FormattableDriverInterface) {

            if (!$formatter) {
                $formatter = new LineFormatter();
            }

            $driver->setFormatter($formatter);
        }

        return new Logger($driver);
    }
}
