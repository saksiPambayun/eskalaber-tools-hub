<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LoansExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = Loan::with(['user', 'tool']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'User',
            'Tool',
            'Loan Date',
            'Return Date',
            'Actual Return Date',
            'Status',
            'Notes'
        ];
    }

    public function map($loan): array
    {
        static $row = 0;
        $row++;

        return [
            $row,
            $loan->user->name ?? '-',
            $loan->tool->name ?? '-',
            $loan->loan_date ? $loan->loan_date->format('d-m-Y') : '-',
            $loan->return_date ? $loan->return_date->format('d-m-Y') : '-',
            $loan->actual_return_date ? $loan->actual_return_date->format('d-m-Y') : '-',
            ucfirst($loan->status),
            $loan->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}