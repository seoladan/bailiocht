<?php

namespace Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies;

use Seoladan\Bailiocht\Rule\ValidationRule;

class TestCaseClass implements ValidationRule
{
    public function __construct(
        private string $id,
    ) {}

    public function getId(): string
    {
        return $this->id;
    }
}
