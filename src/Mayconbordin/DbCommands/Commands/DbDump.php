<?php namespace Mayconbordin\DbCommands\Commands;

use Mayconbordin\DbCommands\Exceptions\DriverException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use \App;

class DbDump extends DbCommand
{
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'db:dump';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Dump the database into an SQL file or print on screen.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        try {
            $output = $this->argument('output');
            $dataOnly = $this->option("data-only");
            $driver = $this->getDatabaseDriver();

            $this->info("Running on '" . App::environment() . "' environment.");
            $this->info("Dumping database '" . $this->db['database'] . "'.");

            $dump = $driver->dump($dataOnly);

            if ($output != null) {
                $this->writeToFile($output, $dump);
                $this->info("Dump of database saved.");
            } else {
                echo $dump;
            }
        } catch (DriverException $e) {
            $this->error($e->getFullMessage());
        }
	}

    /**
     * Write the dump content to a file.
     *
     * @param string $output
     * @param string $dump
     */
    protected function writeToFile($output, $dump)
    {
        $file = fopen($output, "w");

        if ($file === false) {
            $this->error("Unable to open file '$output'");
            exit;
        }

        fwrite($file, $dump);
        fclose($file);
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
            ['output', InputArgument::OPTIONAL, 'Where to dump the SQL.', null],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array_merge(parent::getOptions(), [
            ['data-only', null, InputOption::VALUE_NONE, 'Will dump only the data.'],
        ]);
	}
}
