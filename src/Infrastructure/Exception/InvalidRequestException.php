<?php

namespace Phorza\Infrastructure\Exception;

class InvalidRequestException extends BaseException
{
    public int $errorCode = 400;
    public string $errorMessage = 'Invalid request';
}
