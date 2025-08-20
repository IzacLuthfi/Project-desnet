@extends('layouts.app')

@section('title', 'Detail Proyek')

@section('content')
<h4 class="mb-4 fw-bold">Project: {{ $project->judul }}</h4>

<div class="table-responsive">
  <table class="table table-bordered bg-white">
    <thead class="table-light">
      <tr>
        <th>No</th>
        <th>Jenis Dokumen</th>
        <th>Nama File</th>
        <th>Tanggal Unggah</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($project->projectDocuments as $doc)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $doc->jenis_dokumen }}</td>
          <td>{{ $doc->nama_asli ?? basename($doc->file_path) }}</td>
          <td>{{ \Carbon\Carbon::parse($doc->created_at)->translatedFormat('d F Y') }}</td>
          <td>
            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalKeterangan{{ $doc->id }}">
              Keterangan
            </button>
              <button 
                  class="btn btn-sm btn-danger btn-hapus-dokumen" 
                  data-id="{{ $doc->id }}">
                  Hapus
              </button>
                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-success">Unduh</a>
          </td>

            <!-- Modal -->
            <div class="modal fade" id="modalKeterangan{{ $doc->id }}" tabindex="-1" aria-labelledby="labelKeterangan{{ $doc->id }}" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Keterangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    {{ $doc->keterangan ?? 'Tidak ada keterangan.' }}
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                  </div>
                </div>
              </div>
            </div>  
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="5" class="text-center text-muted">Tidak ada dokumen.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
        <!-- Modal Konfirmasi Hapus -->
      <div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content text-center p-4">
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
            <h5 class="fw-bold mt-3">Apakah Anda yakin ingin menghapus data ini?</h5>
            <p class="text-muted">Tindakan ini akan menghapus data secara permanen.</p>
           <div class="modal-footer justify-content-center">
              <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">
                Batal
              </button>
              <button type="button" class="btn btn-dark" id="btnKonfirmasiHapus">
                Hapus
              </button>
            </div>
          </div>
        </div>
      </div>
      <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali</a>
    </div>
    @endsection
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const hapusButtons = document.querySelectorAll('.btn-hapus-dokumen');
        const modalHapus = new bootstrap.Modal(document.getElementById('modalKonfirmasiHapus'));
        let documentIdToDelete = null;
        let rowToDelete = null;

        hapusButtons.forEach(button => {
            button.addEventListener('click', function () {
                documentIdToDelete = this.dataset.id;
                rowToDelete = this.closest('tr');
                modalHapus.show();
            });
        });

        document.getElementById('btnKonfirmasiHapus').addEventListener('click', function () {
            if (!documentIdToDelete) return;

            fetch(`/documents/${documentIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (response.ok) {
                    rowToDelete.remove();

                    // 🔥 Reindex nomor urut
                    const rows = document.querySelectorAll("table tbody tr");
                    rows.forEach((row, index) => {
                        const nomorCell = row.querySelector("td:first-child");
                        if (nomorCell) {
                            nomorCell.textContent = index + 1;
                        }
                    });

                    modalHapus.hide();
                } else {
                    alert('Gagal menghapus dokumen. Coba lagi.');
                }
            })
            .catch(error => {
                console.error(error);
                alert('Terjadi kesalahan!');
            });
        });
    });
    </script>
    @endpush

