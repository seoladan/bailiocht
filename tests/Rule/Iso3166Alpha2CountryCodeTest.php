<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\Iso3166Alpha2CountryCode;

#[Group('validator-rules')]
#[CoversClass(Iso3166Alpha2CountryCode::class)]
#[UsesClass(ValueRuleValidationException::class)]
class Iso3166Alpha2CountryCodeTest extends ValueRuleTestCase
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
                'GB',
            ],
            [
                [],
                'gb',
            ],
            [
                [],
                'IE',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [],
                'UK',
            ],
            [
                [],
                'GBR',
            ],
            [
                [],
                '',
            ],
        ];
    }
}
