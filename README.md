# l5-db-commands

A set of commands to create/drop/dump/restore databases on Laravel 5.

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
