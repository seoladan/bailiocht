<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Metadata;

use Attribute;

/**
 * @template Object of object
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class OptionalDependency implements Dependency
{
    private string $class;

    public function __construct(
        private readonly ?string $identifier = null,
    ) {}

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }
    public function isRequired(): bool
    {
        return false;
    }
}
