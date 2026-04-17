<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\StringLengthBetween;

#[Group('validator-rules')]
#[CoversClass(StringLengthBetween::class)]
#[UsesClass(ValueRuleValidationException::class)]
class StringLengthBetweenTest extends ValueRuleTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [5, 10],
                null,
            ],
            [
                [5, 10],
                'abcde',
            ],
            [
                [5, 10],
                'abcdefg',
            ],
            [
                [5, 10],
                'abcdefghij',
            ],
            [
                [0, 10],
                'dsgsrg',
            ],
            [
                [0, 10],
                '',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [2, 10],
                0,
            ],
            [
                [0, 10],
                false,
            ],
            [
                [5, 10],
                'abcd',
            ],
            [
                [5, 10],
                'abcdefghijk',
            ],
            [
                [0, 10],
                'asdsddfgsrg',
            ],
        ];
    }

    public static function valueWithInvalidConfigProvider(): array
    {
        return [
            [
                [-1, 0],
                '',
            ],
            [
                [-5, -1],
                '',
            ],
            [
                [5, 2],
                '',
            ],
        ];
    }
}
