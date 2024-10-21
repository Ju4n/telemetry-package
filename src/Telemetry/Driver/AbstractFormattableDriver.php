<?php

declare(strict_types=1);

namespace Telemetry\Driver;

use Telemetry\Formatter\FormatterInterface;

class AbstractFormattableDriver implements FormattableDriverInterface
{
    public function __construct(protected FormatterInterface $formatter)
    {
    }

    public function getFormatter(): FormatterInterface
    {
        return $this->formatter;
    }

    public function setFormatter(FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }
}
