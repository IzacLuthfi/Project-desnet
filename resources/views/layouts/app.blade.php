<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>User Management</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap, Font & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <form id="formTambahProject">

 <script>

    // RESET form saat klik Batal
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
      btn.addEventListener('click', function () {
        const form = document.getElementById('formTambahProject');
        form.reset();

       
        // Sembunyikan notifikasi
        const notif = document.getElementById('notifAjax');
        notif.classList.add('d-none');
        notif.innerText = '';
      });
    });
</script>


  <!-- Style -->
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background-color: #f8f9fd;
    }

    .sidebar {
      width: 240px;
      background-color: #0284c7;
      color: white;
      position: fixed;
      height: 100vh;
      padding: 20px 16px;
    }

    .sidebar .role-label {
      background-color: #0369a1;
      padding: 8px 14px;
      border-radius: 8px;
      font-weight: 600;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-bottom: 30px;
    }

    .sidebar .nav-link {
      color: white;
      font-weight: 600;
      margin-bottom: 12px;
      display: block;
    }

    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      text-decoration: underline;
    }

    .topbar {
      margin-left: 240px;
      padding: 15px 30px;
      background-color: white;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .main-content {
      margin-left: 240px;
      padding: 30px;
    }

    .add-button {
      background-color: #6366f1;
      color: white;
      border: none;
      border-radius: 24px;
      padding: 8px 18px;
      font-weight: 600;
    }

    .card-box {
      background-color: white;
      border-radius: 16px;
      box-shadow: 0 1px 4px rgba(0,0,0,0.05);
      padding: 24px;
    }

    .btn-detail, .btn-edit, .btn-hapus {
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 14px;
    }

    .btn-detail { background-color: #22c55e; }
    .btn-edit { background-color: #f59e0b; }
    .btn-hapus { background-color: #ef4444; }

    .status-dot {
      height: 10px;
      width: 10px;
      border-radius: 50%;
      display: inline-block;
      margin-right: 6px;
    }

    .dot-success { background-color: #22c55e; }
    .dot-warning { background-color: #f59e0b; }

    .doc-box, .komisi-box {
      background-color: #ffffff;
      border: 1px solid #e0e7ff;
      border-radius: 14px;
      padding: 20px;
      text-align: center;
    }

    .doc-box i, .komisi-box i {
      font-size: 28px;
      margin-bottom: 8px;
      display: block;
    }

    .number {
      font-size: 24px;
      font-weight: bold;
      color: #4f46e5;
    }

    table th, table td {
      vertical-align: middle;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column" style="height: 100vh;">
  <div class="text-center mb-3">
    <img src="{{ asset('images/desnet-logo.png') }}" alt="Logo" class="img-fluid mb-2">
    <div class="role-label"><i class="bi bi-person-fill"></i> Admin</div>
  </div>

  <nav class="nav flex-column mb-auto">
    <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">Beranda</a>
    <a href="{{ route('kelola-user.index') }}" class="nav-link {{ Request::is('kelola-user*') ? 'active' : '' }}">Kelola User</a>
    <a href="{{ route('komisi.index') }}" class="nav-link {{ Request::is('komisi*') ? 'active' : '' }}">Komisi</a>
  </nav>

  <!-- Logout di paling bawah -->
  <div class="mt-auto p-3">
    <a href="#" id="btnLogout" class="btn btn-sm btn-dark w-100 d-flex align-items-center justify-content-center">
      <i class="bi bi-box-arrow-right me-1"></i> Logout
    </a>
  </div>
</div>


<!-- Topbar -->
<div class="topbar">
  <div><h6 class="mb-0 fw-bold">Manajemen Arsip Dokumen dan Komisi</h6></div>
  <div class="d-flex align-items-center gap-3">
    <i class="bi bi-calendar-event"></i>
    <i class="bi bi-question-circle"></i>
    <i class="bi bi-bell"></i>
  </div>
</div>

<!-- Main Content -->
<div class="main-content">

        <!-- Footer -->
        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary px-4" id="btnSimpanProject">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Tutup"></button>
      <h5 class="fw-bold mt-3">Apakah Anda yakin ingin menghapus data ini?</h5>
      <p class="text-muted">Tindakan ini akan menghapus data secara permanen.</p>

      <!-- Tidak gunakan <form>, pakai tombol biasa -->
      <div class="d-flex justify-content-center gap-2 mt-3">
        <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-dark" id="btnKonfirmasiHapus">Hapus</button>
      </div>
    </div>
  </div>
</div>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const hapusButtons = document.querySelectorAll('.btn-hapus');
    const modalHapus = new bootstrap.Modal(document.getElementById('modalKonfirmasiHapus'));
    let projectIdToDelete = null;
    let rowToDelete = null;

    // Tangkap klik tombol hapus
    hapusButtons.forEach(button => {
      button.addEventListener('click', function () {
        projectIdToDelete = this.dataset.id;
        rowToDelete = this.closest('tr');
        modalHapus.show();
      });
    });

    // Ketika tombol "Hapus" dalam modal diklik
    document.getElementById('btnKonfirmasiHapus').addEventListener('click', function () {
      if (!projectIdToDelete) return;

      fetch(`/projects/${projectIdToDelete}`, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}',
          'Accept': 'application/json'
        }
      })
      .then(response => {
        if (response.ok) {
          // Hapus baris dari tabel tanpa reload
          rowToDelete.remove();
          modalHapus.hide();
        } else {
          alert('Gagal menghapus data. Coba lagi.');
        }
      })
      .catch(error => {
        console.error(error);
        alert('Terjadi kesalahan!');
      });
    });
  });
</script>

  <!-- Modal Logout -->
<div class="modal fade" id="modalLogout" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="Close"></button>
      <h5 class="fw-bold mt-3">Apakah Anda yakin ingin keluar?</h5>
      <p class="text-muted">Tindakan ini akan mengeluarkan anda dari aplikasi</p>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <div class="d-flex justify-content-center gap-2 mt-3">
          <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-dark">Yakin</button>
        </div>
      </form>
    </div>
  </div>
</div>

 <script>
  document.addEventListener('DOMContentLoaded', function () {
    const logoutButton = document.getElementById('btnLogout');
    const logoutModal = new bootstrap.Modal(document.getElementById('modalLogout'));

    logoutButton.addEventListener('click', function (e) {
      e.preventDefault();
      logoutModal.show();
    });
  });
</script>

</div>
</body>
</html>
