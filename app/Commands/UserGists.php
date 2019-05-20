<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class UserGists extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'gists {username : The github username (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'GLaDOS can search from your public gists';

    private $client = null;
    private $errors = [];

    public function __construct()
    {
        parent::__construct();
        $this->client = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $response = [];

        $this->task('Fetching Gists', function() use(&$response){

            $username = $this->argument('username');

            try {

                $response = json_decode($this->client->request('GET', "https://api.github.com/users/$username/gists")->getBody());


            } catch (ClientException $e){
                $this->errors[] = 'Username not found.';
                return false;
            }

        });

        if(count($this->errors) > 0){
            $this->error(implode(',', $this->errors));
            return;
        }

        $this->getGistQuery($response);
    }

    public function getGistQuery($response)
    {
        $query = $this->ask('Give a search query for filename or gist description.');

        $filteredGists = $this->searchGists($response, $query);

        if (count($filteredGists) == 0) {
            if ($this->confirm('There were no gists with the given query. Do want to try again?')) {
                $this->getGistQuery($response);
            }
        } else {

            if (count($filteredGists) > 1) {

                $gistNames = [];
                $gistChoices = [];

                foreach ( $filteredGists as $gist) {
                    $gistChoices[] = $gist->html_url;

                    if(strlen($gist->description) > 0){
                        $gistNames[] = $gist->description . " [Description]";
                    }else{

                        $fileNames = [];

                        foreach ($gist->files as $file) {
                            $fileNames[] = $file->filename;
                        }

                        $gistNames[] = implode(',', $fileNames) . " [Files]";
                    }
                }

                $selectedGist = $this->choice('Which one of these?', $gistNames);

                $choice = array_search($selectedGist, $gistNames);

                $this->info($gistChoices[$choice]);


            } else {

                $this->info($filteredGists[0]->html_url);

            }

        }
    }

    public function searchGists($response, $query){
        $filteredGists = [];

        foreach ($response as $gist) {

            // dd($gist);

            if (strpos( strtolower($gist->description) , strtolower($query)) !== false) {
                $filteredGists[] = $gist;
            } else {
                foreach ($gist->files as $file) {
                    if ( strpos(strtolower($file->filename) , strtolower($query) )) {
                        $filteredGists[] = $gist;
                    }
                }
            }
        }

        return $filteredGists;
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
