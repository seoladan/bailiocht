<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Validator\Exception;

use Throwable;

interface PropertyException extends Throwable
{
    public function getObject(): object;

    public function getProperty(): string;
}
