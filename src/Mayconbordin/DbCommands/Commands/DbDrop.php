<?php namespace Mayconbordin\DbCommands\Commands;

use Mayconbordin\DbCommands\Exceptions\DriverException;
use Symfony\Component\Console\Input\InputOption;
use \App;

class DbDrop extends DbCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:drop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop a database';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        try {
            $auto   = $this->option("auto");
            $driver = $this->getDatabaseDriver();

            $this->info("Running on '" . App::environment() . "' environment.");
            $this->info("This command is about to drop the database '" . $this->db['database'] . "'.");

            if ($auto === true || $this->confirm('Do you wish to continue? [y|N]')) {
                $driver->dropDb();
                $this->info("Database dropped.");
            } else {
                $this->info("Command aborted.");
            }
        } catch (DriverException $e) {
            $this->error($e->getFullMessage());
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(parent::getOptions(), [
            ['no-interaction', 'n', InputOption::VALUE_NONE, 'Do not ask any interactive question.'],
        ]);
    }
}
