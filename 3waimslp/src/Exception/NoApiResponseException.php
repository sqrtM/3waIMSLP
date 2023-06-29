<?php

declare(strict_types=1);

namespace App\Exception;

use Exception;
use Throwable;

/**
 * IMSLP Api was called, but did not return a valid response.
 */
class NoApiResponseException extends Exception
{
    public function __construct($message = "IMSLP API did not return a valid response.", $code = 503)
    {
        parent::__construct($message, $code);
    }

    // custom string representation of object
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
