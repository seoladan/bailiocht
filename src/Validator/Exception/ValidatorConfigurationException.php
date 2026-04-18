<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Validator\Exception;

use RuntimeException;
use Seoladan\Bailiocht\Exception\ConfigurationException;
use Seoladan\Bailiocht\Rule\Exception\ValidationRuleException;
use Throwable;

class ValidatorConfigurationException extends RuntimeException implements ConfigurationException, ValidatorException {

    /**
     * @param (ValidationRuleException&ConfigurationException)[] $exceptions
     * @param Throwable|null $previous
     */
    public function __construct(
        protected readonly array $exceptions,
        ?Throwable $previous = null
    ) {
        parent::__construct(
            sprintf("Validator configuration is invalid:\n%s", $this->compileMessages()),
            previous: $previous
        );
    }

    private function compileMessages(): string
    {
        return implode("\n", array_map(
            static function (ValidationRuleException $reason): string {
                return sprintf('- %s', str_replace("\n", "\n  ", $reason->getMessage()));
            },
            $this->exceptions
        ));
    }

    /**
     * @return (ValidationRuleException&ConfigurationException)[]
     */
    public function getValidationRuleExceptions(): array {
        return $this->exceptions;
    }
}
