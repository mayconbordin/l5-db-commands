<?php namespace Mayconbordin\DbCommands\Contracts;

use Mayconbordin\DbCommands\Exceptions\DriverException;

interface DriverContract
{
    /**
     * Initialize the database connection based on the information provided.
     *
     * @param array $db [driver=string; database=string; host=string; username=string; password=string; charset=string,default:utf8;
     *                   collation=string,default:utf8_unicode_ci]
     * @return void
     * @throws DriverException
     */
    public function initialize(array $db);

    /**
     * Create the database.
     *
     * @return void
     * @throws DriverException
     */
    public function createDb();

    /**
     * Drop the database.
     *
     * @return void
     * @throws DriverException
     */
    public function dropDb();

    /**
     * Dump the database schema and/or data and return it.
     *
     * @param bool|false $dataOnly If only the data should be exported.
     * @return string
     * @throws DriverException
     */
    public function dump($dataOnly = false);

    /**
     * Restore the database with the dump file.
     *
     * @param string $dumpFile Full path to the dump file.
     * @return void
     * @throws DriverException
     */
    public function restore($dumpFile);

    /**
     * Open a shell for the database.
     *
     * @return void
     * @throws DriverException
     */
    public function shell();

    /**
     * Run a sql file on the database.
     *
     * @return void
     * @throws DriverException
     */
     public function executeSql($sqlFile);
}