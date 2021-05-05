<?php

namespace App\Exceptions;

use Exception;

class NoApiResponseException extends Exception
{
    public function report(){
        Log::debug("No response from API check your api connection");
    }
}
