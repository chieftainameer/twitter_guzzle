<?php

namespace App\Exceptions;

use Exception;

class DataInsertionFailedException extends Exception
{
    public function report(){
        Log::debug("Data could not be inserted");
    }
}
