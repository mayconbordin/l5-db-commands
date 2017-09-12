<?php namespace Mayconbordin\DbCommands\Drivers;

use Mayconbordin\DbCommands\Contracts\DriverContract;
use Mayconbordin\DbCommands\Exceptions\DriverException;

class PostgreSQLDriver implements DriverContract
{
    const NAME = "pgsql";

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
            $this->connection = new \PDO("pgsql:host={$db['host']};user={$db['username']};password={$db['password']}");
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new DriverException("Unable to connect to database.", "", $e->getCode(), $e);
        }
    }

    public function createDb()
    {
        $cmd = "CREATE DATABASE ".$this->db['database']." ENCODING '".array_get($this->db, 'charset', 'utf8')."';";

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
            $options[] = '--data-only';
        }

        $strCmd = "pg_dump -h %s -U %s %s %s";
        $cmd = sprintf($strCmd, $this->db['host'], $this->db['username'], implode(' ', $options), $this->db['database']);

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

        $strCmd = "psql -h %s -U %s %s < %s";
        $cmd = sprintf($strCmd, $this->db['host'], $this->db['username'], $this->db['database'], $dumpFile);

        exec($cmd, $output, $returnVar);

        if ($returnVar != 0) {
            throw new DriverException("Unable to restore the database dump.", "", $returnVar);
        }
    }

    public function shell()
    {
        $strCmd = "psql -h %s -U %s %s";
        $cmd = sprintf($strCmd, $this->db['host'], $this->db['username'], $this->db['database']);

        $process = proc_open($cmd, [STDIN, STDOUT, STDERR], $pipes);

        if ($process === false) {
            throw new DriverException("Unable to open database shell.");
        }

        proc_close($process);
    }

    public function executeSql($sqlFile)
    {
        $returnVar = null;
        $output    = null;

        $strCmd = "psql -h %s -U %s %s < %s";
        $cmd = sprintf($strCmd, $this->db['host'], $this->db['username'], $this->db['database'], $sqlFile);

        exec($cmd, $output, $returnVar);

        if ($returnVar != 0) {
            throw new DriverException("Unable to execute SQL file in the database.", "", $returnVar);
        }
    }
}