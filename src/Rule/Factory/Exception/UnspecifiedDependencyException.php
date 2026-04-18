<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Factory\Exception;

use ReflectionParameter;
use Throwable;

class UnspecifiedDependencyException extends CompileDependenciesException
{
    public function __construct(
        ReflectionParameter $parameter,
        ?Throwable $previous = null
    ) {
        parent::__construct($parameter, sprintf(
            '%1$s parameter "%2$s" is not marked as a runtime dependency',
            $parameter->getDeclaringClass()->getShortName(),
            $parameter->getName(),
        ), previous: $previous);
    }
}
