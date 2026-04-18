<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Validator\Exception;

use DomainException;
use Seoladan\Bailiocht\Exception\ValidationException;
use Seoladan\Bailiocht\Rule\Exception\ValidationRuleException;

abstract class ValidatorValidationException extends DomainException implements ValidatorException, ValidationException {

    /**
     * @return (ValidationRuleException&ValidationException)[]
     */
    abstract public function getValidationRuleExceptions(): array;
}
