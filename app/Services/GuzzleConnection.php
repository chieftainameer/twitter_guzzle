<?php
namespace App\Services;

use GuzzleHttp\Client;

class GuzzleConnection{
    
    public function conexion(){
        return app('GuzzleClient')(['headers' => ['Authorization' => config('app.twitter_bearer_token')]]);;
    }
}