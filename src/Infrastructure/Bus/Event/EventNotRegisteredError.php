<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Bus\Event;

use Phorza\Domain\Bus\Event\DomainEvent;
use Phorza\Infrastructure\Exception\BaseException;

final class EventNotRegisteredError extends BaseException
{
    public int $errorCode = 500;
    public string $errorMessage = 'Event bus error';

    public static function withEvent(DomainEvent $event): self
    {
        return new self(sprintf('The event %s has not a event handler associated', get_class($event)));
    }
}
