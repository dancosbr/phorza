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
            } catch (EventNotRegisteredError $exception) {
                var_dump($exception);
                die;
            } catch (\Exception $exception) {
                var_dump($exception);
                die;
            }
        }
    }

    // public function dispatch(DomainEvent $event): void
    // {
    //     try {
    //         $this->bus->dispatch($event);
    //     } catch (\Prooph\ServiceBus\Exception\EventDispatchException $exception) {
    //         if ($exception->getPrevious() && $exception->getPrevious() instanceof \Exception) {
    //             if (strpos($exception->getPrevious()->getMessage(), 'EventBus was not able to identify a EventHandler for event') === 0) {
    //                 throw EventNotRegisteredError::withEvent($event);
    //             } else {
    //                 throw $exception->getPrevious();
    //             }
    //         }
    //         throw $exception;
    //     } catch (\Exception $exception) {
    //         throw $exception;
    //     }
    // }
}
