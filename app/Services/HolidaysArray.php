<?php

namespace App\Services;

use App\Interfaces\HolidayInterface;

class HolidaysArray implements HolidayInterface
{
    // here other dev can add holidays(riigipühad)
    private array $holidaysArray = ['01-10'];
    /**
     * Add specific days of the month to consider them as holidays if needed
     *
     * @param  array $holidays
     */
    public function addHolidays(array $holidays) : void
    {
        $this->holidaysArray = array_merge($this->holidaysArray, $holidays);
    }
    /**
     * Get holidays
     *
     * @return array
     */
    public function getHolidays(): array
    {
        return $this->holidaysArray;
    }
}
