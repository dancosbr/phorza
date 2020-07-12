<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Bus\Event;

use Phorza\Domain\Bus\Event\DomainEvent;
use Phorza\Domain\Bus\Event\EventBus;
use Prooph\ServiceBus\Plugin\Router\EventRouter;
use Prooph\ServiceBus\EventBus as ProophEventBus;

final class InMemoryProophEventBus implements EventBus
{
    private ProophEventBus $bus;

    public function __construct(EventRouter $eventRouter)
    {
        $this->bus = new ProophEventBus();
        $eventRouter->attachToMessageBus($this->bus);
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            try {
                $this->bus->dispatch($event);
            } catch (\Prooph\ServiceBus\Exception\EventListenerException $exception) {
                if ($exception->getPrevious() && $exception->getPrevious() instanceof \Exception) {
                    if (strpos($exception->getPrevious()->getMessage(), 'At least one event listener caused an exception. Check listener exceptions for details') === 0) {
                        throw EventHandlerError::withEvent($event, $exception->listenerExceptions());
                    } else {
                        throw $exception->getPrevious();
                    }
                }
                throw $exception;
            } catch (\Exception $exception) {
                throw $exception;
            }
        }
    }
}
