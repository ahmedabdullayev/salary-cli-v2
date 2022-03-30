<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalariesExport implements FromCollection, WithHeadings
{
    protected $data;

    /**
     * Write code on Method
     *
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }

    /**
     * Write code on Method
     *
     */
    public function headings() :array
    {
        return [
            'Date',
            'Salary',
        ];
    }
}
