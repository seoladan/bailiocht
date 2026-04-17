<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class IsNotInList implements ValueRule
{
    use CompileArrayValuesTrait;

    public function __construct(
        private array $disallowedValues,
    ) {
    }

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        if (!$this->disallowedValues) {
            throw new ValueRuleConfigurationException($this, 'No disallowed values provided');
        }

        if (in_array($value, $this->disallowedValues)) {
            throw new ValueRuleValidationException(
                $this,
                sprintf('Value must not be %s', $this->compileValues($this->disallowedValues)),
            );
        }
    }
}
