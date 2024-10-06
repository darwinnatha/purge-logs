# Purge Logs

Ce package permet de purger les fichiers de log plus anciens que le nombre de jours spécifié.

## Installation

Pour installer ce package, il suffit d'ajouter le package à votre projet via Composer.

```bash
composer require darwinnatha/pure-logs
```

## Utilisation

Pour utiliser ce package, ajoutez le service provider à votre fichier `config/app.php`.

```php
'providers' => [
    // ...
    Darwinnatha\PurgeLogs\PurgeLogsServiceProvider::class,
],
```

Puis, publiez le fichier de configuration.

```bash
php artisan vendor:publish --provider="Darwinnatha\PurgeLogs\PurgeLogsServiceProvider"
```

desormais, vous pouvez purger les fichiers de log avec la commande `logs:purge`.

```bash
php artisan logs:purge
```

Ou bien, si vous souhaitez spécifier le nombre de jours à conserver, vous pouvez utiliser l'argument `days`.

```bash
php artisan logs:purge 30
```

## Configuration
Publiez le fichier de configuration `config/purge-logs.php` dans votre application.

Vous pouvez configurer le nombre de jours à conserver en modifiant le fichier `config/purge-logs.php`.

Par défaut, le nombre de jours est de 30 jours.

```php
return [
    'retention_period' => 30,
];
```