<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY|Attribute::TARGET_CLASS)]
final class NoValidate
{
}
