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

class JSONFormatterTest extends TestCase
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

    public function testFormatLogEntry(): void
    {
        $dateTime = $this->dateTime->format('Y-m-d H:i:s.v');
        $formatter = new JSONFormatter();
        $formattedText = $formatter->formatLogEntry($this->LogEntry);
        $this->assertIsString($formattedText);
        $this->assertEquals(
            $formattedText,
            "{\"datetime\":\"{$dateTime}\",\"level\":\"ALERT\",\"message\":\"This is a test Message\",\"context\":{\"attribute_1\":\"value_1\",\"attribute_2\":\"value_2\"}}\n"
        );
    }

    public function testFormatLogTransaction(): void
    {
        $dateTime = $this->dateTime->format('Y-m-d H:i:s.v');
        $formatter = new JSONFormatter();
        $formattedText = $formatter->formatLogTransaction($this->logEntryTransaction);
        $this->assertIsString($formattedText);
        $this->assertEquals(
            $formattedText,
            "{\"transaction_id\":\"transaction_1\",\"attributes\":{\"param_1\":\"value_1\"},\"logs\":[{\"datetime\":\"{$dateTime}\",\"level\":\"DEBUG\",\"message\":\"This is a DEBUG message\",\"context\":{\"param_1\":\"value_1\",\"param_2\":\"value_2\"}},{\"datetime\":\"{$dateTime}\",\"level\":\"ALERT\",\"message\":\"This is an ALERT message\",\"context\":[]}]}\n"
        );
    }
}
