@extends('layouts.staff')

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
        <!-- Tombol Keterangan -->
        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#modalKeterangan{{ $doc->id }}">
          Keterangan
        </button>

        <!-- Modal Keterangan -->
        <div class="modal fade" id="modalKeterangan{{ $doc->id }}" tabindex="-1" aria-labelledby="labelKeterangan{{ $doc->id }}" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="labelKeterangan{{ $doc->id }}">Keterangan Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
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

        <!-- Tombol Unduh -->
        <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-sm btn-success">
          Unduh
        </a>
      </td>
    </tr>
  @empty
    <tr>
      <td colspan="5" class="text-center text-muted">Tidak ada dokumen.</td>
    </tr>
  @endforelse
</tbody>

    </table>
      {{-- Tombol Kembali --}}
  <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
  </div>
@endsection
