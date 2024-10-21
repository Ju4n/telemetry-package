<?php

declare(strict_types=1);

namespace Telemetry\Formatter;

use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

interface FormatterInterface
{
    public function formatLogEntry(LogEntry $LogEntry): string;

    public function formatLogTransaction(LogEntryTransaction $logTransaction): string;
}
