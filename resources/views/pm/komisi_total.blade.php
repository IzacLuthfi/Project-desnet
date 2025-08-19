@extends('layouts.pm')

@section('content')
<div class="container">
    <h2>Total Komisi</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama Personel</th>
                <th>Total Margin</th>
                <th>Total Komisi</th>
            </tr>
        </thead>
        <tbody>
            @php
                $komisiPerPersonel = [];

                foreach ($komisiSemuaProject as $komisi) {
                    $nama = $komisi->user?->name ?? $komisi->projectPersonel?->user?->name ?? '-';
                    if (!isset($komisiPerPersonel[$nama])) {
                        $komisiPerPersonel[$nama] = [
                            'total_margin' => 0,
                            'total_komisi' => 0,
                        ];
                    }

                    $komisiPerPersonel[$nama]['total_margin'] += $komisi->margin;
                    $komisiPerPersonel[$nama]['total_komisi'] += $komisi->nilai_komisi;
                }
            @endphp

            @foreach($komisiPerPersonel as $nama => $data)
                <tr>
                    <td>{{ $nama }}</td>
                    <td>Rp {{ number_format($data['total_margin'], 2, ',', '.') }}</td>
                    <td>Rp {{ number_format($data['total_komisi'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('pm.komisi') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
