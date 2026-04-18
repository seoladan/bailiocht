<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Tests\Rule\Factory;

use Exception;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Seoladan\Bailiocht\Rule\Factory\Exception\CannotSatisfyRequiredDependencyException;
use Seoladan\Bailiocht\Rule\Factory\Exception\ClassDoesNotExistException;
use Seoladan\Bailiocht\Rule\Factory\Exception\ClassDoesNotImplementValidationRule;
use Seoladan\Bailiocht\Rule\Factory\Exception\CreateRuleException;
use Seoladan\Bailiocht\Rule\Factory\Exception\FactoryException;
use Seoladan\Bailiocht\Rule\Factory\Exception\UnspecifiedDependencyException;
use Seoladan\Bailiocht\Rule\Factory\Exception\UnsupportedDependencyTypeException;
use Seoladan\Bailiocht\Rule\Factory\RuleFactory;
use Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies\HasDependenciesTestCase;
use Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies\HasDependenciesWithDefaultsTestCase;
use Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies\HasDependenciesWithNullDefaultsTestCase;
use Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies\IgnoredTypesTestCase;
use Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies\IntersectionDependencyTestCase;
use Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies\NoConstructorTestCase;
use Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies\TestCaseClass;
use Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies\UnionDependencyTestCase;
use Seoladan\Bailiocht\Tests\Rule\Factory\Data\CompileDependencies\UnspecifiedDependenciesTestCase;

#[CoversClass(RuleFactory::class)]
class RuleFactoryTest extends TestCase
{
    public function testCreateRuleWithNoConstructor(): void
    {
        $factory = new RuleFactory();

        $rule = $factory->createRule(NoConstructorTestCase::class);

        $this->assertInstanceOf(NoConstructorTestCase::class, $rule);
    }

    public function testCreateRuleHasDependenciesWhenContainerHasAll(): void
    {
        $mockContainer = new class() implements ContainerInterface {
            public function get(string $id)
            {
                return match($id) {
                    TestCaseClass::class => new TestCaseClass('test-1'),
                    'test-case-1' => new TestCaseClass('test-2'),
                    default => throw new class extends Exception implements NotFoundExceptionInterface {},
                };
            }

            public function has(string $id): bool
            {
                return true;
            }
        };
        $factory = new RuleFactory($mockContainer);

        $rule = $factory->createRule(HasDependenciesTestCase::class, 'test');

        $this->assertInstanceOf(HasDependenciesTestCase::class, $rule);
        $this->assertEquals('test', $rule->getName());
        $this->assertEquals('test-1', $rule->getRequired()->getId());
        $this->assertEquals('test-2', $rule->getOptional()->getId());
        $this->assertNull($rule->getHasDefault());
    }

    public function testCreateRuleHasDependenciesWhenContainerHasRequired(): void
    {
        $mockContainer = new class() implements ContainerInterface {
            public function get(string $id)
            {
                return match($id) {
                    TestCaseClass::class => new TestCaseClass('test-1'),
                    default => throw new class extends Exception implements NotFoundExceptionInterface {},
                };
            }

            public function has(string $id): bool
            {
                return true;
            }
        };
        $factory = new RuleFactory($mockContainer);

        $rule = $factory->createRule(HasDependenciesTestCase::class, 'test');

        $this->assertInstanceOf(HasDependenciesTestCase::class, $rule);
        $this->assertEquals('test', $rule->getName());
        $this->assertEquals('test-1', $rule->getRequired()->getId());
        $this->assertNull($rule->getOptional());
        $this->assertNull($rule->getHasDefault());
    }

    public function testCreateRuleHasDependenciesWithDefaultsWhenContainerHasAll(): void
    {
        $mockContainer = new class() implements ContainerInterface {
            public function get(string $id)
            {
                return match($id) {
                    'test-case-1' => new TestCaseClass('test-1'),
                    'test-case-2' => new TestCaseClass('test-2'),
                    default => throw new class extends Exception implements NotFoundExceptionInterface {},
                };
            }

            public function has(string $id): bool
            {
                return true;
            }
        };
        $factory = new RuleFactory($mockContainer);

        $rule = $factory->createRule(HasDependenciesWithDefaultsTestCase::class, 'test');

        $this->assertInstanceOf(HasDependenciesWithDefaultsTestCase::class, $rule);
        $this->assertEquals('test', $rule->getName());
        $this->assertNull($rule->getEnum());
        $this->assertEquals('test-1', $rule->getRequiredWithDefault()->getId());
        $this->assertEquals('test-2', $rule->getOptionalWithDefault()->getId());
        $this->assertNull($rule->getHasDefault());
    }

    public function testCreateRuleHasDependenciesWithDefaultsWhenContainerHasRequired(): void
    {
        $mockContainer = new class() implements ContainerInterface {
            public function get(string $id)
            {
                return match($id) {
                    'test-case-1' => new TestCaseClass('test-1'),
                    default => throw new class extends Exception implements NotFoundExceptionInterface {},
                };
            }

            public function has(string $id): bool
            {
                return true;
            }
        };
        $factory = new RuleFactory($mockContainer);

        $rule = $factory->createRule(HasDependenciesWithDefaultsTestCase::class, 'test');

        $this->assertInstanceOf(HasDependenciesWithDefaultsTestCase::class, $rule);
        $this->assertNull($rule->getEnum());
        $this->assertEquals('test', $rule->getName());
        $this->assertEquals('test-1', $rule->getRequiredWithDefault()->getId());
        $this->assertEquals('default-2', $rule->getOptionalWithDefault()->getId());
        $this->assertNull($rule->getHasDefault());
    }

    public function testCreateRuleHasDependenciesWithNullDefaultsWhenContainerHasAll(): void
    {
        $mockContainer = new class() implements ContainerInterface {
            public function get(string $id)
            {
                return match($id) {
                    'test-case-3' => new TestCaseClass('test-3'),
                    'test-case-4' => new TestCaseClass('test-4'),
                    default => throw new class extends Exception implements NotFoundExceptionInterface {},
                };
            }

            public function has(string $id): bool
            {
                return true;
            }
        };

        $factory = new RuleFactory($mockContainer);

        $rule = $factory->createRule(HasDependenciesWithNullDefaultsTestCase::class, 'test');

        $this->assertInstanceOf(HasDependenciesWithNullDefaultsTestCase::class, $rule);
        $this->assertEquals('test', $rule->getName());
        $this->assertNull($rule->getEnum());
        $this->assertEquals('test-3', $rule->getRequiredWithNullDefault()->getId());
        $this->assertEquals('test-4', $rule->getOptionalWithNullDefault()->getId());
        $this->assertNull($rule->getHasDefault());
    }

    public function testCreateRuleHasDependenciesWithNullDefaultsWhenContainerHasRequired(): void
    {
        $mockContainer = new class() implements ContainerInterface {
            public function get(string $id)
            {
                return match($id) {
                    'test-case-3' => new TestCaseClass('test-3'),
                    default => throw new class extends Exception implements NotFoundExceptionInterface {},
                };
            }

            public function has(string $id): bool
            {
                return true;
            }
        };

        $factory = new RuleFactory($mockContainer);

        $rule = $factory->createRule(HasDependenciesWithNullDefaultsTestCase::class, 'test');

        $this->assertInstanceOf(HasDependenciesWithNullDefaultsTestCase::class, $rule);
        $this->assertNull($rule->getEnum());
        $this->assertEquals('test', $rule->getName());
        $this->assertEquals('test-3', $rule->getRequiredWithNullDefault()->getId());
        $this->assertNull($rule->getOptionalWithNullDefault());
        $this->assertNull($rule->getHasDefault());
    }

    public static function createThrowsExceptionProvider(): array
    {
        return [
            [
                '',
                null,
                ClassDoesNotExistException::class,
                'Rule "" does not exist',
            ],
            [
                'notAClass',
                null,
                ClassDoesNotExistException::class,
                'Rule "notAClass" does not exist',
            ],
            [
                RuleFactory::class,
                null,
                ClassDoesNotImplementValidationRule::class,
                '"RuleFactory" is not a validation rule',
            ],
            [
                UnspecifiedDependenciesTestCase::class,
                null,
                UnspecifiedDependencyException::class,
                'UnspecifiedDependenciesTestCase parameter "hasDefault" is not marked as a runtime dependency',
            ],
            [
                UnionDependencyTestCase::class,
                null,
                UnsupportedDependencyTypeException::class,
                'Union type for UnionDependencyTestCase parameter "required" is unsupported',
            ],
            [
                IntersectionDependencyTestCase::class,
                null,
                UnsupportedDependencyTypeException::class,
                'Intersection type for IntersectionDependencyTestCase parameter "required" is unsupported',
            ],
            [
                IgnoredTypesTestCase::class,
                null,
                CreateRuleException::class,
                '/^Unable to create.+: Too few arguments.+$/',
            ],
            [
                HasDependenciesTestCase::class,
                null,
                CannotSatisfyRequiredDependencyException::class,
                'Required dependency "required" for HasDependenciesTestCase is not available',
            ],
            [
                HasDependenciesTestCase::class,
                new class() implements ContainerInterface {
                    public function get(string $id)
                    {
                        return match($id) {
                            'test-case-1' => new TestCaseClass('test-2'),
                            default => throw new class extends Exception implements NotFoundExceptionInterface {},
                        };
                    }

                    public function has(string $id): bool
                    {
                        return true;
                    }
                },
                CannotSatisfyRequiredDependencyException::class,
                'Required dependency "required" for HasDependenciesTestCase is not available',
            ],
            [
                HasDependenciesWithDefaultsTestCase::class,
                null,
                CannotSatisfyRequiredDependencyException::class,
                'Required dependency "requiredWithDefault" for HasDependenciesWithDefaultsTestCase is not available',
            ],
            [
                HasDependenciesWithDefaultsTestCase::class,
                new class() implements ContainerInterface {
                    public function get(string $id)
                    {
                        return match($id) {
                            'test-case-2' => new TestCaseClass('test-2'),
                            default => throw new class extends Exception implements NotFoundExceptionInterface {},
                        };
                    }

                    public function has(string $id): bool
                    {
                        return true;
                    }
                },
                CannotSatisfyRequiredDependencyException::class,
                'Required dependency "requiredWithDefault" for HasDependenciesWithDefaultsTestCase is not available',
            ],
            [
                HasDependenciesWithNullDefaultsTestCase::class,
                null,
                CannotSatisfyRequiredDependencyException::class,
                'Required dependency "requiredWithNullDefault" for HasDependenciesWithNullDefaultsTestCase is not available',
            ],
            [
                HasDependenciesWithNullDefaultsTestCase::class,
                new class() implements ContainerInterface {
                    public function get(string $id)
                    {
                        return match($id) {
                            'test-case-4' => new TestCaseClass('test-4'),
                            default => throw new class extends Exception implements NotFoundExceptionInterface {},
                        };
                    }

                    public function has(string $id): bool
                    {
                        return true;
                    }
                },
                CannotSatisfyRequiredDependencyException::class,
                'Required dependency "requiredWithNullDefault" for HasDependenciesWithNullDefaultsTestCase is not available',
            ],
        ];
    }

    /**
     * @param class-string $class
     * @param class-string<FactoryException> $expectedException
     * @return void
     */
    #[DataProvider('createThrowsExceptionProvider')]
    public function testCreateThrowsException(
        string $class,
        ?ContainerInterface $container,
        string $expectedException,
        string $expectedExceptionMessage,
    ): void {
        $factory = new RuleFactory($container);

        try {
            $factory->createRule($class);
            $this->fail();
        } catch (FactoryException $e) {
            $this->assertInstanceOf($expectedException, $e);

            if (str_starts_with($expectedExceptionMessage, '/')) {
                $this->assertMatchesRegularExpression($expectedExceptionMessage, $e->getMessage());
            } else {
                $this->assertEquals($expectedExceptionMessage, $e->getMessage());
            }

        }
    }
}
