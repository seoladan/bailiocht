<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class StringLengthBetween implements ValueRule
{
    public function __construct(
        private int $minLength,
        private int $maxLength,
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

        if ($this->maxLength < 0) {
            throw new ValueRuleConfigurationException($this, 'Maximum length must be greater than zero');
        }

        if ($this->minLength > $this->maxLength) {
            throw new ValueRuleConfigurationException($this, 'Maximum length must be greater than or equal to minimum length');
        }

        if (!is_string($value)) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be a string between %s and %s characters in length', $this->minLength, $this->maxLength),
            );
        }

        if (strlen($value) < $this->minLength || strlen($value) > $this->maxLength) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be between %s and %s characters in length', $this->minLength, $this->maxLength),
            );
        }
    }
}
