<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use Attribute;
use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleValidationException;
use Seoladan\DateTime\DateRoundingMode;
use Seoladan\DateTime\DateRoundingUnit;
use Seoladan\DateTime\Now;
use Seoladan\DateTime\Parser as DateParser;
use Seoladan\Riail\Metadata\OptionalDependency;

#[Attribute(Attribute::TARGET_PROPERTY)]
readonly class DateOnOrAfter implements ValueRule
{
    use DateRuleTrait;

    public function __construct(
        DateTimeImmutable|DateInterval|string $date,
        ?string $format = null,
        ?DateRoundingMode $roundingMode = null,
        ?DateRoundingUnit $roundingUnit = null,
        #[OptionalDependency]
        ?DateParser $parser = null,
        #[OptionalDependency]
        ?Now $now = null,
    ) {
        $this->now = $now;
        $this->parser = $parser;
        $this->roundingUnit = $roundingUnit;
        $this->roundingMode = $roundingMode;
        $this->format = $format;
        $this->date = $date;
    }

    /**
     * @param DateTimeInterface $value
     * @param DateTimeImmutable $date
     * @return void
     */
    protected function checkDate(DateTimeInterface $value, DateTimeImmutable $date): void
    {
        if ($value < $date) {
            throw new ValueRuleValidationException($this, sprintf(
                'Value must be on or after %s',
                $date->format($this->format ?? self::DEFAULT_FORMAT)
            ));
        }
    }
}
