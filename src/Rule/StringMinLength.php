<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringMinLength implements ValueRule
{
    public function __construct(
        private int $minLength
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if ($this->minLength < 0) {
            throw new ValueRuleConfigurationException($this, 'Minimum length must be greater than zero');
        }

        if (!is_string($value)) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be a string of %s or more characters in length', $this->minLength),
            );
        }

        if (strlen($value) < $this->minLength) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be %s or more characters in length', $this->minLength),
            );
        }
    }
}
