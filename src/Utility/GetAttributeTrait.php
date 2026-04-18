<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Utility;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionParameter;
use ReflectionProperty;

trait GetAttributeTrait
{

    /**
     * @template Attribute of object
     * @param ReflectionProperty|ReflectionClass|ReflectionParameter $reflect
     * @param class-string<Attribute> $attribute
     * @param bool $isInstanceOf
     * @return ?Attribute
     */
    protected function getAttribute(
        ReflectionProperty|ReflectionClass|ReflectionParameter $reflect,
        string $attribute,
        bool $isInstanceOf = false,
    ): ?object {
        $attributes = $reflect->getAttributes($attribute, $isInstanceOf ? ReflectionAttribute::IS_INSTANCEOF : 0);

        if ($attributes) {
            return $attributes[0]->newInstance();
        }

        return null;
    }
}
