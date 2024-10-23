<?php

declare(strict_types=1);

namespace Telemetry;

use DateTimeImmutable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Telemetry\Driver\CLIDriver;
use Telemetry\Driver\DriverInterface;
use Telemetry\Driver\FileDriver;
use Telemetry\Formatter\FormatterInterface;
use Telemetry\Formatter\JSONFormatter;
use Telemetry\Formatter\LineFormatter;

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
        $this->mockFormatter = $this->createMock(FormatterInterface::class);
    }

    public function testInstance(): void
    {
        $logger = new Logger($this->mockDriver);
        $this->assertInstanceOf(Logger::class, $logger);
    }

    public function testLogFunctionLevelException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $logger = new Logger($this->mockDriver);
        $logger->log('MYCUSTMOLEVEL', 'my test message', ['att' => 'value']);
    }

    public function testLogFunction(): void
    {
        // @phpstan-ignore-next-line
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

    public function testStartLogingTransaction(): void
    {
        $logger = new Logger($this->mockDriver);
        $transaction = $logger->logTransaction('test_id', ['attr' => 'value']);
        $this->assertInstanceOf(TransactionManager::class, $transaction);
    }

    public function testSetAndGetDriver(): void
    {
        $logger = new Logger($this->mockDriver);
        $driver = new FileDriver($this->mockFormatter, self::FILENAME);
        $logger->setDriver($driver);
        $getDriver = $logger->getDriver();
        $this->assertInstanceOf(FileDriver::class, $getDriver);

        // delete created file.
        unlink(self::FILENAME);
    }

    public function testLoggerWriteWithCliDriver(): void
    {
        $logger = new Logger(new CLIDriver(new LineFormatter()));
        ob_start();
        $logger->alert('This is a test alert');
        $line = ob_get_contents();
        ob_end_clean();
        $this->assertIsString($line);
        $this->assertMatchesRegularExpression(
            '/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}\] ALERT: This is a test alert \[\]\\n/',
            $line
        );
    }

    public function testLoggerWriteWithFileDriver(): void
    {
        $logger = new Logger(new FileDriver(new LineFormatter(), self::FILENAME));
        $logger->alert('This is a test alert');
        $line = file_get_contents(self::FILENAME);
        $this->assertIsString($line);
        $this->assertMatchesRegularExpression(
            '/\[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}\] ALERT: This is a test alert \[\]\\n/',
            $line
        );
        unlink(self::FILENAME);
    }

    public function testLoggerWriteWithCliDriverAndJsonFormatter(): void
    {
        $logger = new Logger(new CLIDriver(new JSONFormatter()));
        ob_start();
        $logger->alert('This is a test alert');
        $line = ob_get_contents();
        ob_end_clean();
        $this->assertIsString($line);
        $this->assertMatchesRegularExpression(
            '{"datetime":"[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}","level":"ALERT","message":"This is a test alert","context":\[\]}',
            $line
        );
    }

    public function testLoggerWriteWithFileDriverAndJsonFormatter(): void
    {
        $logger = new Logger(new FileDriver(new JSONFormatter(), self::FILENAME));
        $logger->alert('This is a test alert');
        $line = file_get_contents(self::FILENAME);
        $this->assertIsString($line);
        $this->assertIsString($line);
        $this->assertMatchesRegularExpression(
            '{"datetime":"[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}","level":"ALERT","message":"This is a test alert","context":\[\]}',
            $line
        );
        unlink(self::FILENAME);
    }
}