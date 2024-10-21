# Usage

In the following sections, we'll explore how to effectively use the Logger class to meet your logging requirements.

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

### Custom Driver with Custom Formatter

You can combine both a custom driver and a custom formatter as well:

```php
use Telemetry\LoggerBuilder;
use Telemetry\Driver\FileDriver;
use Telemetry\Formatter\JSONFormatter;

$formatter = new JSONFormatter();
$fileDriver = new FileDriver($formatter, 'path/to/logfile.log');
$logger = LoggerBuilder::build($fileDriver);

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

$formatter = new JSONFormatter();
$driver = new CLIDriver($formatter);
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

$formatter = new LineFormatter();
$driver = new FileDriver($formatter, 'path/to/logfile.log');
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
$formatter = new LineFormatter();
$driver = new CLIDriver($formatter);
$logger = new Logger($driver);
$logger->log(Level::INFO, 'Logging to CLI');

// Change to FileDriver
$fileDriver = new FileDriver($formatter, 'path/to/logfile.log');
$logger->setDriver($fileDriver);

// Log a message to the file
$logger->log(Level::WARNING, 'This is a warning message');
```
### Summary
In summary, `LoggerBuilder` is best suited for users looking for a quick and simple setup, while `Logger` is tailored for those who need comprehensive control over their logging configuration. Depending on your requirements, you can choose the approach that best fits your use case.

## Using Transactions in the Logger

The `Telemetry` logging package supports transaction logging, allowing you to group related log entries under a single transaction ID. This is useful for tracking the flow of events in complex operations. Here’s how to use transactions with the `Logger`.

### Starting a Transaction

To start a transaction, use the `logTransaction` method of the `Logger` class. You need to provide a unique transaction ID and an optional array of attributes.

**Example**:

```php
$logger = LoggerBuilder::build();
$transaction = $logger->logTransaction('transaction-123', ['user_id' => 1]);
```

### Adding Log Entries to a Transaction

Once the transaction is started, you can add log entries to it using the `addLogEntry` method of the `TransactionManager`. You will need to specify the log level, message, and optional context for each log entry.

**Example**:

```php
$transaction->addLogEntry(Level::INFO, 'This is an info message');
$transaction->addLogEntry(Level::ERROR, 'This is an error message', ['error_code' => 404]);
```

### Committing the Transaction

After adding all desired log entries, you must commit the transaction using the `commit` method of the `TransactionManager`. This action will write the entire transaction log to the specified driver (e.g., CLI or file).

**Example**:

```php
$transaction->commit();
```

### Summary of Transaction Usage

1. **Start a Transaction**: Use `logTransaction` with a unique ID and optional attributes.
2. **Add Log Entries**: Use PSR-3 log level methods to add multiple log entries to the transaction.
3. **Commit the Transaction**: Call `commit` to finalize and write the transaction log.

### Example Workflow

Here is a complete example of using transactions with the logger:

```php
$logger = LoggerBuilder::build();
$transaction = $logger->logTransaction('transaction-123', ['user_id' => 1]);

$transaction->info('Starting process...');
$transaction->debug('Process details', ['step' => 1]);
$transaction->error('An error occurred', ['error_code' => 404]);

$transaction->commit();
```