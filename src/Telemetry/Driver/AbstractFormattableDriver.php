<?php

declare(strict_types=1);

namespace Telemetry\Driver;

use Telemetry\Exception\MissingFormatterException;
use Telemetry\Formatter\FormatterInterface;

class AbstractFormattableDriver implements FormattableDriverInterface
{
    protected ?FormatterInterface $formatter = null;

    public function getFormatter(): FormatterInterface
    {
        if (!$this->formatter) {
            throw new MissingFormatterException();
        }

        return $this->formatter;
    }

    public function setFormatter(FormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }
}
