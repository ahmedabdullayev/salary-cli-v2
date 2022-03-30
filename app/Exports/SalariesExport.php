<?php

namespace App\Exports;

use Illuminate\Support\Collection;
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
    * @return Collection
    */
    public function collection(): Collection
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
            'Payment Date',
            'Reminder Date',
        ];
    }
}
