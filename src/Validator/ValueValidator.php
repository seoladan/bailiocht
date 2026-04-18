<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Validator;

use Seoladan\Bailiocht\Exception\ConfigurationException;
use Seoladan\Bailiocht\Exception\ValidationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\ValueRule;
use Seoladan\Bailiocht\Validator\Exception\InvalidValueException;
use Seoladan\Bailiocht\Validator\Exception\ValidatorConfigurationException;

class ValueValidator
{

    /**
     * @param Value $value
     * @param ValueRule[] $rules
     * @return Value
     * @throws InvalidValueException
     * @throws ValidatorConfigurationException
     * @template Value of mixed
     */
    public function validateValueAgainst(mixed $value, array $rules = []): mixed
    {
        /**
         * @var (ValueRuleException&ConfigurationException)[] $configExceptions
         * @var (ValueRuleException&ValidationException)[] $validationExceptions
         */
        $configExceptions = [];
        $validationExceptions = [];

        foreach ($rules as $rule) {
            try {
                $rule->validateValue($value);
            } catch (ValueRuleConfigurationException $e) {
                $configExceptions[] = $e;
            } catch (ValueRuleValidationException $e) {
                $validationExceptions[] = $e;
            }
        }

        if ($configExceptions) {
            throw new ValidatorConfigurationException($configExceptions);
        }

        if ($validationExceptions) {
            throw new InvalidValueException($validationExceptions);
        }

        return $value;
    }
}
