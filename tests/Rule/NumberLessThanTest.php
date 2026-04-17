<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\NumberLessThan;

#[Group('validator-rules')]
#[CoversClass(NumberLessThan::class)]
#[UsesClass(ValueRuleValidationException::class)]
class NumberLessThanTest extends ValueRuleTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [0],
                null,
            ],
            [
                [0],
                -1,
            ],
            [
                [-2],
                -8,
            ],
            [
                [-2.5],
                -3,
            ],
            [
                [3],
                1.5,
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [0],
                0,
            ],
            [
                [0],
                1,
            ],
            [
                [0],
                '',
            ],
            [
                [0],
                'af',
            ],
        ];
    }
}
