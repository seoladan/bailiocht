<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringLength implements ValueRule
{
    public function __construct(
        private int $length
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if ($this->length < 0) {
            throw new ValueRuleConfigurationException($this, 'Minimum length must be greater than zero');
        }

        if (!is_string($value)) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be a string exactly %s characters in length', $this->length),
            );
        }

        if (strlen($value) !== $this->length) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be exactly %s characters in length', $this->length),
            );
        }
    }
}
