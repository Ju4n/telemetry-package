<?php

declare(strict_types=1);

namespace Telemetry\Formatter;

use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

class JSONFormatter implements FormatterInterface
{
    protected const DATETIME_FORMAT = 'Y-m-d H:i:s.v';

    public function formatLogEntry(LogEntry $logEntry): string
    {
        $logEntryArray = $this->formatLogEntryArray($logEntry->toArray());

        return json_encode($logEntryArray) . PHP_EOL;
    }

    public function formatLogTransaction(LogEntryTransaction $logEntryTransaction): string
    {
        $message = [
            'transaction_id' => $logEntryTransaction->getTransactionId(),
            'attributes' => $logEntryTransaction->getAttributes(),
            'logs' => array_map(
                function (LogEntry $logEntry) {
                    return $this->formatLogEntryArray($logEntry->toArray());
                },
                $logEntryTransaction->getLogEntrys()
            ),
        ];

        return json_encode($message) . PHP_EOL;
    }

    /**
     * @param array<string, mixed> $logEntryArray
     *
     * @return array<string, mixed> $logEntryArray
     */
    private function formatLogEntryArray(array $logEntryArray): array
    {
        $logEntryArray['level'] = strtoupper($logEntryArray['level']->value);
        $logEntryArray['datetime'] = strtoupper($logEntryArray['datetime']->format(self::DATETIME_FORMAT));

        return $logEntryArray;
    }
}
