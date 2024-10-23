<?php

declare(strict_types=1);

namespace Telemetry\Driver;

use PHPUnit\Framework\TestCase;
use Telemetry\Formatter\FormatterInterface;
use Telemetry\Formatter\LineFormatter;
use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

class CLIDriverTest extends TestCase
{
    const FORMATTED_LOG_ENTRY = 'formatted log entry';
    const FORMATTED_TRANSACTION_ENTRY = 'formatted log transaction';

    protected FormatterInterface $mockFormatter;
    protected LogEntry $mockLogEntry;
    protected LogEntryTransaction $mockLogEntryTransaction;

    public function setup(): void
    {
        $this->mockFormatter = $this->createConfiguredMock(LineFormatter::class, [
            'formatLogEntry' => self::FORMATTED_LOG_ENTRY,
            'formatLogTransaction' => self::FORMATTED_TRANSACTION_ENTRY,
        ]);
        $this->mockLogEntry = $this->createMock(LogEntry::class);
        $this->mockLogEntryTransaction = $this->createMock(LogEntryTransaction::class);
    }

    public function testSetAndGetFormatter(): void
    {
        $driver = new CLIDriver(new LineFormatter());
        $formatter = $driver->getFormatter();
        $this->assertEquals(LineFormatter::class, $formatter::class);
    }

    public function testWriteLogEntry(): void
    {
        $driver = new CLIDriver($this->mockFormatter);
        ob_start();
        $driver->writeLogEntry($this->mockLogEntry);
        $message = ob_get_contents();
        ob_end_clean();
        $this->assertEquals(self::FORMATTED_LOG_ENTRY, $message);
    }

    public function testWriteLogEntryTransaction(): void
    {
        $driver = new CLIDriver($this->mockFormatter);
        $driver->setFormatter($this->mockFormatter);
        ob_start();
        $driver->writeLogEntryTransaction($this->mockLogEntryTransaction);
        $message = ob_get_contents();
        ob_end_clean();
        $this->assertEquals(self::FORMATTED_TRANSACTION_ENTRY, $message);
    }
}
