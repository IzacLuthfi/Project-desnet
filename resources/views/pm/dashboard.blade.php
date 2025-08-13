<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard PM</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Bootstrap, Font & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  
  <!-- Tambahkan Pusher & Laravel Echo -->
  <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.3/dist/echo.iife.js"></script>

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
      position: relative;
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

    .btn-info { background-color: #11df11; }
    .btn-warning { background-color: #5051f9; }
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

    /* Notification Styles */
    .notification-dropdown {
      position: absolute;
      top: 100%;
      right: 0;
      width: 380px;
      max-height: 500px;
      background: white;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      display: none;
      z-index: 1000;
      margin-top: 10px;
    }

    .notification-dropdown.show {
      display: block;
    }

    .notification-header {
      padding: 16px;
      border-bottom: 1px solid #e5e7eb;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .notification-item {
      padding: 16px;
      border-bottom: 1px solid #f3f4f6;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .notification-item:hover {
      background-color: #f9fafb;
    }

    .notification-item.unread {
      background-color: #eff6ff;
    }

    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background-color: #ef4444;
      color: white;
      font-size: 11px;
      padding: 2px 6px;
      border-radius: 10px;
      min-width: 18px;
      text-align: center;
    }

    .notification-empty {
      padding: 40px;
      text-align: center;
      color: #6b7280;
    }

    .notification-icon {
      position: relative;
      cursor: pointer;
    }

    .notification-body {
      max-height: 400px;
      overflow-y: auto;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar d-flex flex-column" style="height: 100vh;">
  <div class="text-center mb-3">
    <img src="{{ asset('images/desnet-logo.png') }}" alt="Logo" class="img-fluid mb-2">
    <div class="role-label" id="openAccountModal" style="cursor:pointer;">
      <i class="bi bi-person-fill"></i> {{ Auth::user()->role }}
    </div>
  </div>

  <nav class="nav flex-column mb-auto">
    <a href="{{ route('pm.dashboard') }}" class="nav-link {{ Request::is('pm/dashboard') ? 'active' : '' }}">Beranda</a>
    <a href="{{ route('pm.project') }}" class="nav-link {{ Request::is('pm/project') ? 'active' : '' }}">Project</a>
    <a href="{{ route('pm.komisi') }}" class="nav-link {{ Request::is('pm/komisi') ? 'active' : '' }}">Komisi</a>
  </nav>

  <!-- Logout di paling bawah -->
  <div class="mt-auto p-3">
    <a href="#" id="btnLogout" class="btn btn-sm btn-dark w-100 d-flex align-items-center justify-content-center">
      <i class="bi bi-box-arrow-right me-1"></i> Logout
    </a>
  </div>
</div>


<div class="topbar d-flex justify-content-between align-items-center">
    <h6 class="mb-0 fw-bold">Manajemen Arsip Dokumen dan Komisi</h6>
    <li class="nav-item dropdown list-unstyled m-0">
            <a id="pmNotificationDropdown" class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown">
        <i class="bi bi-bell" style="font-size: 1.5rem;"></i>
        <span id="pmNotificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none">
            0
        </span>
    </a>
        <ul class="dropdown-menu dropdown-menu-end p-0" style="width: 320px; max-height: 400px; overflow-y: auto;">
        <li class="p-2 border-bottom fw-bold">Notifikasi</li>
        <div id="pmNotificationList">
            <li class="p-3 text-muted text-center">Tidak ada notifikasi baru</li>
        </div>
        <li class="text-center border-top p-2">
            <button id="markAllPMRead" class="btn btn-sm btn-outline-primary rounded-pill">
                <i class="bi bi-check2-all"></i> Tandai Semua Dibaca
            </button>
        </li>
    </ul>
        
    </li>
</div>
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const badge = document.getElementById('pmNotificationBadge');
    const list = document.getElementById('pmNotificationList');

    // Load notifikasi awal
    function loadNotifications() {
        fetch('/pm/notifications')
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    badge.textContent = data.length;
                    badge.classList.remove('d-none');
                    list.innerHTML = '';
                    data.forEach(notif => {
                        list.innerHTML += `
                            <li class="p-3 border-bottom">
                                <div class="fw-bold text-primary mb-1">Work Order Baru</div>
                                <div class="small text-muted">${notif.message}</div>
                            </li>
                        `;
                    });
                } else {
                    badge.classList.add('d-none');
                    list.innerHTML = '<li class="p-3 text-muted text-center">Tidak ada notifikasi baru</li>';
                }
            });
    }
    loadNotifications();

    // Pusher listener
    var pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
        cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
        authEndpoint: '/broadcasting/auth',
        auth: { headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }
    });

    var channel = pusher.subscribe('private-pm-notifications.{{ auth()->id() }}');
    channel.bind('new-pm-notification', function(data) {
        badge.classList.remove('d-none');
        badge.textContent = parseInt(badge.textContent) + 1;

        const newNotif = `
            <li class="p-3 border-bottom">
                <div class="fw-bold text-primary mb-1">Work Order Baru</div>
                <div class="small text-muted">${data.message}</div>
            </li>
        `;
        list.innerHTML = newNotif + list.innerHTML;
    });

    // Tombol tandai semua dibaca
    document.getElementById('markAllPMRead').addEventListener('click', function(e) {
        e.preventDefault();
        fetch('/pm/notifications/mark-all-read', { 
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        }).then(() => {
            badge.classList.add('d-none');
            list.innerHTML = '<li class="p-3 text-muted text-center">Tidak ada notifikasi baru</li>';
        });
    });
});
</script>


<!-- Main Content -->
<div class="main-content">
  <!-- Modal Tambah Proyek (PM tidak perlu ini, hapus saja) -->
  
  <!-- Tabel Work Order -->
  <div class="card-box mb-4">
    <h6 class="fw-bold mb-3">Work Order</h6>
    <div class="table-responsive">
      <table class="table table-bordered align-middle" id="tabelWorkOrder">
        <thead class="table-light">
          <tr>
            <th class="col-no">No</th>
            <th class="col-judul">Judul Proyek</th>
            <th class="col-status">Status Dokumen</th>
            <th class="col-status">Status Komisi</th>
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
              <td>
                <span class="status-dot {{ $project->status_komisi === 'Disetujui' ? 'dot-success' : 'dot-warning' }}"></span>
                {{ $project->status_komisi ?? 'Belum Disetujui' }}
              </td>
              <td>{{ number_format($project->nilai ?? 0, 0, ',', '.') }}</td>
              <td>
                {{ $project->projectPersonel->map(function($p) {
                    return $p->user ? $p->user->name : '(User tidak ditemukan)';
                })->join(', ') ?: '-' }}
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
                      <form action="{{ route('pm.project.documents.store', $project) }}" method="POST" enctype="multipart/form-data">
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
              <td colspan="7" class="text-center text-muted">Tidak ada proyek.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal Akun -->
  <div class="modal fade" id="accountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center p-4" style="border-radius:40px;">
        <div class="modal-header justify-content-center border-0">
          <h5 class="modal-title px-4 py-2 rounded" style="background-color: #044280; color: white;">
            <i class="bi bi-person-fill"></i> Akun Saya
          </h5>
          <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="d-flex justify-content-between py-2">
            <strong>Nama</strong>
            <span id="accountName">{{ Auth::user()->name }}</span>
          </div>
          <div class="d-flex justify-content-between py-2">
            <strong>Email</strong>
            <span id="accountEmail">{{ Auth::user()->email }}</span>
          </div>
          <div class="d-flex justify-content-between py-2">
            <strong>Role</strong>
            <span id="accountRole">{{ Auth::user()->role }}</span>
          </div>
          <div class="d-flex justify-content-between py-2">
            <strong>Password</strong>
            <span id="accountPassword">*</span>
          </div>
        </div>
        <div class="modal-footer justify-content-center border-0">
          <button class="btn btn-warning" id="btnEditAkun">Edit Akun</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal Edit User --}}
  <div class="modal fade" id="modalEditUser" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning text-dark">
          <h5 class="modal-title">Edit User</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="errorEditUser" class="alert alert-danger d-none"></div>
          <form id="formEditUser">
            @csrf
            @method('PUT')
            <input type="hidden" id="editUserId" name="id">
            
            <div class="mb-3">
              <label>Nama</label>
              <input type="text" class="form-control" id="editName" name="name" required>
            </div>

            <div class="mb-3">
              <label>Email</label>
              <input type="email" class="form-control" id="editEmail" name="email" required>
            </div>

            <div class="mb-3">
              <label>Role</label>
              <select class="form-select" id="editRole" name="role" required>
                <option value="admin">Admin</option>
                <option value="hod">Head of Department</option>
                <option value="pm">Project Manager</option>
                <option value="staff">Staff</option>
              </select>
            </div>

            <div class="mb-3">
              <label>Password Lama</label>
              <div class="input-group">
                <input type="password" class="form-control" id="editOldPassword" name="old_password">
                <button type="button" class="btn btn-outline-secondary" id="toggleEditOldPassword">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="mb-3">
              <label>Password Baru</label>
              <div class="input-group">
                <input type="password" class="form-control" id="editPassword" name="password">
                <button type="button" class="btn btn-outline-secondary" id="toggleEditPassword">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="mb-3">
              <label>Konfirmasi Password Baru</label>
              <div class="input-group">
                <input type="password" class="form-control" id="editPasswordConfirmation" name="password_confirmation">
                <button type="button" class="btn btn-outline-secondary" id="toggleEditPasswordConfirmation">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button class="btn btn-primary" id="btnSaveEdit">Simpan Perubahan</button>
          </form>
        </div>
      </div>
    </div>
  </div>

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
            <button type="button" class="btn btn-dark" id="btnConfirmLogout">Yakin</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Statistik Dokumen -->
  <div class="card-box mb-4">
    <h6 class="fw-bold mb-3">Dokumen</h6>
    <div class="row g-3">
      <div class="col-md-4"><div class="doc-box border-primary"><i class="bi bi-folder-fill text-primary"></i><div class="title">Total Dokumen</div><div class="number">{{ $stats['total'] ?? 0 }}</div></div></div>
      <div class="col-md-4"><div class="doc-box border-warning"><i class="bi bi-folder-symlink-fill text-warning"></i><div class="title">Dokumen Revisi</div><div class="number">{{ $stats['revisi'] ?? 0 }}</div></div></div>
      <div class="col-md-4"><div class="doc-box border-success"><i class="bi bi-folder-check text-success"></i><div class="title">Dokumen Selesai</div><div class="number">{{ $stats['selesai'] ?? 0 }}</div></div></div>
    </div>
  </div>

  <!-- Statistik Komisi -->
  <div class="card-box mb-4">
    <h6 class="fw-bold mb-3">Komisi</h6>
    <div class="row g-3">
      <div class="col-md-6"><div class="komisi-box border-primary"><div class="title"><i class="bi bi-receipt"></i> Komisi Bulan ini</div><div class="amount">Rp. {{ number_format($komisi['bulan'] ?? 0, 0, ',', '.') }}</div></div></div>
      <div class="col-md-6"><div class="komisi-box border-primary"><div class="title"><i class="bi bi-receipt"></i> Komisi Tahun ini</div><div class="amount">Rp. {{ number_format($komisi['tahun'] ?? 0, 0, ',', '.') }}</div></div></div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle Password Functions
    function togglePassword(buttonId, inputId) {
        const btn = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        if (btn && input) {
            btn.addEventListener('click', function () {
                const icon = this.querySelector("i");
                if (input.type === "password") {
                    input.type = "text";
                    icon.classList.replace("bi-eye", "bi-eye-slash");
                } else {
                    input.type = "password";
                    icon.classList.replace("bi-eye-slash", "bi-eye");
                }
            });
        }
    }

    togglePassword("toggleEditOldPassword", "editOldPassword");
    togglePassword("toggleEditPassword", "editPassword");
    togglePassword("toggleEditPasswordConfirmation", "editPasswordConfirmation");

    // Account Modal Management
    const modalAccountEl = document.getElementById('accountModal');
    const modalEditUserEl = document.getElementById('modalEditUser');
    const modalAccount = new bootstrap.Modal(modalAccountEl);
    const modalEditUser = new bootstrap.Modal(modalEditUserEl);

    const btnOpenAccount = document.getElementById("openAccountModal");
    const btnEditAkun = document.getElementById("btnEditAkun");

    if (btnOpenAccount) {
        btnOpenAccount.addEventListener("click", function () {
            document.getElementById("accountName").innerText = "{{ Auth::user()->name }}";
            document.getElementById("accountEmail").innerText = "{{ Auth::user()->email }}";
            document.getElementById("accountRole").innerText = "{{ Auth::user()->role }}";
            document.getElementById("accountPassword").innerText = "";
            modalAccount.show();
        });
    }

    if (btnEditAkun) {
        btnEditAkun.addEventListener("click", function (e) {
            e.preventDefault();
            document.getElementById('editName').value = "{{ Auth::user()->name }}";
            document.getElementById('editEmail').value = "{{ Auth::user()->email }}";
            document.getElementById('editRole').value = "{{ Auth::user()->role }}";
            document.getElementById('editOldPassword').value = '';
            document.getElementById('editPassword').value = '';
            document.getElementById('editPasswordConfirmation').value = '';
            document.querySelector('#modalEditUser .modal-title').innerText = 'Edit Akun Saya';

            modalAccount.hide();
            modalAccountEl.addEventListener('hidden.bs.modal', function openEdit() {
                modalEditUser.show();
                modalAccountEl.removeEventListener('hidden.bs.modal', openEdit);
            });
        });
    }

    // Save Edit Profile
    const btnSaveEdit = document.getElementById('btnSaveEdit');
    if (btnSaveEdit) {
        btnSaveEdit.addEventListener('click', function (e) {
            e.preventDefault();

            const name = document.getElementById('editName').value;
            const email = document.getElementById('editEmail').value;
            const role = document.getElementById('editRole').value;
            const oldPassword = document.getElementById('editOldPassword').value;
            const newPassword = document.getElementById('editPassword').value;
            const passwordConfirm = document.getElementById('editPasswordConfirmation').value;

            fetch('/profile', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    name: name,
                    email: email,
                    role: role,
                    old_password: oldPassword,
                    password: newPassword,
                    password_confirmation: passwordConfirm
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('modalEditUser')).hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan.',
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan sistem.',
                });
            });
        });
    }

    // Logout Management
    const logoutButton = document.getElementById('btnLogout');
    const logoutModal = new bootstrap.Modal(document.getElementById('modalLogout'));
    const confirmLogoutBtn = document.getElementById('btnConfirmLogout');

    if (logoutButton) {
        logoutButton.addEventListener('click', function (e) {
            e.preventDefault();
            logoutModal.show();
        });
    }

    if (confirmLogoutBtn) {
        confirmLogoutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            fetch('{{ route('logout') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).then(res => {
                if (res.ok) {
                    window.location.href = '/login';
                } else {
                    alert('Gagal logout!');
                }
            }).catch(err => {
                console.error(err);
                alert('Kesalahan sistem saat logout.');
            });
        });
    }

    // Notification System
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationList = document.getElementById('notificationList');
    const markAllReadBtn = document.getElementById('markAllRead');

    // Load notifications on page load
    loadNotifications();

    // Toggle notification dropdown
    notificationIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        notificationDropdown.classList.toggle('show');
        if (notificationDropdown.classList.contains('show')) {
            loadNotifications();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationDropdown.contains(e.target) && !notificationIcon.contains(e.target)) {
            notificationDropdown.classList.remove('show');
        }
    });

    // Mark all as read
    markAllReadBtn.addEventListener('click', function() {
        fetch('/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
                updateNotificationBadge(0);
            }
        });
    });

    // Load notifications function
    function loadNotifications() {
        fetch('/notifications/unread')
            .then(response => response.json())
            .then(data => {
                updateNotificationBadge(data.count);
                renderNotifications(data.notifications);
            })
            .catch(err => {
                console.error('Error loading notifications:', err);
            });
    }

    // Update notification badge
    function updateNotificationBadge(count) {
        if (count > 0) {
            notificationBadge.textContent = count > 99 ? '99+' : count;
            notificationBadge.style.display = 'block';
        } else {
            notificationBadge.style.display = 'none';
        }
    }

    // Render notifications
    function renderNotifications(notifications) {
        if (notifications.length === 0) {
            notificationList.innerHTML = `
                <div class="notification-empty">
                    <i class="bi bi-bell-slash fs-3 d-block mb-2"></i>
                    <p class="mb-0">Tidak ada notifikasi</p>
                </div>
            `;
            return;
        }

        let html = '';
        notifications.forEach(notification => {
            const data = notification.data;
            const createdAt = new Date(notification.created_at).toLocaleString('id-ID');
            
            html += `
                <div class="notification-item unread" data-id="${notification.id}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <p class="mb-1 fw-semibold">${data.message}</p>
                            <small class="text-muted">${createdAt}</small>
                        </div>
                        <button class="btn btn-sm btn-light mark-read-btn" data-id="${notification.id}">
                            <i class="bi bi-check2"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        notificationList.innerHTML = html;

        // Add event listeners to mark as read buttons
        document.querySelectorAll('.mark-read-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                markAsRead(this.dataset.id);
            });
        });

        // Add click event to notification items
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function() {
                const notifId = this.dataset.id;
                markAsRead(notifId);
            });
        });
    }

    // Mark notification as read
    function markAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                loadNotifications();
            }
        });
    }

    // Setup Laravel Echo for real-time notifications (optional - jika menggunakan Pusher)
    @if(config('broadcasting.default') === 'pusher')
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: '{{ config('broadcasting.connections.pusher.key') }}',
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        forceTLS: true
    });

    // Listen for real-time notifications
    window.Echo.private(`user.{{ Auth::id() }}`)
        .notification((notification) => {
            // Show browser notification if permitted
            if (Notification.permission === 'granted') {
                new Notification('Work Order Baru', {
                    body: notification.message,
                    icon: '/images/desnet-logo.png'
                });
            }

            // Reload notifications
            loadNotifications();

            // Show toast notification
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Notifikasi Baru',
                text: notification.message,
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        });

    // Request browser notification permission
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
    @endif
});
</script>

</body>
</html>