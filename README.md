# Telemetry Logger 📜

![Tests](https://github.com/Ju4n/telemetry-package/actions/workflows/tests.yml/badge.svg)

**Telemetry Logger** is an extensible PHP package that allows logging to multiple destinations (drivers) and formats. It follows the [PSR-3](https://www.php-fig.org/psr/psr-3/) logging standard and is designed to be flexible and easily extensible with custom drivers and formatters.

## Features

- Supports multiple drivers: CLI and file.
- Extensible for adding custom drivers and formatters.
- Easy to use with a configurable `LoggerBuilder`.
- Supports log transactions.
- PSR-3 compliant.

## Requirements

- PHP 8.2 or higher.
- [Composer](https://getcomposer.org/) for dependency management.
- [PHPUnit](https://phpunit.de/) for running tests.

## Installation

You can install the package via Composer:

```bash
composer require ju4n/telemetry-logger
```

## Documentation

- [Usage](doc/Usage.md)
- [Extending Drivers and Formatters](/doc/Extend.md)
- [Included Drivers and Formatters](/doc/Drivers-Formaters.md)
- [UML Diagram](/doc/uml_diagram.png)


## Running Tests

This project includes a set of unit tests that you can run using [PHPUnit](https://phpunit.de/).

First, ensure that you have installed the development dependencies:

```bash
composer install --dev
```

Then, you can run the tests with the following command:

```bash
vendor/bin/phpunit
```

You can also generate a code coverage report:

```bash
vendor/bin/phpunit --coverage-html coverage/
```

If you don't have PHP installed on your local machine, you can run the tests using `docker-compose` by executing:

```bash
docker-compose run composer install && docker-compose run phpunit
```

## Diagram
![UML](/doc/uml_diagram.png)

---
### License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).