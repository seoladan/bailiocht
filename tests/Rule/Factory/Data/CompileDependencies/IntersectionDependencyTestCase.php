<?php

namespace Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies;

use JsonSerializable;
use Seoladan\Bailiocht\Rule\ValidationRule;
use Seoladan\Riail\Metadata\OptionalDependency;
use Seoladan\Riail\Metadata\RequiredDependency;

class IntersectionDependencyTestCase implements ValidationRule
{

    public function __construct(
        protected string $name,
        #[RequiredDependency]
        protected TestCaseClass&JsonSerializable $required,
        #[OptionalDependency('test-case-1')]
        protected ?TestCaseClass $optional,
        protected ?TestCaseClass $hasDefault = null,
    ) {
    }
}
