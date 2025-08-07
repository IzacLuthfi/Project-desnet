<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Staff</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap, Font & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <form id="formTambahProject">

  <!-- Personel Dynamic JS -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      let personelCount = 3;

      document.getElementById('addPersonelBtn').addEventListener('click', function () {
        const container = document.getElementById('personelContainer');
        const index = personelCount++;
        
        const row = document.createElement('div');
        row.className = 'row g-2 mb-3 personel-row';
        row.innerHTML = `
          <div class="col-md-6">
            <label class="form-label">Personel ${index + 1}</label>
            <input type="text" name="personel[${index}][nama]" class="form-control rounded-3">
          </div>
          <div class="col-md-6">
            <label class="form-label">Sebagai:</label>
            <select name="personel[${index}][role]" class="form-select rounded-3">
              <option value="">Pilih peran</option>
              <option>Analis</option>
              <option>Programer web</option>
              <option>Programer mobile</option>
              <option>Tester</option>
              <option>Desainer</option>
              <option>Front-end</option>
            </select>
          </div>
        `;
        container.appendChild(row);
      });
    });
  </script>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    let personelCount = 3;

    document.getElementById('addPersonelBtn').addEventListener('click', function () {
      // Tambah personel secara dinamis
    });
  });
</script>

<script>
  document.getElementById('addPersonelBtn').addEventListener('click', function () {
    // Tambah personel (duplikat dari atas)
  });

  // AJAX simpan project juga di sini
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  let personelCount = 3;

  // HANYA INI SAJA: tombol tambah personel
  document.getElementById('addPersonelBtn').addEventListener('click', function () {
    const container = document.getElementById('personelContainer');
    const index = personelCount++;

    const row = document.createElement('div');
    row.className = 'row g-2 mb-3 personel-row';
    row.innerHTML = `
      <div class="col-md-6">
        <label class="form-label">Personel ${index + 1}</label>
        <input type="text" name="personel[${index}][nama]" class="form-control" placeholder="Nama Personel">
      </div>
      <div class="col-md-6">
        <label class="form-label">Sebagai:</label>
        <select name="personel[${index}][role]" class="form-select">
          <option value="">Pilih peran</option>
          <option>Analis</option>
          <option>Programer web</option>
          <option>Programer mobile</option>
          <option>Tester</option>
          <option>Desainer</option>
          <option>Front-end</option>
        </select>
      </div>
    `;
    container.appendChild(row);
  });

    // Simpan proyek via AJAX
  document.getElementById('formTambahProject').addEventListener('submit', function (e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const notif = document.getElementById('notifAjax');
    const btn = document.getElementById('btnSimpanProject');

    btn.disabled = true;
    fetch("{{ url('/projects/ajax-store') }}", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": '{{ csrf_token() }}'
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      btn.disabled = false;
      if (data.success) {
        notif.classList.remove('d-none', 'alert-danger');
        notif.classList.add('alert-success');
        notif.innerText = data.message;

        // Tambahkan baris ke tabel
        const table = document.getElementById('tabelWorkOrder').querySelector('tbody');
        const index = table.querySelectorAll('tr').length + 1;
        const personelList = data.project.project_personel.map(p => p.nama).join(', ');
        const row = `
  <tr>
    <td>${index}</td>
    <td>${data.project.judul}</td>
    <td><span class="status-dot dot-warning"></span> Belum Diajukan</td>
    <td><span class="status-dot dot-warning"></span> Belum Disetujui</td>
    <td>${parseInt(data.project.nilai).toLocaleString('id-ID')}</td>
    <td>${personelList || '-'}</td>
    <td>
      <a href="/projects/${data.project.id}" class="btn btn-sm btn-info text-white">Detail</a>
      <a href="/projects/${data.project.id}/edit" class="btn btn-sm btn-warning text-white">Edit</a>
      <form action="/projects/${data.project.id}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?')">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
      </form>
    </td>
  </tr>`;
        table.insertAdjacentHTML('beforeend', row);

        // Reset form & tutup modal
        form.reset();
        document.getElementById('modalTambahProject').querySelector('.btn-close').click();
      } else {
        notif.classList.remove('d-none', 'alert-success');
        notif.classList.add('alert-danger');
        notif.innerText = data.message || 'Gagal menyimpan proyek.';
      }
    })
    .catch(err => {
      notif.classList.remove('d-none', 'alert-success');
      notif.classList.add('alert-danger');
      notif.innerText = 'Kesalahan server. Silakan coba lagi.';
      btn.disabled = false;
    });
  });
});
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    let personelCount = 3;

    // Tambah Personel
    document.getElementById('addPersonelBtn').addEventListener('click', function () {
      const container = document.getElementById('personelContainer');
      const index = personelCount++;

      const row = document.createElement('div');
      row.className = 'row g-2 mb-3 personel-row';
      row.innerHTML = `
        <div class="col-md-6">
          <label class="form-label">Personel ${index + 1}</label>
          <input type="text" name="personel[${index}][nama]" class="form-control" placeholder="Nama Personel">
        </div>
        <div class="col-md-6">
          <label class="form-label">Sebagai:</label>
          <select name="personel[${index}][role]" class="form-select">
            <option value="">Pilih peran</option>
            <option>Analis</option>
            <option>Programer web</option>
            <option>Programer mobile</option>
            <option>Tester</option>
            <option>Desainer</option>
            <option>Front-end</option>
          </select>
        </div>
      `;
      container.appendChild(row);
    });

    // RESET form saat klik Batal
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
      btn.addEventListener('click', function () {
        const form = document.getElementById('formTambahProject');
        form.reset();

        // Hapus semua personel dinamis
        const container = document.getElementById('personelContainer');
        container.innerHTML = '';

        // Tambahkan kembali default 3 personel
        personelCount = 3;
        for (let i = 0; i < 3; i++) {
          const row = document.createElement('div');
          row.className = 'row g-2 mb-3 personel-row';
          row.innerHTML = `
            <div class="col-md-6">
              <label class="form-label">Personel ${i + 1}</label>
              <input type="text" name="personel[${i}][nama]" class="form-control" placeholder="Nama Personel">
            </div>
            <div class="col-md-6">
              <label class="form-label">Sebagai:</label>
              <select name="personel[${i}][role]" class="form-select">
                <option value="">Pilih peran</option>
                <option>Analis</option>
                <option>Programer web</option>
                <option>Programer mobile</option>
                <option>Tester</option>
                <option>Desainer</option>
                <option>Front-end</option>
              </select>
            </div>
          `;
          container.appendChild(row);
        }

        // Sembunyikan notifikasi
        const notif = document.getElementById('notifAjax');
        notif.classList.add('d-none');
        notif.innerText = '';
      });
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
      <div class="role-label" id="openAccountModal" style="cursor:pointer;">
    <i class="bi bi-person-fill"></i> {{ Auth::user()->role }}
  </div>
    </div>

    <nav class="nav flex-column mb-auto">
      <a href="{{ route('staff.dashboard') }}" class="nav-link {{ Request::is('staff/dashboard') ? 'active' : '' }}">Beranda</a>
      <a href="{{ route('staff.project') }}" class="nav-link {{ Request::is('staff/project') ? 'active' : '' }}">Project</a>
      <a href="{{ route('staff.komisi') }}" class="nav-link {{ Request::is('staff/komisi') ? 'active' : '' }}">Komisi</a>
    </nav>

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
          <strong>Name</strong>
          <span id="accountName">{{ Auth::user()->name }}</span>
        </div>
        <div class="d-flex justify-content-between py-2">
          <strong>Email account</strong>
          <span id="accountEmail">{{ Auth::user()->email }}</span>
        </div>
        <div class="d-flex justify-content-between py-2">
          <strong>Role</strong>
          <span id="accountRole">{{ Auth::user()->role }}</span>
        </div>
        <div class="d-flex justify-content-between py-2">
          <strong>Password</strong>
          <span id="accountPassword">*******</span>
        </div>
      </div>
      <div class="modal-footer justify-content-center border-0">
        <button class="btn btn-warning" id="btnEditAkun">Edit Akun</button>
      </div>
    </div>
  </div>
</div>


<!-- Modal Edit User -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:40px;">
      <div class="modal-header">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formEditUser">
          <input type="hidden" id="editUserId">
          <div class="mb-3">
            <label>Nama</label>
            <input type="text" id="editName" class="form-control">
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" id="editEmail" class="form-control">
          </div>
          <div class="mb-3">
            <label>Role</label>
            <select id="editRole" class="form-control">
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Password Lama</label>
            <input type="password" id="editOldPassword" class="form-control">
          </div>
          <div class="mb-3">
            <label>Password Baru</label>
            <input type="password" id="editPassword" class="form-control">
          </div>
          <div class="mb-3">
            <label>Konfirmasi Password Baru</label>
            <input type="password" id="editPasswordConfirmation" class="form-control">
          </div>
          <div id="errorEditUser" class="text-danger"></div>
        </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-primary" id="btnSaveEdit">Simpan Perubahan</button>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script Utama -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEditUser = new bootstrap.Modal(document.getElementById('modalEditUser'));
    const modalAccount = new bootstrap.Modal(document.getElementById('accountModal'));
    const formEditUser = document.getElementById('formEditUser');
    const errorEditBox = document.getElementById('errorEditUser');

    // Klik "Admin" atau role user untuk membuka modal akun
    document.getElementById("openAccountModal").addEventListener("click", function () {
        // Isi ulang data akun
        document.getElementById("accountName").innerText = "{{ Auth::user()->name }}";
        document.getElementById("accountEmail").innerText = "{{ Auth::user()->email }}";
        document.getElementById("accountRole").innerText = "{{ Auth::user()->role }}";
        document.getElementById("accountPassword").innerText = "********";

        modalAccount.show();
    });

    // Klik tombol edit akun
    document.getElementById('btnEditAkun').addEventListener('click', function() {
        document.getElementById('editUserId').value = "{{ Auth::user()->id }}";
        document.getElementById('editName').value = "{{ Auth::user()->name }}";
        document.getElementById('editEmail').value = "{{ Auth::user()->email }}";
        document.getElementById('editRole').value = "{{ Auth::user()->role }}";

        document.getElementById('editOldPassword').value = '';
        document.getElementById('editPassword').value = '';
        document.getElementById('editPasswordConfirmation').value = '';

        document.querySelector('#modalEditUser .modal-title').innerText = 'Edit Akun Saya';
        modalEditUser.show();
    });

    // Edit user dari tabel
    document.addEventListener('click', function(e) {
        if (e.target.closest('.btn-edit')) {
            const row = e.target.closest('tr');
            const id = row.dataset.id;
            const name = row.children[1].textContent.trim();
            const email = row.children[2].textContent.trim();
            const role = row.querySelector('span.badge').textContent.trim().toLowerCase();

            document.getElementById('editUserId').value = id;
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;

            document.getElementById('editOldPassword').value = '';
            document.getElementById('editPassword').value = '';
            document.getElementById('editPasswordConfirmation').value = '';

            document.querySelector('#modalEditUser .modal-title').innerText = 'Edit User';
            modalEditUser.show();
        }
    });
});
</script>

<!-- Modal Tambah Proyek -->
<div class="modal fade" id="modalTambahProject" tabindex="-1" aria-labelledby="modalTambahProjectLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header border-bottom">
        <h5 class="modal-title fw-bold">Tambah Data Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('projects.store') }}" method="POST" id="formTambahProject">
        @csrf
        <div id="notifAjax" class="alert d-none" role="alert"></div>
        <div class="modal-body">

          <!-- Proyek -->
          <div class="mb-4">
            <h6 class="fw-semibold">Proyek</h6>

            <div class="mb-3">
              <label class="form-label">Judul Proyek</label>
              <input type="text" name="judul" class="form-control" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Nilai Proyek</label>
              <input type="number" name="nilai" class="form-control" required>
            </div>
          </div>

          <!-- Personel -->
          <div class="mb-3">
            <h6 class="fw-semibold">Personel</h6>

            <div class="mb-3">
              <label class="form-label">Project Manager</label>
              <input type="text" name="pm" class="form-control" required>
            </div>

            <div id="personelContainer">
              @for ($i = 0; $i < 3; $i++)
              <div class="row g-2 mb-3 personel-row">
                <div class="col-md-6">
                  <label class="form-label">Personel {{ $i + 1 }}</label>
                  <input type="text" name="personel[{{ $i }}][nama]" class="form-control" placeholder="Nama Personel">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Sebagai:</label>
                  <select name="personel[{{ $i }}][role]" class="form-select">
                    <option value="">Pilih peran</option>
                    <option>Analis</option>
                    <option>Programer web</option>
                    <option>Programer mobile</option>
                    <option>Tester</option>
                    <option>Desainer</option>
                    <option>Front-end</option>
                  </select>
                </div>
              </div>
              @endfor
            </div>

            <!-- Tombol Tambah Personel -->
            <button type="button" class="btn btn-sm btn-primary mt-2 rounded-pill px-3" id="addPersonelBtn">
              <i class="bi bi-plus-circle me-1"></i> Tambah Personel
            </button>
          </div>
        </div>

        <!-- Footer -->
        <div class="modal-footer border-top-0">
          <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary px-4" id="btnSimpanProject">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Tabel Work Order -->
<div class="card-box mb-4">
  <h6 class="fw-bold mb-3">Work Order</h6>
  <div class="table-responsive">
    <table class="table table-bordered align-middle" id="tabelWorkOrder">
      <thead class="table-light">
        <tr>
          <th>No</th>
          <th>Judul Proyek</th>
          <th>Status Dokumen</th>
          <th>Status Komisi</th>
          <th>Nilai Proyek</th>
          <th>Personel</th>
          <th>Aksi</th>
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
            <td>{{ $project->projectPersonel->pluck('nama')->join(', ') ?: '-' }}</td>
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
            <td colspan="7" class="text-center text-muted">Belum ada dokumen proyek.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
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
 

  <!-- Statistik Dokumen -->
  <div class="card-box mb-4">
    <h6 class="fw-bold mb-3">Dokumen</h6>
    <div class="row g-3">
      <div class="col-md-4"><div class="doc-box border-primary"><i class="bi bi-folder-fill text-primary"></i><div class="title">Total Dokumen</div><div class="number">47</div></div></div>
      <div class="col-md-4"><div class="doc-box border-warning"><i class="bi bi-folder-symlink-fill text-warning"></i><div class="title">Dokumen Revisi</div><div class="number">15</div></div></div>
      <div class="col-md-4"><div class="doc-box border-success"><i class="bi bi-folder-check text-success"></i><div class="title">Dokumen Selesai</div><div class="number">32</div></div></div>
    </div>
  </div>

  <!-- Statistik Komisi -->
  <div class="card-box mb-4">
    <h6 class="fw-bold mb-3">Komisi</h6>
    <div class="row g-3">
      <div class="col-md-6"><div class="komisi-box border-primary"><div class="title"><i class="bi bi-receipt"></i> Komisi Bulan ini</div><div class="amount">Rp. 76.000.000,00</div></div></div>
      <div class="col-md-6"><div class="komisi-box border-primary"><div class="title"><i class="bi bi-receipt"></i> Komisi Tahun ini</div><div class="amount">Rp. 1.546.000.000,00</div></div></div>
    </div>
  </div>

</div>
</body>
</html>
