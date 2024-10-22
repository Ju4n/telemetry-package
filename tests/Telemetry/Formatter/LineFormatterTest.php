<?php

declare(strict_types=1);

namespace Telemetry\Formatter;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\TestCase;
use Telemetry\Level;
use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

class LineFormatterTest extends TestCase
{
    protected LogEntry $LogEntry;
    protected LogEntryTransaction $logEntryTransaction;
    protected DateTimeImmutable $dateTime;

    #[Before]
    public function setup(): void
    {
        $timezone = new DateTimeZone(date_default_timezone_get());
        $this->dateTime = new DateTimeImmutable('now', $timezone);
        $this->LogEntry = new LogEntry(
            $this->dateTime,
            Level::ALERT,
            'This is a test Message',
            [
                'attribute_1' => 'value_1',
                'attribute_2' => 'value_2',
            ]
        );

        $this->logEntryTransaction = new LogEntryTransaction(
            'transaction_1',
            ['param_1' => 'value_1']
        );
        $this->logEntryTransaction->addLogEntry(new LogEntry(
            $this->dateTime,
            Level::DEBUG,
            'This is a DEBUG message',
            [
                'param_1' => 'value_1',
                'param_2' => 'value_2',
            ]
        ));
        $this->logEntryTransaction->addLogEntry(new LogEntry(
            $this->dateTime,
            Level::ALERT,
            'This is an ALERT message'
        ));
    }

    public function testFormatLogEntry()
    {
        $formatter = new LineFormatter();
        $formattedText = $formatter->formatLogEntry($this->LogEntry);
        $this->assertIsString($formattedText);
        $this->assertEquals(
            $formattedText,
            "[{$this->dateTime->format('Y-m-d H:i:s.v')}] ALERT: This is a test Message {\"attribute_1\":\"value_1\",\"attribute_2\":\"value_2\"}" . PHP_EOL
        );
    }

    public function testFormatLogTransaction()
    {
        $formatter = new LineFormatter();
        $formattedText = $formatter->formatLogTransaction($this->logEntryTransaction);
        $this->assertIsString($formattedText);
        $this->assertEquals(
            $formattedText,
            "[START TRANSACTION ID: transaction_1 with Attributes: {\"param_1\":\"value_1\"}]" . PHP_EOL .
            ">> [{$this->dateTime->format('Y-m-d H:i:s.v')}] DEBUG: This is a DEBUG message {\"param_1\":\"value_1\",\"param_2\":\"value_2\"}" . PHP_EOL .
            ">> [{$this->dateTime->format('Y-m-d H:i:s.v')}] ALERT: This is an ALERT message []" . PHP_EOL .
            "[END TRANSACTION ID: {$this->logEntryTransaction->getTransactionId()}]" . PHP_EOL
        );
    }
}
