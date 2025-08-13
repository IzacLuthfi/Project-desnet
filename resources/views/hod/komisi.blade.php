@extends('layouts.hod')

@section('title', 'Komisi')

@section('content')
  <!-- Tombol Filter -->
  <div class="mb-3">
      <a href="{{ route('hod.komisi.total.bulanan') }}" class="btn btn-warning">Lihat Total Komisi Per Bulan</a> 
      <a href="{{ route('hod.komisi.total') }}" class="btn btn-primary">Lihat Total Komisi</a>
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
          <th>Status Komisi</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($projects as $project)
        <tr id="row-{{ $project->id }}">
          <td>{{ $loop->iteration }}</td>
          <td>{{ $project->judul }}</td>
          <td>
            {{ $project->projectPersonel->map(function($p) {
              return $p->user ? $p->user->name : '(User tidak ditemukan)';
            })->join(', ') ?: '-' }}
          </td>
          <td>{{ number_format($project->nilai ?? 0, 0, ',', '.') }}</td>
          <td class="status-cell">
            @if($project->status_komisi == 'Disetujui')
              <span class="badge bg-success">Disetujui</span>
            @else
              <span class="badge bg-warning">Belum Disetujui</span>
            @endif
          </td>
          <td>
            <a href="{{ route('hod.komisi.show', $project->id) }}" class="btn btn-sm btn-success">Detail</a>
            @if($project->status_komisi == 'Disetujui')
              <button class="btn btn-sm btn-danger btn-batal-verifikasi" data-id="{{ $project->id }}">Batalkan Verifikasi</button>
            @else
              <button class="btn btn-sm btn-warning btn-verifikasi" data-id="{{ $project->id }}">Verifikasi</button>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted">Tidak ada data komisi.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Modal Konfirmasi -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center p-4 position-relative" style="border-radius: 12px;">
        <button type="button" class="btn-close position-absolute" style="top: 12px; right: 12px;" data-bs-dismiss="modal"></button>
        <h4 class="fw-bold mb-4 mt-3 modal-message"></h4>
        <div class="d-flex justify-content-center gap-3">
          <button type="button" class="btn btn-outline-dark px-4 py-2" style="border-radius: 8px;" data-bs-dismiss="modal">Batal</button>
          <button type="button" class="btn btn-dark px-4 py-2 modal-confirm-btn" style="border-radius: 8px;">Ya</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    let confirmAction = null;
    let confirmProjectId = null;

    // Event Delegation untuk semua tombol di tabel
    document.addEventListener("click", function (e) {
        // Klik tombol Verifikasi
        if (e.target.classList.contains("btn-verifikasi")) {
            confirmProjectId = e.target.getAttribute("data-id");
            confirmAction = "verifikasi";
            document.querySelector(".modal-message").textContent = "Apakah Anda yakin ingin menyetujui komisi tersebut?";
            document.querySelector(".modal-confirm-btn").textContent = "Ya, Setujui";
            document.querySelector(".modal-confirm-btn").classList.remove("btn-danger");
            document.querySelector(".modal-confirm-btn").classList.add("btn-dark");
            new bootstrap.Modal(document.getElementById("confirmModal")).show();
        }

        // Klik tombol Batalkan Verifikasi
        if (e.target.classList.contains("btn-batal-verifikasi")) {
            confirmProjectId = e.target.getAttribute("data-id");
            confirmAction = "batalkan";
            document.querySelector(".modal-message").textContent = "Apakah Anda yakin ingin membatalkan verifikasi komisi tersebut?";
            document.querySelector(".modal-confirm-btn").textContent = "Ya, Batalkan";
            document.querySelector(".modal-confirm-btn").classList.remove("btn-dark");
            document.querySelector(".modal-confirm-btn").classList.add("btn-dark");
            new bootstrap.Modal(document.getElementById("confirmModal")).show();
        }
    });

    // Klik tombol konfirmasi modal
    document.querySelector(".modal-confirm-btn").addEventListener("click", function () {
        if (!confirmProjectId || !confirmAction) return;

        let url = confirmAction === "verifikasi"
            ? `/hod/komisi/${confirmProjectId}/verifikasi`
            : `/hod/komisi/${confirmProjectId}/batalkan`;

        fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Content-Type": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Langsung update UI tanpa nunggu status dari server
                if (confirmAction === "verifikasi") {
                    setRowVerified(confirmProjectId);
                } else {
                    setRowUnverified(confirmProjectId);
                }
                bootstrap.Modal.getInstance(document.getElementById("confirmModal")).hide();
            }
        });
    });

    // Fungsi untuk ubah baris jadi "Disetujui"
    function setRowVerified(id) {
        let statusCell = document.querySelector(`#row-${id} .status-cell`);
        let actionCell = document.querySelector(`#row-${id} td:last-child`);
        statusCell.innerHTML = `<span class="badge bg-success">Disetujui</span>`;
        actionCell.innerHTML = `<a href="/hod/komisi/${id}" class="btn btn-sm btn-success">Detail</a>
                                <button class="btn btn-sm btn-danger btn-batal-verifikasi" data-id="${id}">Batalkan Verifikasi</button>`;
    }

    // Fungsi untuk ubah baris jadi "Belum Disetujui"
    function setRowUnverified(id) {
        let statusCell = document.querySelector(`#row-${id} .status-cell`);
        let actionCell = document.querySelector(`#row-${id} td:last-child`);
        statusCell.innerHTML = `<span class="badge bg-warning">Belum Disetujui</span>`;
        actionCell.innerHTML = `<a href="/hod/komisi/${id}" class="btn btn-sm btn-success">Detail</a>
                                <button class="btn btn-sm btn-warning btn-verifikasi" data-id="${id}">Verifikasi</button>`;
    }
});

</script>
@endpush
