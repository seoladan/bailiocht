<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\UsesClass;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\Bailiocht\Rule\Rfc822EmailAddress;

#[Group('validator-rules')]
#[CoversClass(Rfc822EmailAddress::class)]
#[UsesClass(ValueRuleValidationException::class)]
class Rfc822EmailAddressTest extends ValueRuleTestCase
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
                'test@test.com',
            ],
            [
                [],
                'test+testing@test.com',
            ],
        ];
    }

    public static function valueFailsValidationProvider(): array
    {
        return [
            [
                [],
                0,
            ],
            [
                [],
                '',
            ],
            [
                [0],
                'af',
            ],
        ];
    }
}
