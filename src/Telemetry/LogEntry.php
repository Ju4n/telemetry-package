<?php

declare(strict_types=1);

namespace Telemetry;

use DateTimeImmutable;
use Stringable;

class LogEntry
{
    /**
     * @param array<string, string> $context
     */
    public function __construct(
        private readonly DateTimeImmutable $dateTime,
        private readonly Level $level,
        private readonly string | Stringable $message,
        private readonly array $context = []
    ) {
    }

    public function getLevel(): Level
    {
        return $this->level;
    }

    public function getMessage(): string | Stringable
    {
        return $this->message;
    }

    /**
     * @return array<string, string>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    public function getDateTime(): DateTimeImmutable
    {
        return $this->dateTime;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'datetime' => $this->dateTime,
            'level' => $this->level,
            'message' => $this->message,
            'context' => $this->context,
        ];
    }
}
