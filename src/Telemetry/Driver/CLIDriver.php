<?php

declare(strict_types=1);

namespace Telemetry\Driver;

use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

class CLIDriver extends AbstractFormattableDriver implements DriverInterface
{
    public function writeLogEntry(LogEntry $LogEntry): void
    {
        echo $this->getFormatter()->formatLogEntry($LogEntry);
    }

    public function writeLogEntryTransaction(LogEntryTransaction $logEntryTransaction): void
    {
        echo $this->getFormatter()->formatLogTransaction($logEntryTransaction);
    }
}
