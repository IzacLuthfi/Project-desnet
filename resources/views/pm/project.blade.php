@extends('layouts.pm')

@section('title', 'Proyek pm')

@section('content')
  <h4 class="mb-4 fw-bold">Work Order</h4>

  <div class="table-responsive">
    <table class="table table-bordered bg-white">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Judul Proyek</th>
          <th>Status</th>
          <th>Nilai Proyek</th>
          <th>Anggota</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($projects as $project)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $project->judul }}</td>
            <td>
              <span class="text-success fw-semibold">● {{ $project->status_dokumen ?? 'Selesai' }}</span>
            </td>
            <td>{{ number_format($project->nilai ?? 0, 0, ',', '.') }}</td>
            <td>
              {{ $project->projectPersonel->pluck('nama')->take(3)->join(', ') }}
              {{ $project->projectPersonel->count() > 3 ? ',...' : '' }}
            </td>
            <td>
              <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-success">Detail</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted">Tidak ada proyek.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
@endsection
