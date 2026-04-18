<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Factory;

use DateInterval;
use DateTimeInterface;
use Error;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionType;
use Seoladan\Bailiocht\Rule\Factory\Exception\CannotSatisfyRequiredDependencyException;
use Seoladan\Bailiocht\Rule\Factory\Exception\ClassDoesNotExistException;
use Seoladan\Bailiocht\Rule\Factory\Exception\ClassDoesNotImplementValidationRule;
use Seoladan\Bailiocht\Rule\Factory\Exception\CreateRuleException;
use Seoladan\Bailiocht\Rule\Factory\Exception\FactoryException;
use Seoladan\Bailiocht\Rule\Factory\Exception\UnspecifiedDependencyException;
use Seoladan\Bailiocht\Rule\Factory\Exception\UnsupportedDependencyTypeException;
use Seoladan\Bailiocht\Rule\Metadata\Dependency;
use Seoladan\Bailiocht\Rule\Metadata\HasDefault;
use Seoladan\Bailiocht\Rule\ValidationRule;
use Seoladan\Bailiocht\Utility\GetAttributeTrait;
use UnitEnum;

class RuleFactory
{
    use GetAttributeTrait;

    /**
     * @var array<class-string<ValidationRule>, ReflectionClass>
     */
    private array $ruleReflectionCache = [];

    /**
     * @var array<class-string<ValidationRule>, array<string, Dependency>>
     */
    private array $ruleDependencyMap = [];

    /**
     * @var array<class-string<ValidationRule>, array<string, Object>>
     */
    private array $ruleDependencyCache = [];

    public function __construct(
        protected ?ContainerInterface $container = null,
    ) {}

    /**
     * @param class-string<Rule> $rule
     * @param $ruleArgs
     * @return Rule
     * @throws FactoryException
     * @template Rule of ValidationRule
     */
    public function createRule(string $rule, ...$ruleArgs): ValidationRule
    {
        try {
            $this->getRule($rule);

            return new $rule(...$ruleArgs, ...$this->getDependencyArgsForRule($rule));
        } catch (Error $e) {
            throw new CreateRuleException(
                sprintf('Unable to create %s rule: %s', $rule, $e->getMessage()),
                previous: $e,
            );
        }
    }

    /**
     * @param class-string<ValidationRule> $rule
     * @return ReflectionClass<ValidationRule>
     * @throws FactoryException
     */
    protected function getRule(string $rule): ReflectionClass
    {
        if (!isset($this->ruleReflectionCache[$rule])) {
            try {
                $reflection = new ReflectionClass($rule);

                if (!$reflection->implementsInterface(ValidationRule::class)) {
                    throw new ClassDoesNotImplementValidationRule(
                        sprintf('"%s" is not a validation rule', $reflection->getShortName())
                    );
                }

                $this->ruleReflectionCache[$rule] = $reflection;
            } catch (ReflectionException $e) {
                throw new ClassDoesNotExistException(
                    sprintf('Rule "%s" does not exist', $rule),
                    previous: $e,
                );
            }
        }

        return $this->ruleReflectionCache[$rule];
    }

    /**
     * @param class-string<ValidationRule> $rule
     * @return array<string, Object>
     *      Map of {@see ValidationRule} constructor parameter names to an instance of the appropriate class
     * @throws FactoryException
     */
    protected function getDependencyArgsForRule(string $rule): array
    {
        if (!array_key_exists($rule, $this->ruleDependencyCache)) {
            $this->ruleDependencyCache[$rule] = $this->fetchDependenciesForRule($rule);
        }

        return $this->ruleDependencyCache[$rule];
    }

    /**
     * @param class-string<ValidationRule> $rule
     * @return array<string, Object>
     *       Map of {@see ValidationRule} constructor parameter names to an instance of the appropriate class
     * @throws FactoryException
     */
    protected function fetchDependenciesForRule(string $rule): array
    {
        $dependencies = $this->getDependenciesForRule($rule);
        $args = [];

        foreach ($dependencies as $name => $dependency) {
            if ($this->container) {
                try {
                    $args[$name] = $this->container->get($dependency->getIdentifier() ?? $dependency->getClass());
                    continue;
                } catch (ContainerExceptionInterface $e) {
                    if ($dependency->isRequired()) {
                        throw new CannotSatisfyRequiredDependencyException(
                            $this->getParameterForRule($rule, $name),
                            $dependency,
                            $e
                        );
                    }
                }
            } elseif ($dependency->isRequired()) {
                throw new CannotSatisfyRequiredDependencyException(
                    $this->getParameterForRule($rule, $name),
                    $dependency,
                );
            }

            if (!$this->getParameterForRule($rule, $name)->isDefaultValueAvailable()) {
                $args[$name] = null;
            }
        }

        return $args;
    }

    protected function getParameterForRule(string $rule, string $param): ReflectionParameter
    {
        return array_values(array_filter(
            $this->getRule($rule)->getConstructor()->getParameters(),
            static fn (ReflectionParameter $reflect) => $reflect->getName() === $param
        ))[0];
    }

    /**
     * @param class-string<ValidationRule> $rule
     * @return array<string, Dependency>
     * @throws FactoryException
     */
    protected function getDependenciesForRule(string $rule): array
    {
        if (!array_key_exists($rule, $this->ruleDependencyMap)) {
            $this->ruleDependencyMap[$rule] = $this->compileDependenciesForRule($this->ruleReflectionCache[$rule]);
        }

        return $this->ruleDependencyMap[$rule];
    }

    /**
     * @param ReflectionClass<ValidationRule> $reflect
     * @return array<string, Dependency>
     *      A map of {@see ValidationRule} constructor parameter names to the corresponding {@see Dependency}
     * @throws FactoryException
     */
    protected function compileDependenciesForRule(ReflectionClass $reflect): array
    {
        if (!$reflect->getConstructor()) {
            return [];
        }

        /**
         * @var array<string, Dependency> $dependencies
         */
        $dependencies = [];

        foreach ($reflect->getConstructor()->getParameters() as $parameter) {
            $type = $parameter->getType();

            if (!$type || $this->ignoreType($type) || $this->getAttribute($parameter, HasDefault::class)) {
                continue;
            }

            if ($type instanceof ReflectionNamedType) {
                $dependency = $this->getAttribute($parameter, Dependency::class, true);

                if (!$dependency) {
                    throw new UnspecifiedDependencyException($parameter);
                }

                $dependencies[$parameter->getName()] = $dependency->setClass($type->getName());
            } else {
                throw new UnsupportedDependencyTypeException($parameter);
            }
        }

        return $dependencies;
    }

    protected function ignoreType(ReflectionType $type): bool
    {
        if ($type instanceof ReflectionNamedType) {
            return $type->isBuiltin()
                || is_a($type->getName(), UnitEnum::class, true)
                || is_a($type->getName(), DateTimeInterface::class, true)
                || is_a($type->getName(), DateInterval::class, true);
        }

        foreach ($type->getTypes() as $subtype) {
            if (!$this->ignoreType($subtype)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param ReflectionAttribute $attribute
     * @return ValidationRule
     * @throws FactoryException
     */
    public function createRuleFromAttribute(ReflectionAttribute $attribute): ValidationRule
    {
        return $this->createRule($attribute->getName(), ...$attribute->getArguments());
    }
}
