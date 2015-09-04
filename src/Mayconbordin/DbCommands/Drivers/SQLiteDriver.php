<?php namespace Mayconbordin\DbCommands\Drivers;

use Mayconbordin\DbCommands\Contracts\DriverContract;
use Mayconbordin\DbCommands\Exceptions\CommandNotExistsError;
use Mayconbordin\DbCommands\Exceptions\DriverException;
use Mayconbordin\DbCommands\Utils\CmdUtils;

class SQLiteDriver implements DriverContract
{
    const NAME = "sqlite";
    const CMD  = "sqlite3";

    /**
     * @var \SQLite3
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
        if (file_exists($this->db['database'])) return;

        try {
            $this->connection = new \SQLite3($this->db['database'], SQLITE3_OPEN_CREATE);
        } catch (\Exception $e) {
            throw new DriverException("Unable to create database.", "", $e->getCode(), $e);
        }
    }

    public function dropDb()
    {
        if (!file_exists($this->db['database'])) return;

        if (!unlink($this->db['database'])) {
            throw new DriverException("Unable to drop database {$this->db['database']}.");
        }
    }

    public function dump($dataOnly = false)
    {
        if (!CmdUtils::commandExists(self::CMD)) {
            throw new CommandNotExistsError("Command ".self::CMD." does not eixsts.");
        }

        $returnVar = null;
        $output    = null;

        $cmd = sprintf("%s %s .dump", self::CMD, $this->db['database']);

        exec($cmd, $output, $returnVar);

        if ($returnVar != 0) {
            throw new DriverException("Unable to execute dump on database.", "", $returnVar);
        }

        return implode("\n", $output);
    }

    public function restore($dumpFile)
    {
        if (!CmdUtils::commandExists(self::CMD)) {
            throw new CommandNotExistsError("Command ".self::CMD." does not eixsts.");
        }

        $returnVar = null;
        $output    = null;

        $cmd = sprintf("%s %s < %s", self::CMD, $this->db['database'], $dumpFile);

        exec($cmd, $output, $returnVar);

        if ($returnVar != 0) {
            throw new DriverException("Unable to restore the database dump.", "", $returnVar);
        }
    }

    public function shell()
    {
        if (!CmdUtils::commandExists(self::CMD)) {
            throw new CommandNotExistsError("Command ".self::CMD." does not eixsts.");
        }

        $cmd = sprintf("%s %s", self::CMD, $this->db['database']);

        $process = proc_open($cmd, [STDIN, STDOUT, STDERR], $pipes);

        if ($process === false) {
            throw new DriverException("Unable to open database shell.");
        }

        proc_close($process);
    }
}