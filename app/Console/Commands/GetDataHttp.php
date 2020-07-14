<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetDataHttp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'http:post
                        {url : URL from where you want to get data}
                        {--Q|queue=1 : Number of requests to make}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get data from url';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $n = $this->option('queue');
        $bar = $this->output->createProgressBar($n);

        $bar->start();
        for ($i=0; $i < $n; $i++) { 
            
            \App\Jobs\GetDataHttpJob::dispatch($this->argument('url'), 'POST');

            $bar->advance();
        }
        $bar->finish();
        return 0;
    }
}
