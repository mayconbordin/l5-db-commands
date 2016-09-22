# l5-db-commands

[![Latest Stable Version](https://poser.pugx.org/mayconbordin/l5-db-commands/version)](https://packagist.org/packages/mayconbordin/l5-db-commands) [![Total Downloads](https://poser.pugx.org/mayconbordin/l5-db-commands/downloads)](https://packagist.org/packages/mayconbordin/l5-db-commands) [![Latest Unstable Version](https://poser.pugx.org/mayconbordin/l5-db-commands/v/unstable)](//packagist.org/packages/mayconbordin/l5-db-commands) [![License](https://poser.pugx.org/mayconbordin/l5-db-commands/license)](https://packagist.org/packages/mayconbordin/l5-db-commands)

A set of commands to create/drop/dump/restore/shell databases on Laravel 5.

**Supported DBMSs**: MySQL, PostgreSQL and SQLite.

## Installation

In order to install just add 

```json
"mayconbordin/l5-db-commands": "dev-master"
```

to your composer.json. Then run `composer install` or `composer update`.

Then in your `config/app.php` add 

```php
'Mayconbordin\DbCommands\DbCommandsServiceProvider'
```

in the `providers` array.

## Commands

#### `db:create [options]`

Create a new database with the default connection from the configuration file.

*Options*:
 - `--database`: The name of the database connection in the configuration file.

#### `db:drop [options]`

Drop an existing database with the default connection from the configuration file.

*Options*:
 - `--database`: The name of the database connection in the configuration file.

#### `db:dump [options] [--] [<output>]`

Dump the schema and data of an existing database with the default connection from the configuration file. By
default the dump is printed on the screen, optionally it can be written to `<output>`.

*Options*:
 - `--database`: The name of the database connection in the configuration file.
 - `--data-only`: Dumps only the data.

#### `db:restore [options] [--] <dump-file>`

Restore the schema and data from `<dump-file>` to an existing database with the default connection from the configuration file.

*Options*:
 - `--database`: The name of the database connection in the configuration file.

#### `db:shell [options]`

Open a shell to an existing database with the default connection from the configuration file.

> For PostgreSQL you might have to create a `~/.pgpass` file with `localhost:5432:mydbname:postgres:mypass` and chmod 600.

*Options*:
 - `--database`: The name of the database connection in the configuration file.
