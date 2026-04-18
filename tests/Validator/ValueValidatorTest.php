<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Validator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Seoladan\Bailiocht\Exception\ConfigurationException;
use Seoladan\Bailiocht\Exception\Exception;
use Seoladan\Bailiocht\Exception\ValidationException;
use Seoladan\Bailiocht\Rule\NotEmpty;
use Seoladan\Bailiocht\Rule\NumberBetween;
use Seoladan\Bailiocht\Rule\StringMinLength;
use Seoladan\Bailiocht\Validator\Exception\ValidatorException;
use Seoladan\Bailiocht\Validator\ValueValidator;

#[CoversClass(ValueValidator::class)]
class ValueValidatorTest extends TestCase
{
    public static function validateValuePassesProvider(): array
    {
        return [
            [
                'John Doe',
                [
                    new NotEmpty(),
                    new StringMinLength(5),
                ],
            ],
            [
                15,
                [
                    new NumberBetween(5, 20),
                ],
            ],
        ];
    }

    #[DataProvider('validateValuePassesProvider')]
    public function testValidateValueAgainstPasses($value, $rules) {
        $this->expectNotToPerformAssertions();

        $validator = new ValueValidator();

        $validator->validateValueAgainst($value, $rules);
    }

    public static function validateValueFailsProvider(): array
    {
        return [
            [
                '',
                [
                    new NotEmpty(),
                    new StringMinLength(5),
                ],
                2,
            ],
            [
                -1,
                [
                    new NumberBetween(5, 20),
                ],
                1,
            ],
        ];
    }

    #[DataProvider('validateValueFailsProvider')]
    public function testValidateValueAgainstFails($value, $rules, $expectedExceptions) {
        $validator = new ValueValidator();

        try {
            $validator->validateValueAgainst($value, $rules);
            $this->fail();
        } catch (Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
            $this->assertInstanceOf(ValidatorException::class, $e);
            $this->assertCount($expectedExceptions, $e->getValidationRuleExceptions());
        }
    }

    public static function validateValueFailsInvalidConfigProvider(): array
    {
        return [
            [
                -1,
                [
                    new NumberBetween(0, 0),
                ],
                1,
            ],
        ];
    }

    #[DataProvider('validateValueFailsInvalidConfigProvider')]
    public function testValidateValueAgainstInvalidConfig($value, $rules, $expectedExceptions) {
        $validator = new ValueValidator();

        try {
            $validator->validateValueAgainst($value, $rules);
            $this->fail();
        } catch (Exception $e) {
            $this->assertInstanceOf(ConfigurationException::class, $e);
            $this->assertInstanceOf(ValidatorException::class, $e);
            $this->assertCount($expectedExceptions, $e->getValidationRuleExceptions());
        }
    }
}
