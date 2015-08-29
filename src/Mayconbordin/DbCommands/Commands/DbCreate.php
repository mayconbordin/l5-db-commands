<?php namespace Mayconbordin\DbCommands\Commands;

use Mayconbordin\DbCommands\Exceptions\DriverException;
use \App;

class DbCreate extends DbCommand
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create a new database';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		try {
			$driver = $this->getDatabaseDriver();

			$this->info("Running on '".App::environment()."' environment.");
			$this->info("Creating database '".$this->db['database']."'.");

			$driver->createDb();
			$this->info("Database created.");
		} catch (DriverException $e) {
			$this->error($e->getFullMessage());
		}
	}
}
