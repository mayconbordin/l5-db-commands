<?php namespace Mayconbordin\DbCommands\Commands;

use \DB;
use \Config;

use Illuminate\Console\Command;
use Mayconbordin\DbCommands\Contracts\DriverContract;
use Mayconbordin\DbCommands\Drivers\DriverFactory;
use Mayconbordin\DbCommands\Exceptions\DriverException;
use Mayconbordin\DbCommands\Exceptions\DriverNotSupportedError;
use Symfony\Component\Console\Input\InputOption;

abstract class DbCommand extends Command
{
    /**
     * @var DriverContract
     */
    protected $driver;

    /**
     * @var array
     */
    protected $db;

    /**
     * Get the driver for the database.
     *
     * @return DriverContract
     * @throws DriverNotSupportedError
     * @throws DriverException
     */
    protected function getDatabaseDriver()
    {
        if ($this->driver == null) {
            $database = $this->option("database");
            $this->db = $this->getDatabaseInfo($database);

            // check if database exists on configuration
            if ($this->db == null) {
                $this->error("The database '" . $database . "' does not exist.");
                exit;
            }

            $this->driver = DriverFactory::create($this->db);
        }

        return $this->driver;
    }

    /**
     * Get information about the database from the configuration.
     *
     * @param string|null $database The name of the database connection.
     * @return array|null The database information as an associative array.
     */
    protected function getDatabaseInfo($database = null)
    {
        if ($database == null) {
            $database = Config::get('database.default');
        }

        $connections = Config::get('database.connections');

        return isset($connections[$database]) ? $connections[$database] : null;
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The name of the database connection.', null]
        ];
    }
}