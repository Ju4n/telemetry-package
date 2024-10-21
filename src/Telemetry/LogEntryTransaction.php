<?php

declare(strict_types=1);

namespace Telemetry;

class LogEntryTransaction
{
    /**
     * @param array<LogEntry>
     */
    protected array $LogEntrys;

    public function __construct(
        protected readonly int | string $transactionId,
        protected readonly array $attributes = []
    ) {
    }

    public function addLogEntry(LogEntry $LogEntry)
    {
        $this->LogEntrys[] = $LogEntry;
    }

    public function getLogEntrys(): array
    {
        return $this->LogEntrys;
    }

    public function getTransactionId(): int | string
    {
        return $this->transactionId;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
