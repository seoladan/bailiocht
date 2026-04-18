<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Validator\Exception;

use Seoladan\Bailiocht\Exception\Exception;
use Seoladan\Bailiocht\Rule\Exception\ValidationRuleException;

interface ValidatorException extends Exception {

    /**
     * @return ValidationRuleException[]
     */
    public function getValidationRuleExceptions(): array;
}
