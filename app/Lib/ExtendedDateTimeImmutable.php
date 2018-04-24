<?php

namespace App\Lib;

class ExtendedDateTimeImmutable extends \DateTimeImmutable
{
    /**
     * Convert from DateTime object to 30 hours format string.
     *
     * @param bool $isEnd
     * @return string
     */
    public function to30HoursFormatString(bool $isEnd = false): string
    {
        $date = $this;
        $diff = $date->diff($date->modify('midnight'));
        $diffStr = $diff->format('%H:%I:%S');

        if ($diffStr <= '04:59:59' || $diffStr <= '05:00:00' && $isEnd) {
            $diff->h += 24;
            $date = $date->modify('-1 day');
        }

        return "{$date->format('Y-m-d')} {$diff->format('%H:%I:%S')}";
    }

    /**
     * Convert from 30 hours format string to DateTime object.
     *
     * @param string $string
     * @param \DateTimeZone|null $timezone
     * @return boolean|\App\Lib\ExtendedDateTimeImmutable
     */
    public static function from30HoursFormatString(string $string, \DateTimeZone $timezone = null)
    {
        if (!preg_match('/^(\d{4}-\d{2}-\d{2})?(?: (\d{2}:\d{2}:\d{2}))?$/', $string, $m)) {
            return false;
        }

        $date = new static('', $timezone);

        if (!empty($m[1])) {
            $date = $date->setDate(...explode('-', $m[1]));
        }

        if (!empty($m[2])) {
            $date = $date->setTime(...explode(':', $m[2]));
        }

        return $date;
    }
}
