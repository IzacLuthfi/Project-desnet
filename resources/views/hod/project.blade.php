@extends('layouts.hod')

@section('title', 'Proyek HOD')

@section('content')
  <h4 class="mb-4 fw-bold">Work Order</h4>
  
  <style>
  .status-dot {
      height: 10px;
      width: 10px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 6px;
    }

  .dot-success { background-color: #22c55e; }
  .dot-warning { background-color: #f59e0b; }

  .table-fixed {
      table-layout: fixed;
      width: 100%;
    }
    .table-fixed th,
    .table-fixed td {
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      vertical-align: middle;
    }

    .col-no { width: 40px; }
    .col-judul { width: 180px; }
    .col-status { width: 180px; }
    .col-nilai { width: 150px; }
    .col-personel { width: 300px; }
  </style>

  <div class="table-responsive">
    <table class="table table-bordered bg-white">
      <thead class="table-light">
        <tr>
          <th class="col-no">No</th>
          <th class="col-judul">Judul Proyek</th>
          <th class="col-status">Status Dokumen</th>
          <th class="col-nilai">Nilai Proyek</th>
          <th class="col-personel">Personel</th>
        </tr>
      </thead>
      <tbody>
        @php $projects = $projects ?? collect(); @endphp
        @forelse ($projects as $project)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $project->judul }}</td>
            <td>
              <span class="status-dot {{ $project->status_dokumen === 'Sudah Diajukan' ? 'dot-success' : 'dot-warning' }}"></span>
              {{ $project->status_dokumen ?? 'Belum Diajukan' }}
            </td>
            <td>{{ number_format($project->nilai ?? 0, 0, ',', '.') }}</td>
            <td>
              {{ $project->projectPersonel->map(function($p) {
                  return $p->user ? $p->user->name : '(User tidak ditemukan)';
              })->join(', ') ?: '-' }}
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
