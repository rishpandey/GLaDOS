<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\File;
use League\Flysystem\Exception;

class GenerateGitignore extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'gitignore {tags : Comma separated tags used with gitignore.io like laravel, phpstorm etc. (required)}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Download .gitignore file from gitignore.io';

    private $client = null;

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
        $tags = collect(explode(',', $this->argument('tags')));
        $tags = $tags->filter(function($tag){
            return strlen($tag) > 0;
        });

        $queryableTags = collect([]);
        $badTags = collect([]);

        $this->task("Validating tags", function () use($tags, $queryableTags, $badTags){
            $validTags = $this->client->request('GET', 'https://www.gitignore.io/dropdown/templates.json')->getBody();
            $validTags = collect(json_decode($validTags));

            foreach ($tags as $userTag) {
                $isUserTagValid = $validTags->search(function($tag, $key) use($userTag){
                    return strcasecmp($tag->text, $userTag) == 0;
                });

                if($isUserTagValid == false) {
                    $badTags[] = $userTag;
                } else {
                    $queryableTags[] = $userTag;
                }
            }

            if(count($queryableTags) > 0){
                return true;
            }else{
                $this->error("\nGiven tags are invalid. Visit https://www.gitignore.io/");
                return false;
            }
        });


        if(count($badTags) > 0 && count($queryableTags) > 0) {

            if ($this->confirm("You have invalid tags, '". implode(',', $badTags->toArray()) ."'. Do you wish to continue?")) {
                $this->downloadFile($queryableTags->toArray());
            }

        } else if(count($queryableTags) > 0) {

            $this->downloadFile($queryableTags->toArray());

        } else {

            return;

        }

    }

    public function downloadFile($tags)
    {
        $this->task("Downloading File", function () use($tags) {

            try {

                $content = $this->client->request('GET', 'https://www.gitignore.io/api/'. implode(',', $tags))->getBody();
                File::put(getcwd() . DIRECTORY_SEPARATOR . ".gitignore", $content);

                return true;
            } catch (Exception $exception) {
                return false;
            }

        });
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
