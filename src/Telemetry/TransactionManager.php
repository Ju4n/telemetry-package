<?php

declare(strict_types=1);

namespace Telemetry;

use DateTimeZone;
use Stringable;
use Telemetry\Driver\DriverInterface;

class TransactionManager extends AbstractTelemetryLogger
{
    public function __construct(
        protected LogEntryTransaction $logEntryTransaction,
        protected DriverInterface $driver,
        DateTimeZone $dateTimezone
    ) {
        parent::__construct($dateTimezone);
    }

    public function log($level, string | Stringable $message, array $context = []): void
    {
        $logEntry = $this->createLogEntry($level, $message, $context);
        $this->logEntryTransaction->addLogEntry($logEntry);
    }

    public function commit()
    {
        $this->driver->writeLogEntryTransaction($this->logEntryTransaction);
    }
}
