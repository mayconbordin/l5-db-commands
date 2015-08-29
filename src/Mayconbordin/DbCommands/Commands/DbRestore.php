<?php namespace Mayconbordin\DbCommands\Commands;

use Mayconbordin\DbCommands\Exceptions\DriverException;
use Symfony\Component\Console\Input\InputArgument;

use \App;

class DbRestore extends DbCommand
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:restore';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Restore the database from an SQL dump.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        try {
            $dumpFile = $this->argument('dump-file');
            $driver = $this->getDatabaseDriver();

            $this->info("Running on '" . App::environment() . "' environment.");
            $this->info("Restoring database '" . $this->db['database'] . "'.");

            $driver->restore($dumpFile);

            $this->info("Database restored.");
        } catch (DriverException $e) {
            $this->error($e->getFullMessage());
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
            ['dump-file', InputArgument::REQUIRED, 'The SQL dump to be applied in the database.', null],
		];
	}
}
