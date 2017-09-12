<?php namespace Mayconbordin\DbCommands\Commands;

use Mayconbordin\DbCommands\Exceptions\DriverException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use \App;

class DbExecuteSql extends DbCommand
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:exec:sql';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Execute an SQL file in the database.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        try {
            $file   = $this->argument('file');
            $driver = $this->getDatabaseDriver();

            $this->info("Running on '" . App::environment() . "' environment.");
            $this->info("Running SQL file '" . $file . "' on database '" . $this->db['database'] . "'.");

            $driver->executeSql($file);
			$this->info("SQL file executed.");
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
            ['file', InputArgument::REQUIRED, 'The SQL file path.', null],
		];
	}
}
