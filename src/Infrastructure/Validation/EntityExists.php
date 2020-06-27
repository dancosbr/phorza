<?php

namespace Phorza\Infrastructure\Validation;

use Phalcon\Messages\Message;
use Phalcon\Validation;
use Phalcon\Validation\AbstractValidator;

class EntityExists extends AbstractValidator
{
    const NOT_FOUND = 'Resource not found with %s %s';

    /**
     * @param Validation $validator
     * @param mixed $attribute
     */
    public function validate(Validation $validator, $attribute): bool
    {
        /** @var \Phalcon\Mvc\Model $model */
        $model = $this->getOption('model');
        $field = $this->getOption('key', 'id');
        $value = $validator->getValue($attribute);

        $result = $model->findFirst([
            'conditions' => sprintf('%1$s = :%1$s:', $field),
            'bind' => [
                $field => $value
            ],
        ]);

        if (null === $result) {
            $message = new Message(sprintf(self::NOT_FOUND, $attribute, $value), $attribute, 'Uuid');
            $validator->appendMessage($message);
            return false;
        }

        return true;
    }
}
