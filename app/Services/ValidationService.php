<?php

namespace App\Services;

class ValidationService
{
    /**
     * Check user input
     *
     * @param  $input
     * @return string|null
     */
    public static function checkYearInput($input) : ?string {
        if(!is_numeric($input)){
            return "Please provide a year(only numbers)";
        }elseif ($input < 1900 || $input > 3000){
            return "Please provide a year in range of 1900 - 3000";
        }
        return null;
    }
}
