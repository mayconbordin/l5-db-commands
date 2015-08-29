<?php namespace Mayconbordin\DbCommands;

use Illuminate\Support\ServiceProvider;
use Mayconbordin\DbCommands\Commands\DbCreate;
use Mayconbordin\DbCommands\Commands\DbDrop;
use Mayconbordin\DbCommands\Commands\DbDump;
use Mayconbordin\DbCommands\Commands\DbRestore;
use Mayconbordin\DbCommands\Commands\DbShell;

class DbCommandsServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands([
            DbCreate::class, DbDrop::class, DbDump::class, DbRestore::class, DbShell::class
        ]);
    }
}