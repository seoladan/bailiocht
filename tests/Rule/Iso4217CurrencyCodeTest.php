<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\Iso4217CurrencyCode;

#[Group('validator-rules')]
#[CoversClass(Iso4217CurrencyCode::class)]
#[UsesClass(ValueRuleValidationException::class)]
class Iso4217CurrencyCodeTest extends ValueRuleTestCase
{
    public static function valuePassesValidationProvider(): array
    {
        return [
            [
                [],
                null,
            ],
            [
                [],
                'GBP',
            ],
            [
                [],
                'gbp',
            ],
            [
                [],
                'EUR',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [],
                '£',
            ],
            [
                [],
                'US$',
            ],
            [
                [],
                '',
            ],
        ];
    }
}
