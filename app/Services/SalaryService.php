<?php

namespace App\Services;

use DatePeriod;
use DateTime;
use DateInterval;
use App\Exports\SalariesExport;
use Exception;
use Maatwebsite\Excel\Facades\Excel;

class SalaryService
{
    private HolidaysArray $holidaysArray;
    function __construct(){
        $this->holidaysArray = new HolidaysArray();
    }

    /**
     * Search/collect salary days
     *
     * @param int $year
     * @param int $nthDay
     * @return array
     * @throws Exception
     */
        public function searchForSalaryDays(int $year, int $nthDay): array
        {
            $iter = 1;
            $arrayOfDates = array();
            $nextYear = $year + 1;
            $period = new DatePeriod(
            new DateTime($year.'-01-01'),
            new DateInterval('P1D'),
            new DateTime($nextYear.'-01-01')
            );
            foreach ($period as $key => $value) {
                $fullDate = $value->format('Y-m-d');
                $days = $value->format('t');
                $paymentDate = $fullDate;
                $timestampOfPaymentDate = strtotime($paymentDate);
                if($iter >= $days){
                    $iter = 0;
                }
                if($iter == $nthDay){ // 10 - in our case it is 10th day of a month
                    $dateMinusThreeDays = date('Y-m-d', strtotime($paymentDate .' -3 day'));
                    while(date("l", $timestampOfPaymentDate) == "Saturday"
                            || date("l", $timestampOfPaymentDate) == "Sunday"
                            || in_array(date("m-d", $timestampOfPaymentDate), $this->holidaysArray->getHolidays())){
                        $paymentDate = date('Y-m-d', strtotime($paymentDate .' -1 day'));
                        $dateMinusThreeDays = date('Y-m-d', strtotime($paymentDate .' -3 day'));
                        $timestampOfPaymentDate = strtotime($paymentDate);
                    }
                    $arrayOfDates[$paymentDate] = $dateMinusThreeDays;
                }
                $iter++;
            }
        return $arrayOfDates;
    }

    /**
     * Modify created array of salaries for better xlsx array format
     *
     * @param $salariesArray
     * @return array
     */
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

    /**
     * Store excel file in storage/app/salaries
     *
     * @param $data
     * @param $year
     * @return bool
     */
    function array2csv($data, $year): bool
    {
        $data = $this->ModifyArrayForXlsx($data);
        return Excel::store(new SalariesExport($data), 'salaries/'.$year.'.xlsx');
    }
}
