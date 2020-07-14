<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use Exception;
use App\Response as ResponseLog;
use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Http;

class GetDataHttpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $url;
    protected $type;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($url, $type = 'GET')
    {
        $this->url = $url;
        $this->type = $type;
    }

    /**
     * Calculate the number of seconds to wait before retrying the job.
     *
     * @return int
     */
    public function retryAfter()
    {
        return now()->addSeconds(            
            (int) round(((2 ** $this->attempts()) - 1 ) / 2)
        ); 
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = $this->HttpType();

        if (!$response->successful())
            throw new HttpException($response);

        $this->createResponse($response);
    }

    /**
     * Handle a job failure.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(Exception $e)
    {
        $this->createResponse($e->response);
    }

    public function HttpType()
    {
        if ($this->type == 'GET')
            return Http::get($this->url);

        if ($this->type == 'POST')
            return Http::post($this->url);
    }

    private function createResponse($response)
    {
        ResponseLog::create([
            'url' => $this->url,
            'code' => $response->status(),
            'ok' => $response->ok(),
            'body' => $response->body()
        ]);
    }
}
