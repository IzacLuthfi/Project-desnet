@extends('layouts.app') {{-- Pastikan layout utama ada --}}

@section('content')
<div class="container">
    <h3 class="mb-4">Daftar Proyek</h3>

    <!-- Tombol Tambah Proyek -->
    <a href="{{ route('projects.create') }}" class="btn btn-primary mb-3">
        + Tambah Proyek
    </a>

    <!-- Tabel Daftar Proyek -->
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Nilai</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($projects as $project)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $project->judul }}</td>
                    <td>Rp{{ number_format($project->nilai, 0, ',', '.') }}</td>
                    <td>{{ ucfirst($project->status) }}</td>
                    <td>
                        <!-- Tombol Detail -->
                        <button 
                            class="btn btn-success btn-sm btn-detail"
                            data-id="{{ $project->id }}"
                            data-judul="{{ $project->judul }}"
                            data-nilai="{{ $project->nilai }}"
                            data-status="{{ $project->status }}"
                            data-personel='@json($project->personel->map(fn($p) => $p->role . ": " . $p->nama))'
                            data-bs-toggle="modal"
                            data-bs-target="#modalDetailProyek"
                        >
                            Detail
                        </button>

                        <!-- Tombol Edit -->
                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <!-- Tombol Hapus -->
                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus proyek ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if ($projects->isEmpty())
                <tr>
                    <td colspan="5" class="text-center">Belum ada data proyek.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Proyek -->
<div class="modal fade" id="modalDetailProyek" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detail Dokumen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <p><strong>Judul Proyek:</strong> <span id="detail-judul"></span></p>
                <p><strong>Nilai Proyek:</strong> <span id="detail-nilai"></span></p>
                <p><strong>Personel:</strong></p>
                <ul id="detail-personel"></ul>
                <p><strong>Status Progress:</strong> <span id="detail-status"></span></p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.btn-detail');

    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('detail-judul').textContent = this.dataset.judul;
            document.getElementById('detail-nilai').textContent = 'Rp ' + Number(this.dataset.nilai).toLocaleString('id-ID');
            document.getElementById('detail-status').textContent = this.dataset.status;

            const personelList = JSON.parse(this.dataset.personel);
            const listElement = document.getElementById('detail-personel');
            listElement.innerHTML = '';

            personelList.forEach(person => {
                const li = document.createElement('li');
                li.textContent = person;
                listElement.appendChild(li);
            });
        });
    });
});
</script>
@endpush
