<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\NotMatchRegex;

#[Group('validator-rules')]
#[CoversClass(NotMatchRegex::class)]
#[UsesClass(ValueRuleValidationException::class)]
class NotMatchRegexTest extends ValueRuleTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [
                    '/^<[a-z]*>$/',
                ],
                null,
            ],
            [
                [
                    '/^<[a-z]*>$/',
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
                'string',
            ],
            [
                [
                    '/^[^<>]*$/',
                    'Custom message'
                ],
                'string',
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
