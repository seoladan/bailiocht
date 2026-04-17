<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class NotMatchRegex implements ValueRule
{
    public function __construct(
        private string $pattern,
        private ?string $message = null,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (!$this->pattern) {
            throw new ValueRuleConfigurationException($this, 'A valid regex pattern is required');
        }

        match (@preg_match($this->pattern, $value)) {
            false => throw new ValueRuleConfigurationException($this, 'A valid regex pattern is required'),
            0 => null,
            default => throw new ValueRuleValidationException(
                $this,
                $this->message ?? sprintf('Value must not match regex pattern "%s"', $this->pattern),
            ),
        };
    }
}
