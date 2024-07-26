# AstroExceptions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/astroselling/astro-exceptions.svg?style=flat-square)](https://packagist.org/packages/astroselling/astro-exceptions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/astroselling/astro-exceptions/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/astroselling/astro-exceptions/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/astroselling/astro-exceptions/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/astroselling/astro-exceptions/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/astroselling/astro-exceptions.svg?style=flat-square)](https://packagist.org/packages/astroselling/astro-exceptions)

Laravel package to manage exceptions

## Installation

You can install the package via composer:

```bash
composer require astroselling/astro-exceptions
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="astro-exceptions-config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$astroExceptions = new Astroselling\AstroExceptions();
echo $astroExceptions->echoPhrase('Hello, Astroselling!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Enzo Notario](https://github.com/enzonotario)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
