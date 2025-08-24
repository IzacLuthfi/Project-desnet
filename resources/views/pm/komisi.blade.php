@extends('layouts.pm')

@section('title', 'Komisi PM')

@section('content')
  <!-- Tombol Filter -->
  <div class="mb-3">
      <a href="{{ route('pm.komisi.total.bulanan') }}" class="btn btn-warning">Lihat Total Komisi Per Bulan</a>
      <a href="{{ route('pm.komisi.total') }}" class="btn btn-primary">Lihat Total Komisi</a>
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
            <a href="{{ route('pm.komisi.show', $project->id) }}" class="btn btn-sm btn-success">Detail</a>

            {{-- Input baru --}}
            <button 
              class="btn btn-sm btn-warning btn-open-komisi" 
              data-mode="create"
              data-project="{{ $project->id }}"
              data-judul="{{ $project->judul }}"
              data-nilai="{{ $project->nilai }}"
              data-pm='@json([
                  "id"   => $project->pm_id,
                  "nama" => $project->pm?->name ?? "(PM tidak ditemukan)"
              ])'
              data-personel='@json($project->projectPersonel->map(function($p){
                  return [
                      "id"   => $p->id,                         // project_personel_id
                      "nama" => $p->user->name ?? "(User tidak ditemukan)"
                  ];
              }))'>
              Input Komisi
            </button>

            {{-- Edit komisi yang sudah ada --}}
            <button 
                class="btn btn-sm btn-info btn-open-komisi"
                data-mode="edit"
                data-project="{{ $project->id }}"
                data-judul="{{ $project->judul }}"
                data-nilai="{{ $project->nilai }}"
                data-pm='{{ json_encode([
                    "id"         => $project->pm_id,
                    "nama"       => $project->pm?->name ?? "(PM tidak ditemukan)",
                    "persentase" => optional($project->komisiPm)->persentase ?? 0,
                    "komisi_id"  => optional($project->komisiPm)->id
                ]) }}'
                data-personel='{{ json_encode(
                    $project->projectPersonel->map(function($p){
                        return [
                            "id"         => $p->id,
                            "nama"       => $p->user->name ?? "(User tidak ditemukan)",
                            "persentase" => optional($p->komisi)->persentase ?? 0,
                            "komisi_id"  => optional($p->komisi)->id
                        ];
                    })
                ) }}'>
                Edit
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
      <form id="formKomisi" method="POST" action="{{ route('komisi.store') }}">
        @csrf
        <input type="hidden" id="formMethod" name="_method" value="POST">
        <input type="hidden" name="project_id" id="project_id">

        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="modalKomisiLabel">Input Komisi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div id="formAlert" class="alert alert-danger d-none"></div>
        <div class="modal-body">
          <!-- Info Proyek -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Judul Proyek:</label>
            <div id="judul_proyek" class="fw-bold"></div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Nilai Proyek:</label>
            <div id="nilai_proyek" class="fw-bold text-dark"></div>
          </div>

          <!-- Input Margin -->
          <div class="mb-4">
            <label class="form-label fw-semibold">Input Nilai Margin:</label>
            <input type="text" name="margin" id="marginInput" class="form-control" required>
          </div>

          <!-- Komisi PM -->
          <h6 class="fw-bold mb-3">Komisi PM</h6>
          <div id="list_pm"></div>

          <!-- Komisi Personel -->
          <h6 class="fw-bold mb-3">Komisi Personel</h6>
          <div id="list_personel"></div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Error -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title fw-bold" id="errorModalLabel">
          <i class="bi bi-exclamation-triangle-fill me-2 text-white"></i> Validasi Komisi
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p class="mb-2 text-dark" id="errorModalBody"></p>
        <small class="text-muted">Silakan periksa kembali input komisi Anda.</small>
      </div>
      <div class="modal-footer border-0">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form         = document.getElementById('formKomisi');
  const methodInput  = document.getElementById('formMethod');
  const marginInput  = document.getElementById('marginInput'); // margin input
  const formAlert    = document.getElementById('formAlert');
  
  function openModal({mode, projectId, judul, nilai, pm, personel}) {
    // Set action & method: create vs edit
    if (mode === 'edit') {
      form.action     = `/komisi/${projectId}`;
      methodInput.value = 'PUT';
    } else {
      form.action     = `{{ route('komisi.store') }}`;
      methodInput.value = 'POST';
    }

    document.getElementById('project_id').value = projectId;
    document.getElementById('judul_proyek').textContent = judul;
    document.getElementById('nilai_proyek').textContent = parseFloat(nilai).toLocaleString('id-ID');

    // === Render PM ===
    const pmBox = document.getElementById('list_pm');
    pmBox.innerHTML = `
      <input type="hidden" name="komisi_pm_id" value="${pm.komisi_id ?? ''}">
      <div class="row align-items-center mb-3">
        <div class="col-md-4">
          <label class="form-label mb-0">Project Manager</label>
          <input type="text" class="form-control" value="${pm.nama}" readonly>
        </div>
        <div class="col-md-3">
          <label class="form-label mb-0">Komisi:</label>
          <div class="input-group">
            <input type="number" name="komisi_pm[${pm.id}]" step="0.01" class="form-control"
                   value="${mode === 'edit' ? (pm.persentase ?? 0) : ''}" required>
            <span class="input-group-text">%</span>
          </div>
        </div>
      </div>
    `;

    // === Render Personel ===
    const personelBox = document.getElementById('list_personel');
    personelBox.innerHTML = '';
    personel.forEach((p, i) => {
      personelBox.innerHTML += `
        <input type="hidden" name="komisi_id[${p.id}]" value="${p.komisi_id ?? ''}">
        <div class="row align-items-center mb-3">
          <div class="col-md-4">
            <label class="form-label mb-0">Personel ${i+1}</label>
            <input type="text" class="form-control" value="${p.nama}" readonly>
          </div>
          <div class="col-md-3">
            <label class="form-label mb-0">Komisi:</label>
            <div class="input-group">
              <input type="number" name="komisi[${p.id}]" step="0.01" class="form-control"
                     value="${mode === 'edit' ? (p.persentase ?? 0) : ''}" required>
              <span class="input-group-text">%</span>
            </div>
          </div>
        </div>
      `;
    });

    new bootstrap.Modal(document.getElementById('modalKomisi')).show();
  }

  // Satu handler untuk tombol Input & Edit
  document.querySelectorAll('.btn-open-komisi').forEach(btn => {
    btn.addEventListener('click', function () {
      openModal({
        mode: this.dataset.mode,
        projectId: this.dataset.project,
        judul: this.dataset.judul,
        nilai: this.dataset.nilai,
        pm: JSON.parse(this.dataset.pm),
        personel: JSON.parse(this.dataset.personel)
      });
    });
  });

  function showError(message) {
    formAlert.textContent = message;
    formAlert.classList.remove('d-none');
  }

  function hideError() {
    formAlert.classList.add('d-none');
    formAlert.textContent = '';
  }

  // === Format ribuan untuk input margin ===
  marginInput.addEventListener('input', function() {
    let value = this.value.replace(/\./g, '');
    if (!isNaN(value) && value !== '') {
      this.value = parseInt(value, 10).toLocaleString('id-ID');
    } else {
      this.value = '';
    }
  });

  form.addEventListener('submit', function(e) {
    hideError();

    let totalPersen = 0;
    form.querySelectorAll('input[name^="komisi_pm"], input[name^="komisi["]').forEach(input => {
      let val = parseFloat(input.value) || 0;
      totalPersen += val;
    });

    if (totalPersen !== 100) {
      e.preventDefault();

      // isi pesan error ke modal
      document.getElementById('errorModalBody').textContent =
        `Total persentase komisi harus 100%. Saat ini tercatat ${totalPersen}%.`;

      // tampilkan modal error
      new bootstrap.Modal(document.getElementById('errorModal')).show();
      return false;
    }

    // bersihkan titik ribuan sebelum submit
    marginInput.value = marginInput.value.replace(/\./g, '');
  });
});
</script>
@endpush