<?php

declare(strict_types=1);

namespace Telemetry;

use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use Psr\Log\AbstractLogger;
use Stringable;
use Telemetry\Driver\DriverInterface;

class Logger extends AbstractLogger
{
    public function __construct(
        private ?DriverInterface $driver = null,
        private ?DateTimeZone $dateTimezone = null
    ) {
        $this->dateTimezone = $dateTimezone ?: $this->dateTimezone = new DateTimeZone(date_default_timezone_get());
    }

    public function log($level, string | Stringable $message, array $context = []): void
    {
        // check if string level is compatible with psr-3 levels
        $level = Level::tryFrom($level);
        if (!$level) {
            throw new InvalidArgumentException(\sprintf("Level should be an psr3 level compatible (%s)", implode(', ', Level::getLevels())));
        }

        $dateTime = new DateTimeImmutable('now', $this->dateTimezone);
        $LogEntry = new LogEntry($dateTime, $level, $message, $context);

        $this->driver->writeLogEntry($LogEntry);
    }

    public function startLogTransaction(string | int $transactionId, array $attributes): TransactionManager
    {
        $logEntryTransaction = new LogEntryTransaction($transactionId, $attributes);

        return new TransactionManager($logEntryTransaction, $this->driver, $this->dateTimezone);
    }

    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    public function getDriver(): DriverInterface
    {
        return $this->driver;
    }
}
