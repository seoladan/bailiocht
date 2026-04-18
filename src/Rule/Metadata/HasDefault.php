<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Metadata;

use Attribute;

/**
 * @template Object of object
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final readonly class HasDefault implements FactoryMetadata
{
}
