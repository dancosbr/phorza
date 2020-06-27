<?php

namespace Phorza\Infrastructure\Exception;

class ModelSaveException extends BaseException
{
    public int $errorCode = 400;
    public string $errorMessage = 'Invalid data';
}
