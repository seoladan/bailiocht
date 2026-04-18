<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Metadata;

/**
 * @template Object of object
 */
interface Dependency extends FactoryMetadata
{
    public function isRequired(): bool;

    /**
     * @return class-string<Object>
     */
    public function getClass(): string;

    /**
     * @param class-string<Object> $class
     * @return $this
     */
    public function setClass(string $class): self;

    /**
     * @return ?string
     */
    public function getIdentifier(): ?string;
}
