<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Validator;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Seoladan\Bailiocht\Exception\ConfigurationException;
use Seoladan\Bailiocht\Exception\Exception as BailiochtException;
use Seoladan\Bailiocht\Exception\ValidationException;
use Seoladan\Bailiocht\Rule\DateAfter;
use Seoladan\Bailiocht\Rule\Factory\RuleFactory;
use Seoladan\Bailiocht\Rule\NotEmpty;
use Seoladan\Bailiocht\Rule\NoValidate;
use Seoladan\Bailiocht\Rule\NumberBetween;
use Seoladan\Bailiocht\Rule\NumberGreaterThan;
use Seoladan\Bailiocht\Validator\Exception\ValidatorException;
use Seoladan\Bailiocht\Validator\ObjectValidator;
use Seoladan\Bailiocht\Validator\ValueValidator;
use Seoladan\DateTime\Now;
use Seoladan\DateTime\Parser;

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
                self::createTestObject('John Doe', 15, new \DateTimeImmutable('2026-01-01')),
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
        $validator = $this->getObjectValidator();

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
        $validator = $this->getObjectValidator();

        try {
            $validator->validateObject($object);
            $this->fail();
        } catch (BailiochtException $e) {
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

        $validator = $this->getObjectValidator();

        try {
            $validator->validateObject($object);
            $this->fail();
        } catch (BailiochtException $e) {
            $this->assertInstanceOf(ConfigurationException::class, $e);
            $this->assertInstanceOf(ValidatorException::class, $e);
            $this->assertCount(1, $e->getValidationRuleExceptions());
        }
    }

    private static function createTestObject(string $name, ?int $age = null, ?\DateTimeImmutable $date = null): object {
        return new class($name, $age, $date)  {
            public function __construct(
                #[NotEmpty]
                public string $name,
                #[NumberGreaterThan(0)]
                public ?int $age,
                #[DateAfter(new \DateTimeImmutable('2025-01-01'))]
                public ?\DateTimeImmutable $date,
            ) {}
        };
    }

    protected function getObjectValidator(bool $withContainer = true): ObjectValidator
    {
        $container = null;

        if ($withContainer) {
            $now = new Now(new \DateTimeImmutable('2025-01-01'));

            $container = new class($now, new Parser($now)) implements ContainerInterface
            {
                protected Now $now;
                protected Parser $dateParser;

                public function __construct(Now $now, Parser $dateParser) {
                    $this->now = $now;
                    $this->dateParser = $dateParser;
                }

                /**
                 * @inheritDoc
                 */
                public function get(string $id)
                {
                    return match ($id) {
                        Now::class => $this->now,
                        Parser::class => $this->dateParser,
                        default => throw new class extends Exception implements NotFoundExceptionInterface {}
                    };
                }

                /**
                 * @inheritDoc
                 */
                public function has(string $id): bool
                {
                    return match ($id) {
                        Now::class, Parser::class => true,
                        default => false,
                    };
                }
            };
        }

        return new ObjectValidator(new ValueValidator(), new RuleFactory($container));
    }
}
