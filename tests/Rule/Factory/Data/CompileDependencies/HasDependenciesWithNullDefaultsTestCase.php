<?php

namespace Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies;

use Seoladan\Bailiocht\Rule\Metadata\HasDefault;
use Seoladan\Bailiocht\Rule\Metadata\OptionalDependency;
use Seoladan\Bailiocht\Rule\Metadata\RequiredDependency;
use Seoladan\Bailiocht\Rule\ValidationRule;

class HasDependenciesWithNullDefaultsTestCase implements ValidationRule
{

    public function __construct(
        protected string $name,
        protected ?TestCaseEnum $enum = null,
        #[RequiredDependency('test-case-3')]
        protected ?TestCaseClass $requiredWithNullDefault = null,
        #[OptionalDependency('test-case-4')]
        protected ?TestCaseClass $optionalWithNullDefault = null,
        #[HasDefault]
        protected ?TestCaseClass $hasDefault = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEnum(): ?TestCaseEnum
    {
        return $this->enum;
    }

    public function getRequiredWithNullDefault(): ?TestCaseClass
    {
        return $this->requiredWithNullDefault;
    }

    public function getOptionalWithNullDefault(): ?TestCaseClass
    {
        return $this->optionalWithNullDefault;
    }

    public function getHasDefault(): ?TestCaseClass
    {
        return $this->hasDefault;
    }
}
