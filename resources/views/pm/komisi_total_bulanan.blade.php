@extends('layouts.pm')

@section('content')
<div class="container">
    <h2>Total Komisi Perbulan</h2>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Personel</th>
                <th>Januari</th>
                <th>Februari</th>
                <th>Maret</th>
                <th>April</th>
                <th>Mei</th>
                <th>Juni</th>
                <th>Juli</th>
                <th>Agustus</th>
                <th>September</th>
                <th>Oktober</th>
                <th>November</th>
                <th>Desember</th>
                <th>Sub Total</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @php 
                // Siapkan array total per bulan
                $totalPerBulan = array_fill(1, 12, 0);
                $grandTotal = 0;
            @endphp

            @foreach($personelData as $nama => $bulanData)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $nama }}</td>
                    @php $subtotal = 0; @endphp
                    @for($i = 1; $i <= 12; $i++)
                        @php 
                            $nilai = $bulanData[$i];
                            $subtotal += $nilai;
                            $totalPerBulan[$i] += $nilai; // tambahkan ke total bulan
                        @endphp
                        <td>{{ $nilai > 0 ? 'Rp ' . number_format($nilai, 0, ',', '.') : '-' }}</td>
                    @endfor
                    @php $grandTotal += $subtotal; @endphp
                    <td><strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach

            {{-- Baris total semua personel --}}
            <tr class="table-secondary fw-bold">
                <td colspan="2">Total Semua Personel</td>
                @for($i = 1; $i <= 12; $i++)
                    <td>Rp {{ number_format($totalPerBulan[$i], 0, ',', '.') }}</td>
                @endfor
                <td>Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    <a href="{{ route('pm.komisi') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
