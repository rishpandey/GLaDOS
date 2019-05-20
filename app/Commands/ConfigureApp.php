<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ConfigureApp extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'configure';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Feed GLaDOS with information.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('GLaDOS does not need any details from you yet.');
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
