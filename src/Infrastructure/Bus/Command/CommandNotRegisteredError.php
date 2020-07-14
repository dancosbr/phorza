<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Bus\Command;

use Phorza\Domain\Bus\Command\Command;
use Phorza\Infrastructure\Exception\BaseException;

final class CommandNotRegisteredError extends BaseException
{
    public int $errorCode = 500;
    public string $errorMessage = 'Command not registered on command bus';

    public static function withCommand(Command $command): self
    {
        return new self(sprintf('The command %s has not a command handler associated', get_class($command)));
    }
}
