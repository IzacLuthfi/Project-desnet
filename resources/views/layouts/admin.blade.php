@extends('layouts.admin') {{-- Kalau kamu sudah pakai layout, kalau belum bisa langsung HTML full --}}
@section('content')
<div class="main-content">

  <!-- Header + Tombol Tambah -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold">Kelola User</h5>
    <button class="btn add-button" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
      <i class="bi bi-plus-lg"></i> Tambah User
    </button>
  </div>

  <!-- Card Box -->
  <div class="card-box">
    <div class="table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $i => $user)
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ ucfirst($user->role) }}</td>
            <td>
              <span class="status-dot {{ $user->is_active ? 'dot-success' : 'dot-warning' }}"></span>
              {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
            </td>
            <td>
              <button class="btn btn-sm btn-detail" data-id="{{ $user->id }}"><i class="bi bi-eye"></i></button>
              <button class="btn btn-sm btn-edit" data-id="{{ $user->id }}"><i class="bi bi-pencil"></i></button>
              <form action="{{ route('kelola-user.destroy',$user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus user ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-hapus"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-3">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Tambah User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('kelola-user.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control rounded-3" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control rounded-3" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select rounded-3" required>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control rounded-3" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success rounded-3">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
