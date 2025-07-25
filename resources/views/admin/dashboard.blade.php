<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    let personelCount = 3;

    document.getElementById('addPersonelBtn').addEventListener('click', function () {
      personelCount++;

      const container = document.getElementById('personelContainer');

      const row = document.createElement('div');
      row.className = 'row g-2 mb-3';

      row.innerHTML = `
        <div class="col-md-6">
          <label class="form-label">Personel ${personelCount}</label>
          <input type="text" name="personel[]" class="form-control rounded-3">
        </div>
        <div class="col-md-6">
          <label class="form-label">Sebagai:</label>
          <select name="role[]" class="form-select rounded-3">
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

    .topbar .logo img {
      height: 32px;
      margin-right: 10px;
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

    .doc-box {
      background-color: #f8f9ff;
      border: 1px solid #e0e7ff;
      border-radius: 14px;
      padding: 20px;
      text-align: center;
    }

    .doc-box i {
      font-size: 28px;
      margin-bottom: 8px;
      display: block;
    }

    .doc-box .title {
      font-weight: 600;
      margin-bottom: 5px;
    }

    .doc-box .number {
      font-size: 24px;
      font-weight: bold;
      color: #4f46e5;
    }

    .komisi-box {
      background-color: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 14px;
      padding: 20px;
      text-align: center;
    }

    .komisi-box .title {
      font-weight: 600;
      margin-bottom: 8px;
    }

    .komisi-box .amount {
      font-size: 22px;
      font-weight: bold;
    }

    table th, table td {
      vertical-align: middle;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <div class="text-center mb-3">
      <img src="{{ asset('images/desnet-logo.png') }}" alt="Logo" class="img-fluid mb-2">
      <div class="role-label"><i class="bi bi-person-fill"></i> Admin</div>
    </div>
    <nav class="nav flex-column">
      <a href="#" class="nav-link active">Beranda</a>
      <a href="#" class="nav-link">Kelola User</a>
      <a href="#" class="nav-link">Monitoring</a>
      <a href="#" class="nav-link">Komisi</a>
    </nav>
  </div>

  <!-- Topbar -->
  <div class="topbar">
    <div class="logo d-flex align-items-center">
      <h6 class="mb-0 fw-bold">Managemen Arsip Dokumen dan Komisi</h6>
    </div>
    <div class="d-flex align-items-center gap-3">
      <i class="bi bi-calendar-event"></i>
      <i class="bi bi-question-circle"></i>
      <i class="bi bi-bell"></i>
    </div>
  </div>

  <!-- Main Content -->
    <div class="main-content">
        <button class="add-button mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahProject">
        <i class="bi bi-plus-circle me-1"></i> Tambah Dokumen
    </button>
    <!-- Modal Tambah Project -->
<div class="modal fade" id="modalTambahProject" tabindex="-1" aria-labelledby="modalTambahProjectLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 shadow">
      <div class="modal-header border-bottom-0">
        <h5 class="modal-title fw-bold" id="modalTambahProjectLabel">Tambah Data Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body px-4">
        <form>
          <!-- Proyek -->
          <div class="mb-3">
            <label class="form-label fw-semibold">Judul Proyek</label>
            <input type="text" class="form-control rounded-3">
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Nilai Proyek</label>
            <input type="text" class="form-control rounded-3">
          </div>

          <!-- Personel -->
          <h6 class="fw-bold mb-3">Personel</h6>

          <div class="mb-3">
            <label class="form-label fw-semibold">Project Manager</label>
            <input type="text" class="form-control rounded-3">
          </div>

          <div id="personelContainer">
  <!-- Personel 1 -->
  <div class="row g-2 mb-3">
    <div class="col-md-6">
      <label class="form-label">Personel 1</label>
      <input type="text" class="form-control rounded-3" name="personel[]">
    </div>
    <div class="col-md-6">
      <label class="form-label">Sebagai:</label>
      <select class="form-select rounded-3" name="role[]">
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

  <!-- Personel 2 -->
  <div class="row g-2 mb-3">
    <div class="col-md-6">
      <label class="form-label">Personel 2</label>
      <input type="text" class="form-control rounded-3" name="personel[]">
    </div>
    <div class="col-md-6">
      <label class="form-label">Sebagai:</label>
      <select class="form-select rounded-3" name="role[]">
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

  <!-- Personel 3 -->
  <div class="row g-2 mb-3">
    <div class="col-md-6">
      <label class="form-label">Personel 3</label>
      <input type="text" class="form-control rounded-3" name="personel[]">
    </div>
    <div class="col-md-6">
      <label class="form-label">Sebagai:</label>
      <select class="form-select rounded-3" name="role[]">
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
</div>


          <!-- Tombol Tambah Personel -->
          <div class="mb-4">
            <button type="button" id="addPersonelBtn" class="btn btn-primary rounded-pill px-3">
              <i class="bi bi-plus-circle me-1"></i> Tambah Personel
            </button>
          </div>
        </form>
      </div>

      <div class="modal-footer border-top-0">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
      </div>
    </div>
  </div>
</div>

    <!-- Work Order Table -->
    <div class="card-box mb-4">
      <h6 class="fw-bold mb-3">Work Order</h6>
      <div class="table-responsive">
        <table class="table table-bordered align-middle">
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
            <tr>
              <td>1</td>
              <td>Trip Karimun Jawa</td>
              <td><span class="status-dot dot-success"></span>Sudah Diajukan</td>
              <td><span class="status-dot dot-success"></span>Disetujui</td>
              <td>10.000.000</td>
              <td>ahmad, fiki, tiara</td>
              <td>
                <button class="btn-detail">Detail</button>
                <button class="btn-edit">Edit</button>
                <button class="btn-hapus">Hapus</button>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Background.jpg</td>
              <td><span class="status-dot dot-warning"></span>Belum Diajukan</td>
              <td><span class="status-dot dot-warning"></span>Belum Disetujui</td>
              <td>50.000.000</td>
              <td>ahmad, fiki, tiara</td>
              <td>
                <button class="btn-detail">Detail</button>
                <button class="btn-edit">Edit</button>
                <button class="btn-hapus">Hapus</button>
              </td>
            </tr>
            <tr>
              <td>3</td>
              <td>Proposal.docx</td>
              <td><span class="status-dot dot-warning"></span>Belum Diajukan</td>
              <td><span class="status-dot dot-warning"></span>Belum Disetujui</td>
              <td>41.500.000</td>
              <td>ahmad, fiki, tiara</td>
              <td>
                <button class="btn-detail">Detail</button>
                <button class="btn-edit">Edit</button>
                <button class="btn-hapus">Hapus</button>
              </td>
            </tr>
            <tr>
              <td>4</td>
              <td>Illustration.ai</td>
              <td><span class="status-dot dot-success"></span>Sudah Diajukan</td>
              <td><span class="status-dot dot-success"></span>Disetujui</td>
              <td>15.000.000</td>
              <td>ahmad, fiki, tiara</td>
              <td>
                <button class="btn-detail">Detail</button>
                <button class="btn-edit">Edit</button>
                <button class="btn-hapus">Hapus</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Dokument Statistik -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="doc-box">
          <i class="bi bi-folder-fill text-primary"></i>
          <div class="title">Total Dokumen</div>
          <div class="number">47</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="doc-box">
          <i class="bi bi-folder-symlink-fill text-warning"></i>
          <div class="title">Dokumen Revisi</div>
          <div class="number">15</div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="doc-box">
          <i class="bi bi-folder-check text-success"></i>
          <div class="title">Dokumen Selesai</div>
          <div class="number">32</div>
        </div>
      </div>
    </div>

    <!-- Komisi Statistik -->
    <div class="row g-3">
      <div class="col-md-6">
        <div class="komisi-box">
          <div class="title">Komisi Bulan ini</div>
          <div class="amount">Rp. 76.000.000,00</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="komisi-box">
          <div class="title">Komisi Tahun ini</div>
          <div class="amount">Rp. 1.546.000.000,00</div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
