<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\IsInList;

#[Group('validator-rules')]
#[CoversClass(IsInList::class)]
#[UsesClass(ValueRuleValidationException::class)]
class IsInListTest extends ValueRuleTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [
                    ['A', 'B', 'C'],
                ],
                null
            ],
            [
                [
                    ['A', 'B', 'C'],
                ],
                'A'
            ],
            [
                [
                    ['A', 'B', 'C'],
                ],
                'B'
            ],
            [
                [
                    ['A', 'B', 'C'],
                ],
                'C'
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [
                    ['A', 'B', 'C'],
                ],
                ''
            ],
            [
                [
                    ['A', 'B', 'C'],
                ],
                0
            ],
            [
                [
                    ['A', 'B', 'C'],
                ],
                'D'
            ],
        ];
    }

    public static function valueWithInvalidConfigProvider(): array
    {
        return [
            [
                [
                    [],
                ],
                '',
            ]
        ];
    }
}
