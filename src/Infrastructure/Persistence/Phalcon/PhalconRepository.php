<?php

declare(strict_types=1);

namespace Phorza\Infrastructure\Persistence\Phalcon;

use Phalcon\Mvc\Model;
use Phorza\Domain\Error\CreateException;
use Phorza\Domain\Error\DeleteException;
use Phorza\Domain\Error\UpdateException;

abstract class PhalconRepository
{
    public function create(Model $model): void
    {
        $result = $model->create();

        if (false === $result) {
            $messages = $model->getMessages();
            throw new CreateException(trim('Could not create. ' . implode('. ', $messages)));
        }
    }

    public function update(Model $model): void
    {
        $result = $model->update();

        if (false === $result) {
            $messages = $model->getMessages();
            throw new UpdateException(trim('Could not update. ' . implode('. ', $messages)));
        }
    }

    public function delete(Model $model): void
    {
        $result = $model->delete();

        if (false === $result) {
            $messages = $model->getMessages();
            throw new DeleteException(trim('Could not delete. ' . implode('. ', $messages)));
        }
    }
}
