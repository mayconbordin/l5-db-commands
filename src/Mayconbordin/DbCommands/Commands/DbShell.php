<?php namespace Mayconbordin\DbCommands\Commands;

use Mayconbordin\DbCommands\Exceptions\DriverException;
use \App;

class DbShell extends DbCommand
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:shell';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Open a database shell';

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
			$this->info("Opening shell for database '".$this->db['database']."'.");

			$driver->shell();
			$this->info("Shell closed.");
		} catch (DriverException $e) {
			$this->error($e->getFullMessage());
		}
	}
}
