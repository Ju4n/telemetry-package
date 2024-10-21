# Usage

## Using LoggerBuilder

The `LoggerBuilder` class provides a convenient way to create and configure a logger instance. It allows you to specify the logging driver and formatter easily. Below are examples of how to use it in different scenarios.

### Default Logger

You can create a default logger that logs to the CLI using the default line formatter:

```php
use Telemetry\LoggerBuilder;

$logger = LoggerBuilder::build();

// Log a message with default settings
$logger->log(Level::INFO, 'This is an info message');
```

### Custom Formatter

If you want to use a custom formatter, you can pass it as an argument to the `build` method. Here’s an example using the `JSONFormatter`:

```php
use Telemetry\LoggerBuilder;
use Telemetry\Formatter\JSONFormatter;

$formatter = new JSONFormatter();
$logger = LoggerBuilder::build(formatter: $formatter);

// Log a message
$logger->log(Level::INFO, 'This is an info message');
```

### Custom Driver

You can also specify a custom driver, such as `FileDriver`, to log messages to a file. Here’s how to do it:

```php
use Telemetry\LoggerBuilder;
use Telemetry\Driver\FileDriver;

$fileDriver = new FileDriver('path/to/logfile.log');
$logger = LoggerBuilder::build($fileDriver);

// Log a message
$logger->log(Level::ERROR, 'This is an error message');
```

### Custom Driver with Custom Formatter

You can combine both a custom driver and a custom formatter as well:

```php
use Telemetry\LoggerBuilder;
use Telemetry\Driver\FileDriver;
use Telemetry\Formatter\JSONFormatter;

$fileDriver = new FileDriver('path/to/logfile.log');
$formatter = new JSONFormatter();
$logger = LoggerBuilder::build($fileDriver, $formatter);

// Log a message
$logger->log(Level::DEBUG, 'Debugging information');
```

__Note:__ Driver must implements `FormattableDriverInterface` to use a Formatter.

## Using Logger Directly

You can also create an instance of the `Logger` class directly. This allows for more control over the logging process. Below are examples of how to use the `Logger` class directly.

### Basic Logger Initialization

To create a logger, you need to provide a logging driver, if the Driver implements `FormattableDriverInterface` you must add a Formatter. Here’s an example using the `CLIDriver`:

```php
use Telemetry\Logger;
use Telemetry\Driver\CLIDriver;
use Telemetry\Formatter\JSONFormatter;

$driver = new CLIDriver();
$formatter = new JSONFormatter();
$driver->setFormatter($formatter);
$logger = new Logger($driver);

// Log a message
$logger->log(Level::INFO, 'This is an info message');
```

### Logger with FileDriver

You can also use the `FileDriver` to log messages to a file:

```php
use Telemetry\Logger;
use Telemetry\Driver\FileDriver;
use Telemetry\Formatter\LineFormatter;

$driver = new FileDriver('path/to/logfile.log');
$formatter = new LineFormatter();
$driver->setFormatter($formatter);
$logger = new Logger($driver);

// Log a message
$logger->log(Level::ERROR, 'This is an error message');
```

### Setting the Logger Driver

You can change the driver of an existing logger instance by using the `setDriver` method:

```php
use Telemetry\Logger;
use Telemetry\Driver\CLIDriver;
use Telemetry\Driver\FileDriver;

// Add CLI Driver
$driver = new CLIDriver();
$formatter = new LineFormatter();
$driver->setFormatter($formatter);
$logger = new Logger($driver);
$logger->log(Level::INFO, 'Logging to CLI');

// Change to FileDriver
$fileDriver = new FileDriver('path/to/logfile.log');
$fileDriver->setFormatter($formatter);
$logger->setDriver($fileDriver);

// Log a message to the file
$logger->log(Level::WARNING, 'This is a warning message');
```

### Summary
In summary, LoggerBuilder is best suited for users looking for a quick and simple setup, while Logger is tailored for those who need comprehensive control over their logging configuration. Depending on your requirements, you can choose the approach that best fits your use case.