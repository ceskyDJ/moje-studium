<?php

declare(strict_types = 1);

namespace App\Utils;

use DateTime;

/**
 * Helper for extend PHP's date functions
 *
 * @author Michal Šmahel (ceskyDJ) <admin@ceskydj.cz>
 * @package App\Utils
 */
class DateHelper
{
    /**
     * Returns czech style of short date (day and month)
     *
     * @param \DateTime $date Date to convert
     *
     * @return string Short date (for ex.: po 9. 3.)
     */
    public function getCzechShortDate(DateTime $date): string
    {
        $daysInWeek = ["po", "út", "st", "čt", "pá", "so", "ne"];

        return $daysInWeek[$date->format("N") - 1] . " {$date->format("j. n.")}";
    }

    /**
     * Returns monday's date
     *
     * @param \DateTime $date Source date
     *
     * @return \DateTime Monday's date
     */
    public function getMondayDate(DateTime $date): DateTime
    {
        // How many days is it from monday?
        $offsetFromMonday = $date->format("N") - 1;

        return $date->modify("- {$offsetFromMonday} days");
    }
}