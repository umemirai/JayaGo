<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransaksiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected int $branchId;

    public function __construct(int $branchId)
    {
        $this->branchId = $branchId;
    }

    public function collection()
    {
        return Transaction::with('user')
            ->where('branch_id', $this->branchId)
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Invoice',
            'Kasir',
            'Total (Rp)',
            'Metode Pembayaran',
            'Status',
            'Tanggal',
        ];
    }

    public function map($t): array
    {
        return [
            $t->invoice_number,
            $t->user->name ?? '-',
            $t->total,
            ucfirst($t->payment_method),
            $t->status,
            $t->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF16A34A']],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Transaksi';
    }
}