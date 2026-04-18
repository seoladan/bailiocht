<?php

namespace Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies;

use DateInterval;
use DateTimeInterface;
use Seoladan\Bailiocht\Rule\ValidationRule;

class IgnoredTypesTestCase implements ValidationRule
{

    protected ?string $nullableStringProperty;

    public function __construct(
        protected int $intProperty,
        protected string $stringProperty,
        null|string $nullableStringProperty,
        protected TestCaseEnum $enumProperty,
        protected DateTimeInterface $dateTimeProperty,
        protected DateInterval $dateIntervalProperty,
        protected DateTimeInterface|DateInterval $multiTypeProperty,
        protected int $defaultIntProperty = 1,
    ) {
        $this->nullableStringProperty = $nullableStringProperty;
    }
}
