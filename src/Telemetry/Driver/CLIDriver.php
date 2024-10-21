<?php

declare(strict_types=1);

namespace Telemetry\Driver;

use Telemetry\Formatter\FormatterInterface;
use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

class CLIDriver extends AbstractFormattableDriver implements DriverInterface
{
    public function __construct(FormatterInterface $formatter)
    {
        parent::__construct($formatter);
    }

    public function writeLogEntry(LogEntry $LogEntry): void
    {
        echo $this->getFormatter()->formatLogEntry($LogEntry);
    }

    public function writeLogEntryTransaction(LogEntryTransaction $logEntryTransaction): void
    {
        echo $this->getFormatter()->formatLogTransaction($logEntryTransaction);
    }
}
