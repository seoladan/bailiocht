<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;

/**
 * @implements ValidationRule<ValueRule>
 */
interface ValueRule extends ValidationRule
{
    /**
     * @param mixed $value
     * @return void
     * @throws ValueRuleConfigurationException
     * @throws ValueRuleValidationException
     */
    public function validateValue(mixed $value): void;
}
