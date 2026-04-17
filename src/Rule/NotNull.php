<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotNull implements ValueRule
{
    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            throw new ValueRuleValidationException($this, 'Value is required');
        }
    }
}
