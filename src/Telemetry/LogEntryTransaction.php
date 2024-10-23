<?php

declare(strict_types=1);

namespace Telemetry;

class LogEntryTransaction
{
    /**
     * @var array<LogEntry> $logEntrys
     */
    protected array $logEntrys;

    /**
     * @param array<string, string> $attributes
     */
    public function __construct(
        protected readonly int | string $transactionId,
        protected readonly array $attributes = []
    ) {
    }

    public function addLogEntry(LogEntry $LogEntry): void
    {
        $this->logEntrys[] = $LogEntry;
    }

    /**
     * @return array<LogEntry>
     */
    public function getLogEntrys(): array
    {
        return $this->logEntrys;
    }

    public function getTransactionId(): int | string
    {
        return $this->transactionId;
    }

    /**
     * @return array<string, string>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
