<?php

namespace App\Exports;

use App\Models\ProjectCommission;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class KomisiBulananExport implements FromArray, WithHeadings
{
    protected $personelData;

    /**
     * $personelData optional. Jika null, class ini akan membangunnya sendiri
     * berdasarkan data ProjectCommission.
     */
    public function __construct(array $personelData = null)
    {
        $this->personelData = $personelData;
    }

    protected function buildPersonelData(): array
    {
        if ($this->personelData !== null) {
            return $this->personelData;
        }

        $komisiSemuaProject = ProjectCommission::with(['projectPersonel.user', 'user'])->get();
        $personelData = [];

        foreach ($komisiSemuaProject as $komisi) {
            $nama = $komisi->user->name ?? ($komisi->projectPersonel->user->name ?? '-');
            $bulan = (int) Carbon::parse($komisi->created_at)->format('n'); // 1-12

            if (!isset($personelData[$nama])) {
                $personelData[$nama] = array_fill(1, 12, 0);
            }

            $personelData[$nama][$bulan] += $komisi->nilai_komisi;
        }

        return $this->personelData = $personelData;
    }

    /**
     * Dari data personel, kembalikan array baris untuk Excel.
     * Setiap baris: [Nama, Jan, Feb, ..., Des, Subtotal]
     * Terakhir ditambahkan baris "Total Semua Personel".
     */
    public function array(): array
    {
        $personelData = $this->buildPersonelData();

        $rows = [];
        $totalPerMonth = array_fill(1, 12, 0);

        foreach ($personelData as $nama => $bulanData) {
            $row = [$nama];
            $subtotal = 0;

            for ($m = 1; $m <= 12; $m++) {
                $val = $bulanData[$m] ?? 0;
                $row[] = $val;
                $subtotal += $val;
                $totalPerMonth[$m] += $val;
            }

            $row[] = $subtotal; // kolom Sub Total
            $rows[] = $row;
        }

        // baris total semua personel
        $totalRow = ['Total Semua Personel'];
        $grandTotal = 0;
        for ($m = 1; $m <= 12; $m++) {
            $totalRow[] = $totalPerMonth[$m];
            $grandTotal += $totalPerMonth[$m];
        }
        $totalRow[] = $grandTotal; // subtotal total
        $rows[] = $totalRow;

        return $rows;
    }

    public function headings(): array
    {
        return array_merge(
            ['Nama Personel'],
            ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
            ['Sub Total']
        );
    }
}
