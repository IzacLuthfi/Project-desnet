@extends('layouts.app')

@section('title', 'Komisi admin')

@section('content')
  <!-- Tombol Filter -->
  <div class="mb-3">
      <a href="{{ route('admin.komisi.total.bulanan') }}" class="btn btn-warning">Lihat Total Komisi Per Bulan</a>  
      <a href="{{ route('admin.komisi.total') }}" class="btn btn-primary">Lihat Total Komisi</a>
  </div>

  <h4 class="fw-bold mb-4">Komisi Bulanan</h4>
  
  <!-- Tabel Komisi -->
<div class="table-responsive">
  <table class="table table-bordered table-border-black bg-white table-fixed">
    <!-- colgroup menentukan lebar “paten” tiap kolom -->
    <colgroup>
      <col style="width: 60px;">         <!-- No (fixed px) -->
      <col style="width: 40%;">         <!-- Judul Proyek (persentase) -->
      <col style="width: 35%;">         <!-- Personel -->
      <col style="width: 120px;">       <!-- Nilai Proyek (fixed px) -->
      <col style="width: 100px;">       <!-- Aksi (fixed px) -->
    </colgroup>

    <thead class="table-light table-shadded text-center">
      <tr>
        <th class="col-no">No</th>
        <th>Judul Proyek</th>
        <th class="col-personel">Personel</th>
        <th class="col-nilai">Nilai Proyek</th>
        <th class="col-aksi">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($projects as $project)
      <tr>
        <td class="col-no">{{ $loop->iteration }}</td>
        <td title="{{ $project->judul }}">{{ $project->judul }}</td>
        <td class="col-personel" title="{{ $project->projectPersonel->map(fn($p)=> $p->user ? $p->user->name : '(User tidak ditemukan)')->join(', ') }}">
          {{ $project->projectPersonel->map(function($p) {
              return $p->user ? $p->user->name : '(User tidak ditemukan)';
          })->join(', ') ?: '-' }}
        </td>
        <td class="col-nilai">Rp {{ number_format($project->nilai ?? 0, 0, ',', '.') }}</td>
        <td class="col-aksi">
          <a href="{{ route('admin.komisi.show', $project->id) }}" class="btn btn-sm btn-info">Detail</a>
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

<style>
  /* Buat table fixed layout supaya colgroup respected */
  .table-fixed {
    table-layout: fixed;
    width: 100%;
    /* Optional: agar horizontal scroll muncul pada layar kecil */
    min-width: 700px;
  }

  /* Potong teks yang kepanjangan */
  .table-fixed th,
  .table-fixed td {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: middle;
  }

  /* Alignment khusus */
  .col-no { text-align: center; }
  .col-personel { text-align: left; }
  .col-nilai { text-align: right; white-space: nowrap; }
  .col-aksi { text-align: center; }

  /* Responsive: kalau layar kecil, biarkan tabel horizontal scroll */
  .table-responsive {
    overflow-x: auto;
  }
</style>