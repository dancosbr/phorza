<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Bus\Event;

use Phorza\Domain\Bus\Event\DomainEvent;
use Phorza\Infrastructure\Exception\BaseException;

final class EventHandlerError extends BaseException
{
    public int $errorCode = 500;
    public string $errorMessage = 'Event bus error';

    public static function withEvent(DomainEvent $event, array $exceptions = []): self
    {
        $exceptionMessages = [];
        /** @var \Exception $exception */
        foreach ($exceptions as $exception) {
            $exceptionMessages[] = $exception->getMessage();
        }

        return new self(sprintf('The event %s handlers produced some exception(s): ' . implode('. ', $exceptionMessages)));
    }
}
