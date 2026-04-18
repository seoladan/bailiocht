<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Validator\Exception;

use Seoladan\Bailiocht\Exception\ValidationException;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleException;
use Throwable;

class InvalidValueException extends ValidatorValidationException {

    /**
     * @param (ValueRuleException&ValidationException)[] $valueRuleExceptions
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly array $valueRuleExceptions,
        ?Throwable $previous = null
    ) {
        parent::__construct(sprintf("Value is invalid:\n%s", $this->compileMessages()), previous: $previous);
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
}
