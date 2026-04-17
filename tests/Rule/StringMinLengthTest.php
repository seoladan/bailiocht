<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\StringMinLength;

#[Group('validator-rules')]
#[CoversClass(StringMinLength::class)]
#[UsesClass(ValueRuleValidationException::class)]
class StringMinLengthTest extends ValueRuleTestCase
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
                [0],
                'dsgsrg',
            ],
            [
                [5],
                'asdsd',
            ],
            [
                [5],
                'asdsddfgsrg',
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
                [10],
                'asdsd',
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
