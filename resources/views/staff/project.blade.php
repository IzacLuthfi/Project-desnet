@extends('layouts.staff')

@section('title', 'Proyek Staff')

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
    .col-status { width: 140px; }
    .col-nilai { width: 120px; }
    .col-personel { width: 200px; }
    .col-aksi { width: 170px; }
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
          <th class="col-aksi">Aksi</th>
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
            <td>
              <!-- Tombol Detail -->
              <a href="{{ route('staff.project.show', $project->id) }}" 
                class="btn btn-sm btn-success text-white" 
                style="background-color: #11df11;">
                Detail
              </a>

              <!-- Tombol Tambah dengan modal -->
              <button class="btn btn-sm btn-primary text-white" 
                style="background-color: #5051f9;" 
                data-bs-toggle="modal" 
                data-bs-target="#modalTambah{{ $project->id }}">
                Tambah
              </button>
              <!-- Modal Tambah Dokumen -->
              <div class="modal fade" id="modalTambah{{ $project->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $project->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="modalLabel{{ $project->id }}">
                        Input Dokumen Proyek: {{ $project->judul }}
                      </h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('project.documents.store', $project->id) }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      <div class="modal-body">
                        <div class="mb-3">
                        <label for="jenis_dokumen" class="form-label">Jenis Dokumen</label>
                        <input type="text" name="jenis_dokumen" class="form-control" placeholder="Masukkan Jenis Dokumen" required>
                      </div>
                        <div class="mb-3">
                          <label for="dokumen" class="form-label">Upload Dokumen</label>
                          <input type="file" name="dokumen" class="form-control" required>
                        </div>
                        <div class="mb-3">
                          <label for="keterangan" class="form-label">Keterangan (opsional)</label>
                          <textarea name="keterangan" class="form-control" rows="3"></textarea>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
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
