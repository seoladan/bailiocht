<?php

namespace Seoladan\Bailiocht\Rule\Factory\Exception;

use DomainException;
use ReflectionParameter;
use Throwable;

abstract class DependenciesException extends DomainException implements FactoryException {
    public function __construct(
        private readonly ReflectionParameter $parameter,
        string $message,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, previous: $previous);
    }

    public function getParameter(): ReflectionParameter
    {
        return $this->parameter;
    }
}
