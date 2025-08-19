@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Detail Komisi - {{ $project->nama_project }}</h2>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama Personel</th>
                <th>Margin</th>
                <th>Persentase (%)</th>
                <th>Nilai Komisi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($project->komisi as $komisi)
                <tr>
                    <td>{{ $komisi->projectPersonel->user->name ?? '-' }}</td>
                    <td>Rp {{ number_format($komisi->margin, 2, ',', '.') }}</td>
                    <td>{{ $komisi->persentase }}%</td>
                    <td>Rp {{ number_format($komisi->nilai_komisi, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <a href="{{ route('komisi.index') }}" class="btn btn-secondary">Kembali</a>
</div>
@endsection
