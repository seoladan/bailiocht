<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule\Exception;

use Seoladan\Bailiocht\Rule\ValueRule;

/**
 * @implements ValidationRuleException<ValueRule>
 */
interface ValueRuleException extends ValidationRuleException
{
    public function getValidator(): ValueRule;
}
