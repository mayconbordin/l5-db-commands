<?php namespace Mayconbordin\DbCommands\Drivers;


use Mayconbordin\DbCommands\Contracts\DriverContract;
use Mayconbordin\DbCommands\Exceptions\DriverException;
use Mayconbordin\DbCommands\Exceptions\DriverNotSupportedError;

class DriverFactory
{
    /**
     * Create a driver for the database.
     *
     * @param array $db The configuration of the database.
     * @return DriverContract
     * @throws DriverNotSupportedError
     * @throws DriverException
     */
    public static function create(array $db)
    {
        $driver = null;

        switch ($db['driver']) {
            case MySQLDriver::NAME:
                $driver = new MySQLDriver();
                break;
            case PostgreSQLDriver::NAME:
                $driver = new PostgreSQLDriver();
                break;
            default:
                throw new DriverNotSupportedError("Driver '".$db['driver']."' is not supported.'");
        }

        if ($driver != null) {
            $driver->initialize($db);
        }

        return $driver;
    }
}