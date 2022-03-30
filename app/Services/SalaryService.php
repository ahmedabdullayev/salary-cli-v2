<?php

namespace App\Services;

use DatePeriod;
use DateTime;
use DateInterval;
use App\Exports\SalariesExport;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SalaryService
{
    private HolidaysArray $holidaysArray;
    function __construct(){
        $this->holidaysArray = new HolidaysArray();
    }

    /**
     * @throws \Exception
     */
        public function searchForSalaryDays(int $year): array
        {
            $iter = 1;
            $array = array();
            $nextYear = $year + 1;
            $period = new DatePeriod(
            new DateTime($year.'-01-01'),
            new DateInterval('P1D'),
            new DateTime($nextYear.'-01-01')
            );
            foreach ($period as $key => $value) {
                $fullDate = $value->format('Y-m-d');
                $days = $value->format('t');
                $dateMinus = $fullDate;
                $timestamp = strtotime($dateMinus);
                if($iter >= $days){
                $iter = 0;
                }
                if($iter == 10){
                    while(date("l", $timestamp) == "Saturday" || date("l", $timestamp) == "Sunday"
                            || in_array(date("m-d", $timestamp), $this->holidaysArray->getHolidays())){
                        $array[$dateMinus] = 'No';
                        $dateMinus = date('Y-m-d', strtotime($dateMinus .' -1 day'));
                        $timestamp = strtotime($dateMinus);
                    }
                    $array[$dateMinus] = "Salary day";
                }else{
                    $array[$dateMinus] = 'No';
                }
                $iter++;
            }
        return $array;
    }

    function ModifyArrayForXlsx($salariesArray): array
    {
            $xlsxArray = [];
            foreach ($salariesArray as $item=>$value){
                $xlsxArray[] = array(
                    'Date'=>$item,
                    'Salary'=>$value,
                );
            }
            return $xlsxArray;
    }

    function array2csv($data, $year)
    {
        $data = $this->ModifyArrayForXlsx($data);
        echo Excel::store(new SalariesExport($data), 'salaries/'.$year.'.xlsx');
    }
}
