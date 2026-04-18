<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Factory\Exception;

use ReflectionIntersectionType;
use ReflectionParameter;
use ReflectionUnionType;
use Throwable;

class UnsupportedDependencyTypeException extends CompileDependenciesException
{
    public function __construct(
        ReflectionParameter $parameter,
        ?Throwable $previous = null
    ) {
        parent::__construct($parameter, sprintf(
            '%3$s type for %1$s parameter "%2$s" is unsupported',
            $parameter->getDeclaringClass()->getShortName(),
            $parameter->getName(),
            match ($parameter->getType()::class) {
                ReflectionUnionType::class => 'Union',
                ReflectionIntersectionType::class => 'Intersection',
                default => '"'.$parameter->getType().'"',
            },
        ), previous: $previous);
    }
}
