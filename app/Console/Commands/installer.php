<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class installer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run this first to setup!';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $DiscordToken = $this->ask("Please enter your discord token here");
        file_put_contents(__DIR__."/../../../.env", "DISCORD_TOKEN=\"$DiscordToken\"");
        Artisan::call("migrate");
        Artisan::call("key:generate");
    }
}
