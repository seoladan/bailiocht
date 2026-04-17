<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Exception;

use DomainException;
use Seoladan\Bailiocht\Exception\ValidationException;
use Seoladan\Bailiocht\Rule\ValidationRule;
use Seoladan\Bailiocht\Rule\ValueRule;
use Throwable;

/**
 * @template Validator of ValueRule
 * @implements ValidationRule<ValueRule>
 */
class ValueRuleValidationException extends DomainException implements ValueRuleException, ValidationException
{
    /**
     * @param Validator $validator
     * @param string $message
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly ValueRule $validator,
        string $message = "",
        ?Throwable $previous = null,
    ) {
        parent::__construct($message ?: 'Value does not pass validation', previous: $previous);
    }

    /**
     * @return Validator
     */
    public function getValidator(): ValueRule
    {
        return $this->validator;
    }
}
