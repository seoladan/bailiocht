<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Validator;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Seoladan\Bailiocht\Exception\ConfigurationException;
use Seoladan\Bailiocht\Exception\Exception;
use Seoladan\Bailiocht\Exception\ValidationException;
use Seoladan\Bailiocht\Rule\Factory\RuleFactory;
use Seoladan\Bailiocht\Rule\NotEmpty;
use Seoladan\Bailiocht\Rule\NoValidate;
use Seoladan\Bailiocht\Rule\NumberBetween;
use Seoladan\Bailiocht\Rule\NumberGreaterThan;
use Seoladan\Bailiocht\Validator\Exception\ValidatorException;
use Seoladan\Bailiocht\Validator\ObjectValidator;
use Seoladan\Bailiocht\Validator\ValueValidator;

#[CoversClass(ObjectValidator::class)]
class ObjectValidatorTest extends TestCase
{
    public static function validateObjectPassesProvider(): array
    {
        return [
            [
                self::createTestObject('John Doe'),
            ],
            [
                self::createTestObject('John Doe', 15),
            ],
            [
                new #[NoValidate] class('John Doe')  {
                    public function __construct(
                        public string $name,
                    ) {}
                }
            ],
            [
                new class('John Doe')  {
                    public function __construct(
                        #[NoValidate]
                        public string $name,
                    ) {}
                }
            ],
        ];
    }

    #[DataProvider('validateObjectPassesProvider')]
    public function testValidateObjectPasses(object $object): void {
        $validator = new ObjectValidator(new ValueValidator(), new RuleFactory());

        $this->assertSame($object, $validator->validateObject($object));
    }

    public static function validateObjectFailsProvider(): array
    {
        return [
            [
                self::createTestObject('John Doe', -1),
                1,
            ],
        ];
    }

    #[DataProvider('validateObjectFailsProvider')]
    public function testValidateObjectFails(object $object, int $expectedExceptions) {
        $validator = new ObjectValidator(new ValueValidator(), new RuleFactory());

        try {
            $validator->validateObject($object);
            $this->fail();
        } catch (Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
            $this->assertInstanceOf(ValidatorException::class, $e);
            $this->assertCount($expectedExceptions, $e->getValidationRuleExceptions());
        }
    }

    public function testValidateObjectWithInvalidRuleConfig() {
        $object = new class('John Doe', 5) {
            public function __construct(
                public string $name,
                #[NumberBetween(0, 0)]
                public int $age,
            ) {}
        };

        $validator = new ObjectValidator(new ValueValidator(), new RuleFactory());

        try {
            $validator->validateObject($object);
            $this->fail();
        } catch (Exception $e) {
            $this->assertInstanceOf(ConfigurationException::class, $e);
            $this->assertInstanceOf(ValidatorException::class, $e);
            $this->assertCount(1, $e->getValidationRuleExceptions());
        }
    }

    private static function createTestObject(string $name, ?int $age = null): object {
        return new class($name, $age)  {
            public function __construct(
                #[NotEmpty]
                public string $name,
                #[NumberGreaterThan(0)]
                public ?int $age,
            ) {}
        };
    }
}
