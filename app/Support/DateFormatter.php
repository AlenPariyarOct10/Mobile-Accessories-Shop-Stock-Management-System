<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use DateTimeInterface;

class DateFormatter
{
    public static function human(DateTimeInterface|string|null $date, bool $withTime = false): string
    {
        if (! $date) {
            return '-';
        }

        $date = $date instanceof CarbonInterface
            ? $date
            : Carbon::parse($date);

        $label = match (true) {
            $date->isToday() => 'Today',
            $date->isYesterday() => 'Yesterday',
            $date->isTomorrow() => 'Tomorrow',
            default => $date->format('M j, Y'),
        };

        return $withTime ? $label.', '.$date->format('g:i A') : $label;
    }
}
