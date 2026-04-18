<?php

namespace Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies;

use Seoladan\Bailiocht\Rule\Metadata\HasDefault;
use Seoladan\Bailiocht\Rule\Metadata\OptionalDependency;
use Seoladan\Bailiocht\Rule\Metadata\RequiredDependency;
use Seoladan\Bailiocht\Rule\ValidationRule;

class HasDependenciesWithDefaultsTestCase implements ValidationRule
{

    public function __construct(
        protected string $name,
        protected ?TestCaseEnum $enum = null,
        #[RequiredDependency('test-case-1')]
        protected ?TestCaseClass $requiredWithDefault = new TestCaseClass('default-1'),
        #[OptionalDependency('test-case-2')]
        protected ?TestCaseClass $optionalWithDefault = new TestCaseClass('default-2'),
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

    public function getRequiredWithDefault(): ?TestCaseClass
    {
        return $this->requiredWithDefault;
    }

    public function getOptionalWithDefault(): ?TestCaseClass
    {
        return $this->optionalWithDefault;
    }

    public function getHasDefault(): ?TestCaseClass
    {
        return $this->hasDefault;
    }
}
