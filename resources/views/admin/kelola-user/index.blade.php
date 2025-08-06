{{-- resources/views/kelola-user/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Kelola User')

@section('content')
<div class="card-box mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-warning rounded-2" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="bi bi-plus-lg me-1"></i> Tambah User
        </button>
        <input type="text" id="searchUser" class="form-control" placeholder="Cari Nama Pengguna..." style="width: 250px;">
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle table-bordered bg-white">
            <thead class="table-light text-center">
                <tr>
                    <th>No</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                @foreach ($users as $key => $user)
                <tr data-id="{{ $user->id }}">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>###</td>
                    <td>
                        @php
                            switch ($user->role) {
                                case 'admin': $badgeColor = 'bg-danger'; break;
                                case 'hod': $badgeColor = 'bg-success'; break;
                                case 'pm': $badgeColor = 'bg-warning text-dark'; break;
                                default: $badgeColor = 'bg-secondary'; break;
                            }
                        @endphp
                        <span class="badge {{ $badgeColor }}">{{ ucfirst($user->role) }}</span>
                    </td>
                    <td class="text-center">
                        <a href="#" class="btn btn-warning btn-sm rounded-2 me-1 btn-edit" data-id="{{ $user->id }}">
    <i class="bi bi-pencil-square"></i> Edit
</a>

                        <button class="btn btn-danger btn-sm rounded-2 btn-hapus" data-id="{{ $user->id }}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pesan jika data tidak ditemukan -->
    <div id="noDataMessage" class="text-center text-muted mt-3 d-none">
        <em>Data tidak ditemukan.</em>
    </div>
</div>
@endsection

{{-- Modal Konfirmasi Hapus --}}
<div class="modal fade" id="modalKonfirmasiHapus" tabindex="-1" aria-labelledby="modalHapusLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4">
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            <h5 class="fw-bold mt-3">Apakah Anda yakin ingin menghapus data ini?</h5>
            <p class="text-muted">Tindakan ini akan menghapus data secara permanen.</p>
            <div class="d-flex justify-content-center gap-2 mt-3">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-dark" id="btnKonfirmasiHapus">Hapus</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah User --}}
<div class="modal fade" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="fw-bold mb-0">Tambah User</h5>
                <small class="fw-bold text-muted">Masukkan Data</small>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <hr>
            <form id="formTambahUser">
                @csrf
                <div class="mb-3">
                    <label>Nama User</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label>Email User</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label>Password User</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label>Masukkan Ulang Password</label>
                    <input type="password" class="form-control" name="password_confirmation" required>
                </div>
                <div class="mb-3">
                    <label>Role</label>
                    <select class="form-select" name="role" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="pm">PM</option>
                        <option value="hod">HOD</option>
                        <option value="admin">Admin</option>
                        <option value="staff">Staff</option>
                    </select>
                </div>
                <div id="errorTambahUser" class="alert alert-danger d-none"></div>
                <div class="text-end mt-3">
                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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
                        <input type="password" class="form-control" id="editOldPassword" name="old_password">
                    </div>
                    <div class="mb-3">
                        <label>Password Baru</label>
                        <input type="password" class="form-control" id="editPassword" name="password">
                    </div>
                    <div class="mb-3">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="editPasswordConfirmation" name="password_confirmation">
                    </div>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button class="btn btn-primary" id="btnSaveEdit">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const formTambahUser = document.getElementById('formTambahUser');
    const errorBox = document.getElementById('errorTambahUser');
    const modalTambahUser = new bootstrap.Modal(document.getElementById('modalTambahUser'));

    // Submit tambah user
    formTambahUser.addEventListener('submit', function(e) {
        e.preventDefault();
        errorBox.classList.add('d-none');
        errorBox.innerHTML = '';
        const formData = new FormData(formTambahUser);

        fetch("{{ route('kelola-user.store') }}", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(async res => {
            if (res.ok) {
                const data = await res.json();
                const tbody = document.getElementById('userTableBody');
                const newIndex = tbody.querySelectorAll('tr').length + 1;

                let badgeClass = 'bg-secondary';
                if (data.role === 'admin') badgeClass = 'bg-danger';
                else if (data.role === 'hod') badgeClass = 'bg-success';
                else if (data.role === 'pm') badgeClass = 'bg-warning text-dark';

                const newRow = document.createElement('tr');
                newRow.setAttribute('data-id', data.id);
                newRow.innerHTML = `
                    <td>${newIndex}</td>
                    <td>${data.name}</td>
                    <td>${data.email}</td>
                    <td>###</td>
                    <td><span class="badge ${badgeClass}">${data.role}</span></td>
                    <td class="text-center">
                        <a href="#" class="btn btn-warning btn-sm rounded-2 me-1 btn-edit">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <button class="btn btn-danger btn-sm rounded-2 btn-hapus" data-id="${data.id}">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </td>
                `;
                tbody.appendChild(newRow);
                modalTambahUser.hide();
                formTambahUser.reset();
            } else if (res.status === 422) {
                const err = await res.json();
                errorBox.innerHTML = Object.values(err.errors).flat().join('<br>');
                errorBox.classList.remove('d-none');
            } else {
                alert('Terjadi kesalahan server.');
            }
        })
        .catch(() => alert('Terjadi kesalahan jaringan.'));
    });

    // Live Search
    document.getElementById('searchUser').addEventListener('keyup', function () {
        const query = this.value;
        fetch(`{{ route('kelola-user.search') }}?q=${query}`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('userTableBody');
            tbody.innerHTML = '';

            if (data.length === 0) {
                document.getElementById('noDataMessage').classList.remove('d-none');
            } else {
                document.getElementById('noDataMessage').classList.add('d-none');
                data.forEach((user, index) => {
                    let badgeClass = 'bg-secondary';
                    if (user.role === 'admin') badgeClass = 'bg-danger';
                    else if (user.role === 'hod') badgeClass = 'bg-success';
                    else if (user.role === 'pm') badgeClass = 'bg-warning text-dark';

                    const row = document.createElement('tr');
                    row.setAttribute('data-id', user.id);
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>###</td>
                        <td><span class="badge ${badgeClass}">${user.role}</span></td>
                        <td class="text-center">
                            <a href="#" class="btn btn-warning btn-sm rounded-2 me-1 btn-edit" data-id="${user.id}">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                            <button class="btn btn-danger btn-sm rounded-2 btn-hapus" data-id="${user.id}">
                                <i class="bi bi-trash"></i> Hapus
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        })
        .catch(err => console.error(err));
    });

    // Edit user
    const modalEditUser = new bootstrap.Modal(document.getElementById('modalEditUser'));
    const formEditUser = document.getElementById('formEditUser');
    const errorEditBox = document.getElementById('errorEditUser');

    document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-edit')) {
        const id = e.target.closest('.btn-edit').dataset.id;
        const row = e.target.closest('tr');
        const name = row.children[1].textContent.trim();
        const email = row.children[2].textContent.trim();
        const role = row.querySelector('span.badge').textContent.trim();

        document.getElementById('editUserId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;
        document.getElementById('editRole').value = role.toLowerCase();

        modalEditUser.show();
    }
    });

    // Submit edit
    formEditUser.addEventListener('submit', function(e) {
        e.preventDefault();
        errorEditBox.classList.add('d-none');
        errorEditBox.innerHTML = '';
        const id = document.getElementById('editUserId').value;
        const formData = new FormData(formEditUser);

        fetch(`/admin/kelola-user/${id}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: formData
        })
        .then(async res => {
            if (res.ok) {
                const data = await res.json();
                const row = document.querySelector(`tr[data-id="${id}"]`);
                row.children[1].textContent = data.name;
                row.children[2].textContent = data.email;

                let badgeClass = 'bg-secondary';
                if (data.role === 'admin') badgeClass = 'bg-danger';
                else if (data.role === 'hod') badgeClass = 'bg-success';
                else if (data.role === 'pm') badgeClass = 'bg-warning text-dark';

                row.children[4].innerHTML = `<span class="badge ${badgeClass}">${data.role}</span>`;
                modalEditUser.hide();

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data user berhasil diperbarui',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else if (res.status === 422) {
                const err = await res.json();
                errorEditBox.innerHTML = Object.values(err.errors).flat().join('<br>');
                errorEditBox.classList.remove('d-none');
            } else {
                Swal.fire('Error', 'Terjadi kesalahan server.', 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error'));
    });

    // Hapus user
    document.addEventListener('click', function (e) {
        if (e.target.closest('.btn-hapus')) {
            const idToDelete = e.target.closest('.btn-hapus').dataset.id;
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tindakan ini akan menghapus data secara permanen",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/kelola-user/${idToDelete}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    })
                    .then(res => {
                        if (res.ok) {
                            document.querySelector(`tr[data-id="${idToDelete}"]`).remove();
                            Swal.fire('Dihapus!', 'Data user berhasil dihapus.', 'success');
                        } else {
                            Swal.fire('Error', 'Gagal menghapus data.', 'error');
                        }
                    })
                    .catch(() => Swal.fire('Error', 'Terjadi kesalahan jaringan.', 'error'));
                }
            });
        }
    });
});
</script>
@endpush
