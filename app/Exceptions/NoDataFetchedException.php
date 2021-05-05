<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class NoDataFetchedException extends Exception
{
    public function report(){
        Log::debug("No data found....its empty");
    }
}
