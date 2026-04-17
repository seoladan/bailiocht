<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Exception;

use Seoladan\Bailiocht\Exception\Exception;
use Seoladan\Bailiocht\Rule\ValidationRule;

/**
 * @template Validator of ValidationRule
 */
interface ValidationRuleException extends Exception
{
    /**
     * @return Validator
     */
    public function getValidator(): ValidationRule;
}
