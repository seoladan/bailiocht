<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Factory\Exception;

use ReflectionParameter;
use RuntimeException;
use Seoladan\Riail\Metadata\Dependency;
use Throwable;

class CannotSatisfyRequiredDependencyException extends RuntimeException implements DependencyException {
    public function __construct(
        private readonly ReflectionParameter $parameter,
        private readonly Dependency $dependency,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf(
            'Required dependency "%2$s" for %1$s is not available',
            $this->parameter->getDeclaringClass()->getShortName(),
            $this->parameter->getName()
        ), previous: $previous);
    }

    public function getParameter(): ReflectionParameter
    {
        return $this->parameter;
    }

    public function getDependency(): Dependency
    {
        return $this->dependency;
    }
}
