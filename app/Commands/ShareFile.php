<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ShareFile extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'share {email : The email of the recipient. (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'GLaDOS can email a file for you.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // check if setting exists
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
