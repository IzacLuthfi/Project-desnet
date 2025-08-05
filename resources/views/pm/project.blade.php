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
              <!-- Tombol Detail -->
              <a href="{{ route('projects.show', $project->id) }}" 
                class="btn btn-sm btn-success text-white" 
                style="background-color: #11df11;">
                Detail
              </a>

              <!-- Tombol Tambah dengan modal -->
              <button class="btn btn-sm btn-primary text-white" 
                style="background-color: #0183f0;" 
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
                          <select name="jenis_dokumen" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Jenis Dokumen --</option>
                            <option value="User Acceptance Testing (UAT)">User Acceptance Testing (UAT)</option>
                            <option value="Berita Acara Serah Terima (BAST)">Berita Acara Serah Terima (BAST)</option>
                          </select>
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
