<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\NumberBetween;

#[Group('validator-rules')]
#[CoversClass(NumberBetween::class)]
#[UsesClass(ValueRuleValidationException::class)]
class NumberBetweenTest extends ValueRuleTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [1, 20],
                null,
            ],
            [
                [1, 20],
                1,
            ],
            [
                [-20, 20],
                0,
            ],
            [
                [1, 20],
                1.0,
            ],
            [
                [1, 20],
                5,
            ],
            [
                [1, 20],
                20,
            ],
            [
                [1, 20],
                20.0,
            ],
            [
                [10, 1],
                5,
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [1, 20],
                0,
            ],
            [
                [1, 20, false],
                1,
            ],
            [
                [1, 20],
                -1,
            ],
            [
                [1, 20],
                21,
            ],
            [
                [1, 20, false],
                20,
            ],
            [
                [1, 20],
                '',
            ],
            [
                [1, 20],
                'af',
            ],
        ];
    }

    public static function valueWithInvalidConfigProvider(): array
    {
        return [
            [
                [1, 1],
                1,
            ]
        ];
    }
}
