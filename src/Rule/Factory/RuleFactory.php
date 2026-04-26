<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Factory;

use Psr\Container\ContainerInterface;
use Seoladan\Bailiocht\Rule\Factory\Exception\CannotSatisfyRequiredDependencyException;
use Seoladan\Bailiocht\Rule\Factory\Exception\ClassDoesNotExistException;
use Seoladan\Bailiocht\Rule\Factory\Exception\ClassDoesNotImplementValidationRule;
use Seoladan\Bailiocht\Rule\Factory\Exception\CreateRuleException;
use Seoladan\Bailiocht\Rule\Factory\Exception\UnspecifiedDependencyException;
use Seoladan\Bailiocht\Rule\Factory\Exception\UnsupportedDependencyTypeException;
use Seoladan\Bailiocht\Rule\ValidationRule;
use Seoladan\Riail\Factory\Exception\CannotSatisfyRequiredDependencyException as GenericCannotSatisfyRequiredDependencyException;
use Seoladan\Riail\Factory\Exception\ClassDoesNotExistException as GenericClassDoesNotExistException;
use Seoladan\Riail\Factory\Exception\ClassDoesNotImplementRuleType;
use Seoladan\Riail\Factory\Exception\CreateRuleException as GenericCreateRuleException;
use Seoladan\Riail\Factory\Exception\UnspecifiedDependencyException as GenericUnspecifiedDependencyException;
use Seoladan\Riail\Factory\Exception\UnsupportedDependencyTypeException as GenericUnsupportedDependencyTypeException;
use Seoladan\Riail\Factory\RuleFactory as GenericRuleFactory;
use Seoladan\Riail\Rule;

/**
 * @extends GenericRuleFactory<ValidationRule>
 */
class RuleFactory extends GenericRuleFactory
{
    public function __construct(
        ?ContainerInterface $container = null,
    ) {
        parent::__construct(ValidationRule::class, $container);
    }

    public function createRule(string $rule, ...$ruleArgs): Rule
    {
        try {
            return parent::createRule($rule, ...$ruleArgs);
        } catch (GenericCannotSatisfyRequiredDependencyException $exception) {
            throw new CannotSatisfyRequiredDependencyException(
                $exception->getParameter(),
                $exception->getDependency(),
                $exception
            );
        } catch (GenericClassDoesNotExistException $exception) {
            throw new ClassDoesNotExistException($exception->getMessage(), previous: $exception);
        } catch (ClassDoesNotImplementRuleType $exception) {
            throw new ClassDoesNotImplementValidationRule($exception->getMessage(), previous: $exception);
        } catch (GenericCreateRuleException $exception) {
            throw new CreateRuleException($exception->getMessage(), previous: $exception);
        } catch (GenericUnsupportedDependencyTypeException $exception) {
            throw new UnsupportedDependencyTypeException($exception->getParameter(), $exception);
        } catch (GenericUnspecifiedDependencyException $exception) {
            throw new UnspecifiedDependencyException($exception->getParameter(), $exception);
        }
    }
}
