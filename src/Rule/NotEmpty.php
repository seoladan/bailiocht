<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotEmpty implements ValueRule
{
    use EmptyValueTrait;

    public function validateValue(mixed $value): void
    {
        if ($this->isEmpty($value)) {
            throw new ValueRuleValidationException($this, 'Value must not be empty');
        }
    }
}
