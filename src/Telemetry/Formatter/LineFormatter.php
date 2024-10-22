<?php

declare(strict_types=1);

namespace Telemetry\Formatter;

use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

class LineFormatter implements FormatterInterface
{
    protected const DATETIME_FORMAT = 'Y-m-d H:i:s.v';

    public function formatLogEntry(LogEntry $logEntry): string
    {
        return \sprintf(
            "[%s] %s: %s %s",
            $logEntry->getDateTime()->format(self::DATETIME_FORMAT),
            strtoupper($logEntry->getLevel()->value),
            $logEntry->getMessage(),
            json_encode($logEntry->getContext())
        ) . PHP_EOL;
    }

    public function formatLogTransaction(LogEntryTransaction $logEntryTransaction): string
    {
        $message = \sprintf(
            '[START TRANSACTION ID: %s with Attributes: %s]',
            $logEntryTransaction->getTransactionId(),
            json_encode($logEntryTransaction->getAttributes())
        ) . PHP_EOL;
        foreach ($logEntryTransaction->getLogEntrys() as $LogEntry) {
            $message .= \sprintf('>> %s', $this->formatLogEntry($LogEntry));
        }
        $message .= \sprintf('[END TRANSACTION ID: %s]', $logEntryTransaction->getTransactionId()) . PHP_EOL;

        return $message;
    }
}
