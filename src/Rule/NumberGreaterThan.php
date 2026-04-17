<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\Metadata\ConfigCannotBeInvalid;

#[Attribute(Attribute::TARGET_PROPERTY)]
#[ConfigCannotBeInvalid]
readonly class NumberGreaterThan implements ValueRule
{
    public function __construct(
        private int|float $minValue,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (!is_numeric($value)) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be a number greater than %s', $this->minValue),
            );
        }

        if ($value <= $this->minValue) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be greater than %s', $this->minValue),
            );
        }
    }
}
