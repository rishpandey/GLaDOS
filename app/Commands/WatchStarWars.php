<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class WatchStarWars extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'starwars';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Watch Star Wars: Episode IV in ASCII';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("GLaDos just executes a telnet request to another server all the credit belongs to the following.\n");

        $this->info("Original Work   : Simon Jansen ( http://www.asciimation.co.nz/ )");
        $this->info("Telnetification : Sten Spans ( http://blinkenlights.nl/ )");
        $this->info("Terminal Tricks : Mike Edwards (pf-asciimation@mirkwood.net)");

        sleep(2);

        system('telnet towel.blinkenlights.nl');
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
