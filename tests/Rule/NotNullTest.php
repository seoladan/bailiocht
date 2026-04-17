<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\NotNull;

#[Group('validator-rules')]
#[CoversClass(NotNull::class)]
#[UsesClass(ValueRuleValidationException::class)]
class NotNullTest extends ValueRuleTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [],
                '',
            ],
            [
                [],
                0,
            ],
            [
                [],
                'string',
            ],
            [
                [],
                false,
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [],
                null,
            ],
        ];
    }
}
