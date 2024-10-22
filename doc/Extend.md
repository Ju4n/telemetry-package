# Extending Drivers and Formatters

The `Telemetry` logging package is designed to be flexible and extensible, allowing you to create custom drivers and formatters that suit your specific logging needs. Below are the steps to extend the existing drivers and formatters.

### Extending Drivers

To create a custom driver, you need to implement the `DriverInterface` and optionally extend the `AbstractFormattableDriver` if your driver requires formatting capabilities. Here’s how to do it:

1. **Create Your Custom Driver Class**:
   - Implement the methods defined in the `DriverInterface`.
   - If you want to add formatting capabilities, extend the `AbstractFormattableDriver`.

```php
namespace Telemetry\Driver;

use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

class MyCustomDriver extends AbstractFormattableDriver implements DriverInterface
{
    public function writeLogEntry(LogEntry $logEntry): void
    {
        // Your custom logic to write log entry
    }

    public function writeLogEntryTransaction(LogEntryTransaction $transaction): void
    {
        // Your custom logic to write log transaction
    }
}
```

2. **Set a Formatter**:
If your driver extends `AbstractFormattableDriver`, you have to pass the formatter in the constructor.

```php
$formatter = new MyCustomFormatter();
$myDriver = new MyCustomDriver($formatter);
```

### Extending Formatters

To create a custom formatter, implement the `FormatterInterface` and define the formatting logic for both log entries and log transactions.

1. **Create Your Custom Formatter Class**:

```php
namespace Telemetry\Formatter;

use Telemetry\LogEntry;
use Telemetry\LogEntryTransaction;

class MyCustomFormatter implements FormatterInterface
{
    public function formatLogEntry(LogEntry $logEntry): string
    {
        // Your custom logic to format a log entry
    }

    public function formatLogTransaction(LogEntryTransaction $logEntryTransaction): string
    {
        // Your custom logic to format a log transaction
    }
}
```

2. **Use Your Custom Formatter**:
   You can use your custom formatter by passing it to the driver when setting it up.

```php
$formatter = new MyCustomFormatter();
$driver = new MyCustomDriver($formatter);
$logger = new Logger($driver);
```

### Summary

By following these steps, you can easily extend both drivers and formatters in the `Telemetry` logging package. This allows you to customize how logs are recorded and formatted, ensuring that your logging solution meets the specific requirements of your application.