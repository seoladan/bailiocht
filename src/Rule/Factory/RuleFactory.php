<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Factory;

use ReflectionAttribute;
use Seoladan\Bailiocht\Rule\ValueRule;


/**
 * @template Rule of ValueRule
 */
class RuleFactory
{
    /**
     * @param class-string<Rule> $rule
     * @param array $ruleArgs
     * @return Rule
     */
    public function createRule(string $rule, array $ruleArgs = []): ValueRule
    {
        return new $rule(...$ruleArgs);
    }

    /**
     * @param ReflectionAttribute $attribute
     * @return ValueRule
     */
    public function createRuleFromAttribute(ReflectionAttribute $attribute): ValueRule
    {
        return $this->createRule($attribute->getName(), $attribute->getArguments());
    }
}
