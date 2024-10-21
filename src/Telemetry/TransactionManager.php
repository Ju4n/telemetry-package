<?php

declare(strict_types=1);

namespace Telemetry;

use DateTimeImmutable;
use DateTimeZone;
use Stringable;
use Telemetry\Driver\DriverInterface;

class TransactionManager
{
    public function __construct(
        private LogEntryTransaction $logEntryTransaction,
        private readonly DriverInterface $driver,
        private readonly DateTimeZone $dateTimeZone
    ) {
    }

    public function addLogEntry(Level $level, string | Stringable $message, array $context = [])
    {
        $dateTime = new DateTimeImmutable('now', $this->dateTimeZone);
        $this->logEntryTransaction->addLogEntry(new LogEntry($dateTime, $level, $message, $context));
    }

    public function commit()
    {
        $this->driver->writeLogEntryTransaction($this->logEntryTransaction);
    }
}
