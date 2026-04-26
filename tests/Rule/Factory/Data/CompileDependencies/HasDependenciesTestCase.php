<?php

namespace Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies;

use Seoladan\Bailiocht\Rule\ValidationRule;
use Seoladan\Riail\Metadata\HasDefault;
use Seoladan\Riail\Metadata\OptionalDependency;
use Seoladan\Riail\Metadata\RequiredDependency;

class HasDependenciesTestCase implements ValidationRule
{

    public function __construct(
        protected string $name,
        #[RequiredDependency]
        protected TestCaseClass $required,
        #[OptionalDependency('test-case-1')]
        protected TestCaseClass|null $optional,
        #[HasDefault]
        protected TestCaseClass|string|null $hasDefault = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRequired(): TestCaseClass
    {
        return $this->required;
    }

    public function getOptional(): ?TestCaseClass
    {
        return $this->optional;
    }

    public function getHasDefault(): string|TestCaseClass|null
    {
        return $this->hasDefault;
    }
}
