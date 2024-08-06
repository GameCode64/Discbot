<?php

namespace App\Console\Commands;

use App\Http\Controllers\BotController;
use Illuminate\Console\Command;

class service extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'service:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the bot';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        while(true)
        {
            (new BotController)->StartBot($this);
            $this->error("Bot has been stopped");
        }
    }
}
