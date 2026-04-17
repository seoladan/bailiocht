<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class IsInList implements ValueRule
{
    use CompileArrayValuesTrait;

    public function __construct(
        private array $allowedValues,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (!$this->allowedValues) {
            throw new ValueRuleConfigurationException($this, 'No allowed values provided');
        }

        if (!in_array($value, $this->allowedValues, true)) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must be %s', $this->compileValues($this->allowedValues)),
            );
        }
    }
}
