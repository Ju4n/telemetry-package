<?php

declare(strict_types=1);

namespace Telemetry\Driver;

use Telemetry\Formatter\FormatterInterface;

interface FormattableDriverInterface
{
    public function setFormatter(FormatterInterface $formatter): void;

    public function getFormatter(): FormatterInterface;
}
