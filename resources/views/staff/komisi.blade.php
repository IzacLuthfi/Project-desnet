@extends('layouts.staff')

@section('title', 'Komisi staff')

@section('content')
  <!-- Tombol Filter -->
  <div class="mb-3">
      <a href="{{ route('staff.komisi.total.bulanan') }}" class="btn btn-warning">Lihat Total Komisi Per Bulan</a>  
      <a href="{{ route('staff.komisi.total') }}" class="btn btn-primary">Lihat Total Komisi</a>
  </div>

  <h4 class="fw-bold mb-4">Komisi Bulanan</h4>
    <!-- Tabel Komisi -->
  <div class="table-responsive">
    <table class="table table-bordered bg-white">
      <thead class="table-light text-center">
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
          <td>
            {{ $project->projectPersonel->map(function($p) {
                return $p->user ? $p->user->name : '(User tidak ditemukan)';
            })->join(', ') ?: '-' }}
          </td>
          <td class="text-end"> {{-- nilai proyek rata kanan --}}
            Rp {{ number_format($project->nilai ?? 0, 0, ',', '.') }}
          </td>
          <td>
            <a href="{{ route('staff.komisi.show', $project->id) }}" class="btn btn-sm btn-success">Detail</a>
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
