<?php

declare(strict_types=1);

namespace Telemetry;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use Telemetry\Driver\DriverInterface;

class TransactionManagerTest extends TestCase
{
    protected mixed $mockFormatter;
    protected mixed $mockDriver;
    protected DateTimeZone $dateTimeZone;

    public function setup(): void
    {
        $this->mockDriver = $this->createMock(DriverInterface::class);
        $this->dateTimeZone = new DateTimeZone(date_default_timezone_get());
    }

    public function testTransactionManager()
    {
        // test result in mock method
        $this->mockDriver->method('writeLogEntryTransaction')
            ->willReturnCallback(function ($logEntryTransaction) {
                $this->assertInstanceOf(LogEntryTransaction::class, $logEntryTransaction);
                foreach ($logEntryTransaction->getLogEntrys() as $logEntry) {
                    $this->assertInstanceOf(LogEntry::class, $logEntry);
                    $logEntryArray = $logEntry->toArray();
                    $this->assertIsArray($logEntryArray);
                    $this->assertInstanceOf(Level::class, $logEntryArray['level']);
                    $this->assertIsString($logEntryArray['message']);
                    $this->assertIsArray($logEntryArray['context']);
                    $this->assertInstanceOf(DateTimeImmutable::class, $logEntryArray['datetime']);
                }
            });

        $logTransaction = new LogEntryTransaction('test_id');
        $transactionManager = new TransactionManager($logTransaction, $this->mockDriver, $this->dateTimeZone);
        $transactionManager->alert('test', ['attr' => 'value']);
        $transactionManager->debug('test', ['attr' => 'value']);
        $transactionManager->commit();
    }

    public function testGetTransactionManagerWithCLIDriverAndLineFormatterAndWrite()
    {
        $logger = LoggerBuilder::build();
        $transaction = $logger->logTransaction('test');
        $transaction->debug('this is a debug');
        $transaction->alert('this is an alert');
        $transaction->error('here is the error');
        ob_start();
        $transaction->commit();
        $lines = ob_get_contents();
        ob_end_clean();
        $this->assertIsString($lines);
        $this->assertDoesNotMatchRegularExpression(
            '/[START TRANSACTION ID: test with Attributes: []]\n
                >> \[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}\] DEBUG: this is a debug []\n
                >> \[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}\] ALERT: this is an alert []\n
                >> \[[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}.[0-9]{3}\] ERROR: here is the error []\n
            [END TRANSACTION ID: test]\n/',
            $lines
        );
    }
}
