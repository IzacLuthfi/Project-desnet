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
          <td>
              {{ $project->projectPersonel->map(function($p) {
                  return $p->user ? $p->user->name : '(User tidak ditemukan)';
              })->join(', ') ?: '-' }}
          </td>
          <td>{{ number_format($project->nilai ?? 0, 0, ',', '.') }}</td>
          <td>
            <a href="{{ route('komisi.show', $project->id) }}" class="btn btn-sm btn-success">Detail</a>
            <button 
              class="btn btn-sm btn-warning btn-input-komisi" 
              data-project="{{ $project->id }}"
              data-judul="{{ $project->judul }}"
              data-nilai="{{ $project->nilai }}"
              data-personel='@json($project->projectPersonel->map(function($p) {
                  return [
                      'id' => $p->id,
                      'nama' => $p->user->name ?? '(User tidak ditemukan)'
                  ];
              }))'>
              Input Komisi
            </button>
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

  <!-- Modal Input Komisi -->
  <div class="modal fade" id="modalKomisi" tabindex="-1" aria-labelledby="modalKomisiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <form action="{{ route('komisi.store') }}" method="POST">
          @csrf
          <input type="hidden" name="project_id" id="project_id">

          <div class="modal-header">
            <h5 class="modal-title" id="modalKomisiLabel">Input Komisi</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <div class="mb-3">
              <label>Judul Proyek</label>
              <input type="text" id="judul_proyek" class="form-control" readonly>
            </div>
            <div class="mb-3">
              <label>Nilai Proyek</label>
              <input type="text" id="nilai_proyek" class="form-control" readonly>
            </div>
            <div class="mb-3">
              <label>Nilai Margin</label>
              <input type="number" step="0.01" name="margin" class="form-control" required>
            </div>

            <h6>Komisi Personel</h6>
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Persentase (%)</th>
                </tr>
              </thead>
              <tbody id="list_personel"></tbody>
            </table>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-input-komisi').forEach(btn => {
        btn.addEventListener('click', function () {
            let projectId = this.dataset.project;
            let judul = this.dataset.judul;
            let nilai = this.dataset.nilai;
            let personel = JSON.parse(this.dataset.personel);

            document.getElementById('project_id').value = projectId;
            document.getElementById('judul_proyek').value = judul;
            document.getElementById('nilai_proyek').value = parseFloat(nilai).toLocaleString();

            let tbody = document.getElementById('list_personel');
            tbody.innerHTML = '';

            personel.forEach(p => {
                tbody.innerHTML += `
                    <tr>
                        <td>${p.nama}</td>
                        <td>
                            <input type="number" name="komisi[${p.id}]" step="0.01" class="form-control" required>
                        </td>
                    </tr>
                `;
            });

            new bootstrap.Modal(document.getElementById('modalKomisi')).show();
        });
    });
});
</script>
@endpush
