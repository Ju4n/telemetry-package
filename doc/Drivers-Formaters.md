# Included Drivers and Formatters

The `Telemetry` logging package comes with a set of built-in drivers and formatters that can be used out of the box. Below are the details of the included drivers and formatters:

### Drivers

1. **CLIDriver**
   - **Description**: The `CLIDriver` is designed for logging messages to the command line interface (CLI). It is particularly useful for development and debugging purposes.
   - **Usage**: The driver formats log entries and transactions using the set formatter and outputs them directly to the console.
   - **Example Output**:
     ```
     [2024-10-21 12:00:00.000] INFO: Log message {"key": "value"}
     ```

2. **FileDriver**
   - **Description**: The `FileDriver` allows logging to a specified file. This driver is suitable for production environments where you want to persist logs to disk.
   - **Usage**: When using this driver, you provide a file path where the logs will be written. The driver will format log entries and transactions using the assigned formatter and write them to the specified file.
   - **Example Output**: The logs will be stored in the specified file in the defined format.

### Formatters

1. **LineFormatter**
   - **Description**: The `LineFormatter` formats log entries into a single line string. Each log entry contains a timestamp, log level, message, and context data.
   - **Format Example**:
     ```
     [2024-10-21 12:00:00.000] INFO: Log message {"key": "value"}
     ```

2. **JSONFormatter**
   - **Description**: The `JSONFormatter` formats log entries and transactions as JSON strings. This is particularly useful for logging in applications where logs need to be processed by other systems or tools.
   - **Format Example**:
     ```json
     {
       "datetime": "2024-10-21T12:00:00.000Z",
       "level": "INFO",
       "message": "Log message",
       "context": {"key": "value"}
     }
     ```

### Summary

The included drivers and formatters provide flexibility for different logging needs. The `CLIDriver` and `FileDriver` allow for outputting logs to the console or a file, while the `LineFormatter` and `JSONFormatter` give you options for how logs are formatted. You can choose the combination that best fits your application requirements, or easily extend them as needed.