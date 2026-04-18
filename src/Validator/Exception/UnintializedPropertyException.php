<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Validator\Exception;

use DomainException;

class UnintializedPropertyException extends DomainException implements PropertyException
{
    public function __construct(
        private readonly object $object,
        private readonly string $property,
        ?string $message = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            $message ?? sprintf('Value is required for property "%s"', $property),
            previous: $previous
        );
    }

    public function getObject(): object
    {
        return $this->object;
    }

    public function getProperty(): string
    {
        return $this->property;
    }
}
