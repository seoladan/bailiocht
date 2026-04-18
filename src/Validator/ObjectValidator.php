<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Validator;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;
use Seoladan\Bailiocht\Exception\ConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleException;
use Seoladan\Bailiocht\Rule\Factory\RuleFactory;
use Seoladan\Bailiocht\Rule\NoValidate;
use Seoladan\Bailiocht\Rule\ValueRule;
use Seoladan\Bailiocht\Utility\GetAttributeTrait;
use Seoladan\Bailiocht\Validator\Exception\InvalidObjectException;
use Seoladan\Bailiocht\Validator\Exception\InvalidPropertyException;
use Seoladan\Bailiocht\Validator\Exception\InvalidValueException;
use Seoladan\Bailiocht\Validator\Exception\PropertyException;
use Seoladan\Bailiocht\Validator\Exception\UnintializedPropertyException;
use Seoladan\Bailiocht\Validator\Exception\ValidatorConfigurationException;

class ObjectValidator
{
    use GetAttributeTrait;

    public function __construct(
        protected ValueValidator $valueValidator,
        protected RuleFactory $ruleFactory,
    ) {
    }

    protected function shouldValidate(ReflectionClass|ReflectionProperty $property): bool
    {
        return !$this->getAttribute($property, NoValidate::class);
    }

    /**
     * @param ReflectionProperty $property
     * @param object $forObject
     * @return void
     * @throws InvalidPropertyException
     * @throws ValidatorConfigurationException
     */
    protected function validateProperty(ReflectionProperty $property, object $forObject): void
    {
        if (!$this->shouldValidate($property)) {
            return;
        }

        if (!$property->isInitialized($forObject) && !$property->getType()->allowsNull()) {
            throw new UnintializedPropertyException($forObject, $property->getName());
        }

        try {
            $this->valueValidator->validateValueAgainst(
                $property->getValue($forObject),
                $this->getPropertyValidators($property)
            );
        } catch (InvalidValueException $e) {
            throw InvalidPropertyException::fromValueValidationException($e, $forObject, $property->getName());
        }
    }

    /**
     * @param Object $object
     * @return Object
     * @template Object of object
     * @throws InvalidPropertyException
     * @throws ValidatorConfigurationException
     */
    public function validateObject(object $object): object
    {
        $reflect = new ReflectionObject($object);

        if (!$this->shouldValidate($reflect)) {
            return $object;
        }

        /**
         * @var (ValueRuleException&ConfigurationException)[] $configExceptions
         * @var PropertyException[] $invalidProperties
         */
        $configExceptions = [];
        $invalidProperties = [];

        foreach ($reflect->getProperties() as $property) {
            $this->validateProperty($property, $object);

            try {
                $this->validateProperty($property, $object);
            } catch (ValidatorConfigurationException $e) {
                $configExceptions = [...$configExceptions, ...$e->getValidationRuleExceptions()];
            } catch (PropertyException $e) {
                $invalidProperties[] = $e;
            }
        }

        if ($configExceptions) {
            throw new ValidatorConfigurationException($configExceptions);
        }

        if ($invalidProperties) {
            throw new InvalidObjectException($object, $invalidProperties);
        }

        return $object;
    }

    /**
     * @param ReflectionProperty $property
     * @return ValueRule[]
     */
    protected function getPropertyValidators(ReflectionProperty $property): array
    {
        return array_map(
            $this->ruleFactory->createRuleFromAttribute(...),
            $property->getAttributes(ValueRule::class, ReflectionAttribute::IS_INSTANCEOF)
        );
    }
}
