<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Bus\Command;

use Phorza\Domain\Bus\Command\Command;
use Phorza\Domain\Bus\Command\CommandBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Prooph\ServiceBus\CommandBus as ProophCommandBus;

final class InMemoryProophCommandBus implements CommandBus
{
    private ProophCommandBus $bus;

    public function __construct(CommandRouter $commandRouter)
    {
        $this->bus = new ProophCommandBus();
        $commandRouter->attachToMessageBus($this->bus);
    }

    public function dispatch(Command $command): void
    {
        try {
            $this->bus->dispatch($command);
        } catch (\Prooph\ServiceBus\Exception\CommandDispatchException $exception) {
            if ($exception->getPrevious() && $exception->getPrevious() instanceof \Exception) {
                if (strpos($exception->getPrevious()->getMessage(), 'CommandBus was not able to identify a CommandHandler for command') === 0) {
                    throw CommandNotRegisteredError::withCommand($command);
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
