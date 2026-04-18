<?php

namespace Seoladan\Bailiocht\Rule\Factory\Exception;

use ReflectionParameter;
use Seoladan\Bailiocht\Rule\Metadata\Dependency;

interface DependencyException extends FactoryException
{

    public function getParameter(): ReflectionParameter;

    public function getDependency(): ?Dependency;
}
