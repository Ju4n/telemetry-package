<?php

declare(strict_types=1);

namespace Telemetry;

use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use Psr\Log\AbstractLogger;
use Stringable;

abstract class AbstractTelemetryLogger extends AbstractLogger
{
    public function __construct(protected DateTimeZone $dateTimezone)
    {
    }

    protected function validateLevel(string $level): Level
    {
        $level = Level::tryFrom($level);
        if (!$level) {
            throw new InvalidArgumentException(
                \sprintf("Level should be an PSR-3 level compatible (%s)", implode(', ', Level::getLevels()))
            );
        }

        return $level;
    }

    /**
     * @param array<string, string> $context
     */
    protected function createLogEntry(string $level, string|Stringable $message, array $context = []): LogEntry
    {
        $level = $this->validateLevel($level);
        $dateTime = new DateTimeImmutable('now', $this->dateTimezone);

        return new LogEntry($dateTime, $level, $message, $context);
    }
}
