<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Refresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Do Refresh Commands!';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Do Refresh Commands!');
        
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:clear');
//         $this->call('optimize');
        
        $this->info('finished!');
    }
}
