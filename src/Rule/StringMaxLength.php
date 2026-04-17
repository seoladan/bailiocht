<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringMaxLength implements ValueRule
{
    public function __construct(
        private int $maxLength
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if ($this->maxLength < 0) {
            throw new ValueRuleConfigurationException($this, 'Minimum length must be greater than zero');
        }

        if (!is_string($value)) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be a string of %s or fewer characters in length', $this->maxLength),
            );
        }

        if (strlen($value) > $this->maxLength) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be %s or fewer characters in length', $this->maxLength),
            );
        }
    }
}
