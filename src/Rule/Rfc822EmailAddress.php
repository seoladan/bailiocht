<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class Rfc822EmailAddress implements ValueRule
{
    public function __construct()
    {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (filter_var($value, FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE) === null) {
            throw new ValueRuleValidationException($this, 'Value must be a valid email address');
        }
    }
}
