<?php

namespace Phorza\Infrastructure\Exception;

use Phalcon\Messages\Message;
use Phalcon\Messages\Messages;

class ValidationException extends BaseException
{
    public int $errorCode = 400;
    public string $errorMessage = 'Validation error';
    public array $errors = [];

    public static function withMessages(Messages $messages): self
    {
        $exception = new self();
        $exception->errors = array_map(function (array $message) {
            unset($message['type']);
            unset($message['code']);
            unset($message['metaData']);

            return $message;
        }, $messages->jsonSerialize());

        return $exception;
    }
}
