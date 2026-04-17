<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\StringLength;

#[Group('validator-rules')]
#[CoversClass(StringLength::class)]
#[UsesClass(ValueRuleValidationException::class)]
class StringLengthTest extends ValueRuleTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [5],
                null,
            ],
            [
                [0],
                '',
            ],
            [
                [5],
                'asdsd',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [2],
                0,
            ],
            [
                [0],
                false,
            ],
            [
                [4],
                '',
            ],
            [
                [5],
                'asdsddfgsrg',
            ],
        ];
    }

    public static function valueWithInvalidConfigProvider(): array
    {
        return [
            [
                [-1],
                '',
            ],
        ];
    }
}
