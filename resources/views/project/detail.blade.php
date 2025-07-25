@extends('layouts.app')

@section('content')
<div class="container">
    <h4 class="fw-bold mb-4">Detail Proyek</h4>

    <div class="mb-3">
        <strong>Judul:</strong> {{ $project->judul }}
    </div>
    <div class="mb-3">
        <strong>Nilai:</strong> Rp {{ number_format($project->nilai, 0, ',', '.') }}
    </div>
    <div class="mb-3">
        <strong>Project Manager:</strong> {{ $project->pm }}
    </div>
    <div class="mb-3">
        <strong>Status:</strong> {{ $project->status }}
    </div>

    <h5 class="mt-4">Daftar Personel</h5>
    <ul>
        @foreach($project->personel as $person)
            <li>{{ $person->nama }} - {{ $person->role }}</li>
        @endforeach
    </ul>

    <a href="{{ route('project.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
