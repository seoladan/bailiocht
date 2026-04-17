<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NumberBetween implements ValueRule
{
    private int|float $minValue;
    private int|float $maxValue;

    public function __construct(
        int|float $minValue,
        int|float $maxValue,
        private bool $inclusive = true,
    ) {
        $this->minValue = min($minValue, $maxValue);
        $this->maxValue = max($minValue, $maxValue);
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if ($this->minValue == $this->maxValue) {
            throw new ValueRuleConfigurationException($this, 'Minimum must be greater than the maximum value');
        }

        if (!is_numeric($value)) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be a number between %s and %s', $this->minValue, $this->maxValue),
            );
        }

        if ($this->inclusive) {
            if ($value < $this->minValue || $value > $this->maxValue) {
                throw new ValueRuleValidationException(
                    $this,
                    sprintf('Value must be between %s and %s', $this->minValue, $this->maxValue),
                );
            }
        } elseif ($value <= $this->minValue || $value >= $this->maxValue) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be between %s and %s', $this->minValue, $this->maxValue),
            );
        }
    }
}
