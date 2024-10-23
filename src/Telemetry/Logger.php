<?php

declare(strict_types=1);

namespace Telemetry;

use DateTimeZone;
use Stringable;
use Telemetry\Driver\DriverInterface;

class Logger extends AbstractTelemetryLogger
{
    public function __construct(
        protected ?DriverInterface $driver = null,
        ?DateTimeZone $dateTimezone = null
    ) {
        $dateTimezone = $dateTimezone ?: new DateTimeZone(date_default_timezone_get());
        parent::__construct($dateTimezone);
    }

    /**
     * @inheritdoc
     */
    public function log($level, string | Stringable $message, array $context = []): void
    {
        $logEntry = $this->createLogEntry($level, $message, $context);
        $this->driver->writeLogEntry($logEntry);
    }

    /**
     * @param array<string, string> $attributes
     */
    public function logTransaction(string | int $transactionId, array $attributes = []): TransactionManager
    {
        $logEntryTransaction = new LogEntryTransaction($transactionId, $attributes);

        return new TransactionManager($logEntryTransaction, $this->driver, $this->dateTimezone);
    }

    public function setDriver(DriverInterface $driver): void
    {
        $this->driver = $driver;
    }

    public function getDriver(): DriverInterface
    {
        return $this->driver;
    }
}
