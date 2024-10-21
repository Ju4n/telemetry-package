<?php

declare(strict_types=1);

namespace Telemetry\Driver;

use PHPUnit\Framework\TestCase;
use Telemetry\Formatter\FormatterInterface;
use Telemetry\Formatter\LineFormatter;
use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

class FileDriverTest extends TestCase
{
    const FORMATTED_LOG_ENTRY = 'formatted log entry';
    const FORMATTED_TRANSACTION_ENTRY = 'formatted log transaction';
    const FILENAME = 'test_file.log';

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

    public function tearDown(): void
    {
        unlink(self::FILENAME);
    }

    public function testSetAndGetFormatter()
    {
        $driver = new FileDriver(new LineFormatter(), self::FILENAME);
        $formatter = $driver->getFormatter();
        $this->assertEquals(LineFormatter::class, $formatter::class);
    }

    public function testWriteLogEntry()
    {
        $driver = new FileDriver($this->mockFormatter, self::FILENAME);
        $driver->writeLogEntry($this->mockLogEntry);
        $message = file_get_contents(self::FILENAME);
        $this->assertEquals(self::FORMATTED_LOG_ENTRY, $message);
    }

    public function testWriteLogEntryTransaction()
    {
        $driver = new FileDriver($this->mockFormatter, self::FILENAME);
        $driver->writeLogEntryTransaction($this->mockLogEntryTransaction);
        $message = file_get_contents(self::FILENAME);
        $this->assertEquals(self::FORMATTED_TRANSACTION_ENTRY, $message);
    }
}
