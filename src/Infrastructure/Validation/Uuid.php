<?php

namespace Phorza\Infrastructure\Validation;

use Phalcon\Messages\Message;
use Phalcon\Validation;
use Phalcon\Validation\AbstractValidator;
use Phorza\Domain\ValueObject\Uuid as UuidVO;

class Uuid extends AbstractValidator
{
    const INVALID_TYPE = 'Field %s has invalid type %s';
    const NOT_ALLOWED = 'Field %s does not allow the value %s';

    /**
     * @param Validation $validator
     * @param mixed $attribute
     */
    public function validate(Validation $validator, $attribute): bool
    {
        $value = $validator->getValue($attribute);
        if (!is_string($value)) {
            $message = new Message(sprintf(self::INVALID_TYPE, $attribute, gettype($value)), $attribute, 'Uuid');
            $validator->appendMessage($message);
            return false;
        }

        try {
            new UuidVO($value);
        } catch (\InvalidArgumentException $exception) {
            $message = $this->getOption('message');
            if (!$message) {
                $message = new Message(sprintf(self::NOT_ALLOWED, $attribute, $value), $attribute, 'Uuid');
            }
            $validator->appendMessage($message);

            return false;
        }

        return true;
    }
}
