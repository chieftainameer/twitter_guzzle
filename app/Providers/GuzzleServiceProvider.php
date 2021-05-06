<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use App\Services\GuzzleConnection;
use Illuminate\Support\Facades\Schema;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;


class GuzzleServiceProvider extends ServiceProvider
{

    private $logger;
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind(GuzzleConnection::class,function(){
        //     return new GuzzleConnection;
        // });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('GuzzleClient',function(){
            $messageFormats = [
                'REQUEST:',
                'METHOD:{method}',
                'URL:{uri}',
                'HEADERS:{req_headers}',
                'Payload:{req_body}',
                'RESPONSE:',
                'STATUS:{code}',
                'BODY:{res_body}'
            ];

            $stack = $this->setLoggingHandler($messageFormats);

            return function ($config) use ($stack) {
                return new Client(array_merge($config,['handler' => $stack]));
            };
        });
    }

    private function get_logger(){
        if(! $this->logger){
            $this->logger = with(new Logger('guzzle-log'))
            ->pushHandler(new RotatingFileHandler(\storage_path('logs/guzzle-log.log')));
        }
        return $this->logger;
    }

    private function setGuzzleMiddleware(string $messageFormat){
        return Middleware::log($this->get_logger(),new MessageFormatter($messageFormat));
    }

    private function setLoggingHandler(array $messageFormats){
        $stack = HandlerStack::create();

        collect($messageFormats)->each(function($messageFormat) use ($stack){
            $stack->unshift($this->setGuzzleMiddleware($messageFormat));
        });

        return $stack;
    }
}
