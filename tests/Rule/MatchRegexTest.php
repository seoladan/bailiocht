<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\MatchRegex;

#[Group('validator-rules')]
#[CoversClass(MatchRegex::class)]
#[UsesClass(ValueRuleValidationException::class)]
class MatchRegexTest extends ValueRuleTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [
                    '/^[^<>]*$/',
                ],
                null,
            ],
            [
                [
                    '/^[^<>]*$/',
                ],
                'string',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [
                    '/^[^<>]*$/',
                ],
                'string<',
            ],
            [
                [
                    '/^[^<>]*$/',
                    'Custom message'
                ],
                'string<',
                'Custom message'
            ],
        ];
    }

    public static function valueWithInvalidConfigProvider(): array
    {
        return [
            [
                [
                    ''
                ],
                '',
            ],
            [
                [
                    '/^[^<>]*$'
                ],
                '',
            ],
            [
                [
                    '/^[^<>*$/'
                ],
                '',
            ],
        ];
    }
}
