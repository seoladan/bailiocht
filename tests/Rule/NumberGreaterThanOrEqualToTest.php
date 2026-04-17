<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\NumberGreaterThanOrEqualTo;

#[Group('validator-rules')]
#[CoversClass(NumberGreaterThanOrEqualTo::class)]
#[UsesClass(ValueRuleValidationException::class)]
class NumberGreaterThanOrEqualToTest extends ValueRuleTestCase
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
                0,
            ],
            [
                [0],
                1,
            ],
            [
                [-2],
                0,
            ],
            [
                [-2.5],
                -2,
            ],
            [
                [3],
                3.5,
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [0],
                -1,
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
