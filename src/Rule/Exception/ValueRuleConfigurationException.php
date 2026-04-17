<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Exception;

use DomainException;
use Seoladan\Bailiocht\Exception\ConfigurationException;
use Seoladan\Bailiocht\Rule\ValueRule;
use Throwable;

/**
 * @template Validator of ValueRule
 */
class ValueRuleConfigurationException extends DomainException implements ValueRuleException, ConfigurationException
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
        parent::__construct($message ?: 'Validator has invalid configuration', previous: $previous);
    }

    /**
     * @return Validator
     */
    public function getValidator(): ValueRule
    {
        return $this->validator;
    }
}
