<?php

namespace Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies;

use Seoladan\Bailiocht\Rule\Metadata\HasDefault;
use Seoladan\Bailiocht\Rule\Metadata\OptionalDependency;
use Seoladan\Bailiocht\Rule\Metadata\RequiredDependency;
use Seoladan\Bailiocht\Rule\ValidationRule;

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
