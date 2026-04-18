<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Validator\Exception;

use Throwable;

class InvalidObjectException extends ValidatorValidationException {

    /**
     * @param object $object
     * @param PropertyException[] $propertyExceptions
     * @param Throwable|null $previous
     */
    public function __construct(
        protected object $object,
        protected readonly array $propertyExceptions,
        ?Throwable $previous = null
    ) {
        $invalidCount = count($this->getInvalidProperties());

        parent::__construct(sprintf(
            "%s %s %s invalid:\n%s",
            $invalidCount,
            $object::class,
            $invalidCount === 1 ? 'property is' : 'properties are',
            $this->compileMessages(),
        ), previous: $previous);
    }

    private function compileMessages(): string
    {
        return implode("\n", array_map(
            static function (PropertyException $reason): string {
                return sprintf('- %s: %s', $reason->getProperty(), str_replace("\n", "\n  ", $reason->getMessage()));
            },
            $this->propertyExceptions
        ));
    }

    public function getObject(): object
    {
        return $this->object;
    }

    /**
     * @return string[]
     */
    public function getInvalidProperties(): array
    {
        return array_unique(array_map(
            static fn(PropertyException $exception): string => $exception->getProperty(),
            $this->propertyExceptions,
        ));
    }

    /**
     * @return PropertyException[]
     */
    public function getPropertyExceptions(): array
    {
        return $this->propertyExceptions;
    }

    public function getValidationRuleExceptions(): array
    {
        return [...array_map(
        static fn(InvalidPropertyException $exception): array => $exception->getValidationRuleExceptions(),
        array_filter(
            $this->propertyExceptions,
            static fn(PropertyException $exception): bool => $exception instanceof InvalidPropertyException
        ))];
    }
}
