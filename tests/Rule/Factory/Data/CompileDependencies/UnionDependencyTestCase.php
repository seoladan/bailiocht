<?php

namespace Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies;

use Seoladan\Bailiocht\Rule\Metadata\OptionalDependency;
use Seoladan\Bailiocht\Rule\Metadata\RequiredDependency;
use Seoladan\Bailiocht\Rule\ValidationRule;

class UnionDependencyTestCase implements ValidationRule
{

    public function __construct(
        protected string $name,
        #[RequiredDependency]
        protected TestCaseClass|int $required,
        #[OptionalDependency('test-case-1')]
        protected ?TestCaseClass $optional,
        protected ?TestCaseClass $hasDefault = null,
    ) {
    }
}
