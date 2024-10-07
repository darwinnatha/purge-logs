# Purge Logs
<!-- In english -->
<!-- Ce package permet de purger les fichiers de log plus anciens que le nombre de jours spécifié. -->
This package enable to purge logs that have been write before the specified date 

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

Alternatively, if you want to specify the number of days to retain, you can use the `days` argument.

```bash
php artisan logs:purge 30
```

## Configuration
You can configure the number of days to retain by editing the `config/purge-logs.php` file.


```php
return [
    'retention_period' => 30,
];
```