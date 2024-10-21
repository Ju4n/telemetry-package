<?php

declare(strict_types=1);

namespace Telemetry\Driver;

use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

interface DriverInterface
{
    public function writeLogEntry(LogEntry $logEntry): void;

    public function writeLogEntryTransaction(LogEntryTransaction $transaction): void;
}
