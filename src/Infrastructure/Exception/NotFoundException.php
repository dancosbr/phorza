<?php

namespace Phorza\Infrastructure\Exception;

class NotFoundException extends BaseException
{
    public int $errorCode = 404;
    public string $errorMessage = 'Resource not found';
}
