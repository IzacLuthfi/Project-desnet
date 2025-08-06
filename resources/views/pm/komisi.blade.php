@extends('layouts.pm')

@section('title', 'Komisi pm')

@section('content')
  <!-- Tombol Filter -->
  <div class="mb-3">
    <a href="#" class="btn btn-primary me-2">Perbulan</a>
    <a href="#" class="btn btn-outline-primary">Pertahun</a>
  </div>
  <h4 class="fw-bold mb-4">Komisi Bulanan</h4>
    <!-- Tabel Komisi -->
  <div class="table-responsive">
    <table class="table table-bordered bg-white">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Judul Proyek</th>
          <th>Personel</th>
          <th>Nilai Proyek</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($projects as $project)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $project->judul }}</td>
          <td>{{ $project->projectPersonel->pluck('nama')->join(', ') }}</td>
          <td>{{ number_format($project->nilai ?? 0, 0, ',', '.') }}</td>
          <td>
            <a href="#" class="btn btn-sm btn-success">Detail</a>
            <a href="#" class="btn btn-sm btn-warning">Edit</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted">Tidak ada data komisi.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
