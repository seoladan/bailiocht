<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Validator\Exception;

use Seoladan\Bailiocht\Rule\Exception\ValueRuleException;
use Throwable;

class InvalidPropertyException extends ValidatorValidationException implements PropertyException
{

    /**
     * @param object $object
     * @param string $property
     * @param ValueRuleException[] $valueRuleExceptions
     * @param Throwable|null $previous
     */
    public function __construct(
        protected object $object,
        protected string $property,
        protected readonly array $valueRuleExceptions,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf(
            "Property \"%s\" is invalid:\n%s",
            $this->property,
            $this->compileMessages(),
        ), previous: $previous);
    }

    public function getObject(): object
    {
        return $this->object;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    private function compileMessages(): string
    {
        return implode("\n", array_map(
            static function (ValueRuleException $reason): string {
                return sprintf('- %s', str_replace("\n", "\n  ", $reason->getMessage()));
            },
            $this->valueRuleExceptions
        ));
    }

    public function getValidationRuleExceptions(): array
    {
        return $this->valueRuleExceptions;
    }

    public static function fromValueValidationException(
        InvalidValueException $exception,
        object $object,
        string $property
    ): self {
        throw new self($object, $property, $exception->getValidationRuleExceptions());
    }
}
