<?php

declare(strict_types=1);

declare(strict_types=1);

namespace Telemetry;

use PHPUnit\Framework\TestCase;
use Telemetry\Driver\CLIDriver;
use Telemetry\Driver\FileDriver;
use Telemetry\Formatter\JSONFormatter;
use Telemetry\Formatter\LineFormatter;

class LoggerBuilderTest extends TestCase
{
    const FILENAME = 'test_file.log';

    public function testBuildLoggerWithCLIDriverAndLineFormatter()
    {
        $logger = LoggerBuilder::build();
        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertInstanceOf(CLIDriver::class, $logger->getDriver());
        $this->assertInstanceOf(LineFormatter::class, $logger->getDriver()->getFormatter());
    }

    public function testBuildLoggerWithFileDriverAndLineFormatter()
    {
        $logger = LoggerBuilder::build(new FileDriver(new LineFormatter(), self::FILENAME));
        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertInstanceOf(FileDriver::class, $logger->getDriver());
        $this->assertInstanceOf(LineFormatter::class, $logger->getDriver()->getFormatter());

        // delete file
        unlink(self::FILENAME);
    }

    public function testBuildLoggerWithFileDriverAndJSONFormatter()
    {
        $logger = LoggerBuilder::build(new FileDriver(new JSONFormatter(), self::FILENAME), new JSONFormatter);
        $this->assertInstanceOf(Logger::class, $logger);
        $this->assertInstanceOf(FileDriver::class, $logger->getDriver());
        $this->assertInstanceOf(JSONFormatter::class, $logger->getDriver()->getFormatter());

        // delete file
        unlink(self::FILENAME);
    }

    public function testBuildLoggerAndWrite()
    {
        $logger = LoggerBuilder::build();
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
}
