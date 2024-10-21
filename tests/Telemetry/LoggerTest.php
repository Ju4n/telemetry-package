<?php

declare(strict_types=1);

namespace Telemetry;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Telemetry\Driver\DriverInterface;
use Telemetry\Driver\FileDriver;
use Telemetry\Formatter\FormatterInterface;

class LoggerTest extends TestCase
{
    const FILENAME = 'test_file.log';

    protected FormatterInterface $mockFormatter;
    protected LogEntryTransaction $mockLogEntryTransaction;
    protected DriverInterface $mockDriver;

    public function setup(): void
    {
        $this->mockLogEntryTransaction = $this->createMock(LogEntryTransaction::class);
        $this->mockDriver = $this->createMock(DriverInterface::class);
    }

    public function testInstance()
    {
        $logger = new Logger($this->mockDriver);
        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function testLogFunctionLevelException()
    {
        $this->expectException(InvalidArgumentException::class);
        $logger = new Logger($this->mockDriver);
        $logger->log('MYCUSTMOLEVEL', 'my test message', ['att' => 'value']);
    }

    public function testLogFunction()
    {
        $this->mockDriver->method('writeLogEntry')
            ->willReturnCallback(
                function ($logEntry) {
                    $this->assertInstanceOf(LogEntry::class, $logEntry);
                    $logEntryArray = $logEntry->toArray();
                    $this->assertIsArray($logEntryArray);
                    $this->assertInstanceOf(Level::class, $logEntryArray['level']);
                    $this->assertEquals($logEntryArray['message'], 'my test message');
                    $this->assertInstanceOf(DateTimeImmutable::class, $logEntryArray['datetime']);
                }
            );

        $logger = new Logger($this->mockDriver);
        $logger->log('debug', 'my test message', ['attr' => 'value']);
    }

    public function testStartLogingTransaction()
    {
        $logger = new Logger($this->mockDriver);
        $transaction = $logger->startLogTransaction('test_id', ['attr' => 'value']);
        $this->assertInstanceOf(TransactionManager::class, $transaction);
    }

    public function testSetAndGetDriver()
    {
        $logger = new Logger($this->mockDriver);
        $driver = new FileDriver(self::FILENAME);
        $logger->setDriver($driver);
        $getDriver = $logger->getDriver();
        $this->assertInstanceOf(FileDriver::class, $getDriver);

        // delete created file.
        unlink(self::FILENAME);
    }
}
