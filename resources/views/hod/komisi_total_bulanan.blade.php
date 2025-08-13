@extends('layouts.hod')

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
            @foreach($personelData as $nama => $bulanData)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $nama }}</td>
                    @php $subtotal = 0; @endphp
                    @for($i = 1; $i <= 12; $i++)
                        @php 
                            $nilai = $bulanData[$i];
                            $subtotal += $nilai;
                        @endphp
                        <td>{{ $nilai > 0 ? 'Rp ' . number_format($nilai, 0, ',', '.') : '-' }}</td>
                    @endfor
                    <td><strong>Rp {{ number_format($subtotal, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('hod.komisi') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
