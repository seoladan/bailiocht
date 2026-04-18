<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Factory\Exception;

use DomainException;

class ClassDoesNotImplementValidationRule extends DomainException implements FactoryException
{
}
