<?php namespace Mayconbordin\DbCommands\Drivers;

use Mayconbordin\DbCommands\Contracts\DriverContract;
use Mayconbordin\DbCommands\Exceptions\DriverException;

class MySQLDriver implements DriverContract
{
    const NAME = "mysql";

    /**
     * @var \PDO
     */
    private $connection;

    /**
     * @var array
     */
    private $db;

    public function initialize(array $db)
    {
        $this->db = $db;

        try {
            $this->connection = new \PDO("mysql:host=".$db['host'], $db['username'], $db['password']);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new DriverException("Unable to connect to database.", "", $e->getCode(), $e);
        }
    }

    public function createDb()
    {
        $cmd = "CREATE DATABASE IF NOT EXISTS ".$this->db['database']." CHARACTER SET ".array_get($this->db, 'charset', 'utf8')
             . " COLLATE ".array_get($this->db, 'collation', 'utf8_unicode_ci').";";

        try {
            $this->connection->exec($cmd);
        } catch (\PDOException $e) {
            throw new DriverException("Unable to create database.", $cmd, $e->getCode(), $e);
        }
    }

    public function dropDb()
    {
        $cmd = "DROP DATABASE IF EXISTS " . $this->db['database'] . ";";

        try {
            $this->connection->exec($cmd);
        } catch (\PDOException $e) {
            throw new DriverException("Unable to drop database.", $cmd, $e->getCode(), $e);
        }
    }

    public function dump($dataOnly = false)
    {
        $returnVar = null;
        $output    = null;
        $options   = [];

        if ($dataOnly === true) {
            $options[] = '--no-create-info';
        }

        $strCmd = "mysqldump -h %s -u %s -p%s %s %s";
        $cmd = sprintf($strCmd, $this->db['host'], $this->db['username'], $this->db['password'], implode(' ', $options), $this->db['database']);

        exec($cmd, $output, $returnVar);

        if ($returnVar != 0) {
            throw new DriverException("Unable to execute dump on database.", "", $returnVar);
        }

        return implode("\n", $output);
    }

    public function restore($dumpFile)
    {
        $returnVar = null;
        $output    = null;

        $strCmd = "mysql -h %s -u %s -p%s %s < %s";
        $cmd = sprintf($strCmd, $this->db['host'], $this->db['username'], $this->db['password'], $this->db['database'], $dumpFile);

        exec($cmd, $output, $returnVar);

        if ($returnVar != 0) {
            throw new DriverException("Unable to restore the database dump.", "", $returnVar);
        }
    }

    public function shell()
    {
        $strCmd = "mysql -h %s -u %s -p%s";
        $cmd = sprintf($strCmd, $this->db['host'], $this->db['username'], $this->db['password'], $this->db['database']);

        $process = proc_open($cmd, [STDIN, STDOUT, STDERR], $pipes);

        if ($process === false) {
            throw new DriverException("Unable to open database shell.");
        }

        proc_close($process);
    }
}