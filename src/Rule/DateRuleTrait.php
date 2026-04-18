<?php

declare(strict_types=1);

namespace Seoladan\Bailiocht\Rule;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Seoladan\Bailiocht\Rule\Exception\ValueRuleConfigurationException;
use Seoladan\DateTime\DateRoundingMode;
use Seoladan\DateTime\DateRoundingUnit;
use Seoladan\DateTime\Exception\ParseDateException;
use Seoladan\DateTime\Exception\ParseDateIntervalException;
use Seoladan\DateTime\Now;
use Seoladan\DateTime\Parser as DateParser;

trait DateRuleTrait
{
    protected const DEFAULT_FORMAT = DateTimeInterface::RFC3339;

    protected readonly DateTimeImmutable|DateInterval|string $date;
    protected readonly ?string $format;
    protected readonly ?DateRoundingMode $roundingMode;
    protected readonly ?DateRoundingUnit $roundingUnit;
    protected readonly ?DateParser $parser;
    protected readonly ?Now $now;

    public function validateValue(mixed $value): void
    {
        if ($value === null) {
            return;
        }

        $this->checkDate($this->getDateFromValue($value), $this->getDateFromValue($this->date));
    }

    abstract protected function checkDate(DateTimeInterface $value, DateTimeImmutable $date): void;

    private function getDateFromValue(DateTimeInterface|DateInterval|string $date): DateTimeImmutable
    {
        $format = $this->format ?? static::DEFAULT_FORMAT;
        $roundingMode = $this->roundingMode ?? DateRoundingMode::ToStart;
        $roundingUnit = $this->roundingUnit ?? DateRoundingUnit::fromDateFormat($format);

        if (is_string($date)) {
            if (!$this->parser) {
                throw new ValueRuleConfigurationException($this, 'Date parser is not available');
            }

            try {
                // Try to parse as a relative date
                $interval = $this->parser->parseDateInterval($date, $format, $roundingMode, $roundingUnit);

                if ($interval) {
                    if (!$interval->getRelativeTo() && !$this->now) {
                        throw new ValueRuleConfigurationException($this, 'Current date & time is not available');
                    }

                    $date = $interval->applyToDate($interval->getRelativeTo() ?? $this->now->getDateTime());
                } else {
                    $date = $this->parser->parseDate($date, $format, $roundingMode, $roundingUnit);
                }
            } catch (ParseDateIntervalException|ParseDateException $exception) {
                throw new ValueRuleConfigurationException($this, $exception->getMessage(), previous: $exception);
            }
        }

        if ($date instanceof DateInterval) {
            if (!$this->now) {
                throw new ValueRuleConfigurationException($this, 'Current date & time is not available');
            }

            $date = $this->now->getDateTime()->add($date);
        }

        if ($date instanceof DateTimeImmutable) {
            return $date;
        }

        return DateTimeImmutable::createFromInterface($date);
    }
}
