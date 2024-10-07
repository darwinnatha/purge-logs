# Purge Logs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/darwinnatha/purge-logs.svg?style=flat-square)](https://packagist.org/packages/darwinnatha/purge-logs)
[![Total Downloads](https://img.shields.io/packagist/dt/darwinnatha/purge-logs.svg?style=flat-square)](https://packagist.org/packages/darwinnatha/purge-logs)

This package allows you to delete logs, keeping only those from a given period. 

## Installation

To install this package, you should add the package to your project via Composer.

```bash
composer require darwinnatha/pure-logs
```

## Usage

To use this package, publish the configuration file.

```bash
php artisan vendor:publish --provider="Darwinnatha\PurgeLogs\PurgeLogsServiceProvider"
```

Then, you can purge the logs by running the following command.

```bash
php artisan logs:purge
```

Alternatively, if you want to specify the number of days to retain, you can use the `keep-days` argument.

```bash
php artisan logs:purge --keep-days=7
```

## Configuration
You can configure the number of days to retain by editing the `config/purge-logs.php` file.



```php
return [
    'retention_period' => 7,
];
```
