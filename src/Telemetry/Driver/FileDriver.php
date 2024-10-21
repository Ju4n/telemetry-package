<?php

declare(strict_types=1);

namespace Telemetry\Driver;

use SplFileObject;
use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

class FileDriver extends AbstractFormattableDriver implements DriverInterface
{
    protected SplFileObject $fileObject;

    public function __construct(string $filepath)
    {
        $this->fileObject = new SplFileObject($filepath, 'w');
    }

    public function writeLogEntry(LogEntry $logEntry): void
    {
        $record = $this->getFormatter()->formatLogEntry($logEntry);
        $this->fileObject->fwrite($record);
    }

    public function writeLogEntryTransaction(LogEntryTransaction $logEntryTransaction): void
    {
        $transaction = $this->getFormatter()->formatLogTransaction($logEntryTransaction);
        $this->fileObject->fwrite($transaction);
    }
}
