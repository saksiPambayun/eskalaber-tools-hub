<?php

namespace App\Exports;

use App\Models\Fine;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinesExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = Fine::with(['user', 'loan']);

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
            'Loan ID',
            'Amount',
            'Description',
            'Late Days',
            'Status',
            'Payment Date',
            'Payment Method',
        ];
    }

    public function map($fine): array
    {
        static $row = 0;
        $row++;

        return [
            $row,
            $fine->user->name ?? '-',
            $fine->loan_id,
            'Rp ' . number_format($fine->amount, 0, ',', '.'),
            $fine->description ?? '-',
            $fine->late_days . ' hari',
            ucfirst($fine->status),
            $fine->payment_date ? $fine->payment_date->format('d-m-Y') : '-',
            $fine->payment_method ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}