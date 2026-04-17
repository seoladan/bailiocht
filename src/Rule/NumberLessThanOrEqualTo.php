<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\Metadata\ConfigCannotBeInvalid;

#[Attribute(Attribute::TARGET_PROPERTY)]
#[ConfigCannotBeInvalid]
readonly class NumberLessThanOrEqualTo implements ValueRule
{
    public function __construct(
        private int|float $maxValue
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
                sprintf('Value must be a number less than or equal to %s', $this->maxValue),
            );
        }

        if ($value > $this->maxValue) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be less than or equal to %s', $this->maxValue),
            );
        }
    }
}
