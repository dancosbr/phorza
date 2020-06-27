<?php

namespace Phorza\Infrastructure\Exception;

use Exception;

abstract class BaseException extends Exception
{
    public function __construct($message = '', $code = 0)
    {
        if (empty($message) && property_exists($this, 'errorMessage')) {
            $message = $this->errorMessage;
        }

        if (empty($code) && property_exists($this, 'errorCode')) {
            $code = $this->errorCode;
        }

        parent::__construct($message, $code);
    }
}
