<?php

namespace App\Console\Commands;

use App\Services\SalaryService;
use App\Services\ValidationService;
use Exception;
use Illuminate\Console\Command;

class SalaryCommand extends Command
{

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'start:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create list of salaries';


    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $service = new SalaryService();
        $this->info("Welcome!");
        $year = $this->ask("Please provide a year (before that please close xlsx
                                    file with the same year, if there is!)");
        if ($this->confirm('Do you wish to continue with '.$year.' year ?')) {
            try {
            if(ValidationService::checkYearInput($year) != null){
                $this->warn("Warn: ". ValidationService::checkYearInput($year));
                return;
            }
            $dates = $service->searchForSalaryDays($year, 10);
                if($service->array2csv($dates, $year)){
                    $this->info('Success! Salaries xlsx doc has been created in app/storage/salaries !');
                }
            } catch (Exception $e){
                $this->warn("Error: Please close xlsx file with that year: ". $year);
            }

        }
    }
}
