<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Seoladan\Bailiocht\Rule\Exception\ValidationRuleException;
use Seoladan\Riail\Rule;

/**
 * @template RuleException of ValidationRuleException
 */
interface ValidationRule extends Rule
{
}
