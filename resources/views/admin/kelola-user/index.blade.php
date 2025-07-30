@extends('layouts.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-700">User Management</h2>
        <a href="{{ route('kelola-user.create') }}" class="add-button">+ Tambah User</a>
    </div>

    <!-- Search -->
    <div class="mb-4">
        <form method="GET" action="{{ url('kelola-user') }}">
            <input type="text" name="search" placeholder="Cari Nama Pengguna"
                   class="border px-4 py-2 rounded w-64 focus:outline-none focus:ring" value="{{ request('search') }}">
            <button type="submit" class="ml-2 btn btn-dark">Cari</button>
        </form>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="table table-striped">
            <thead class="bg-blue-900 text-white">
                <tr>
                    <th>No</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Password</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white text-gray-800">
                @foreach ($users as $index => $user)
                <tr>
                    <td>{{ ($currentPage - 1) * 10 + $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>###</td>
                    <td>
                        <span class="badge {{ $roleColors[$user->role] ?? 'bg-light' }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('kelola-user.edit', $user->id) }}" class="btn btn-success btn-action">Edit</a>
                        <form action="{{ route('kelola-user.destroy', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin hapus user ini?')" class="btn btn-danger btn-action">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        {{ $users->links() }} <!-- Gunakan pagination Laravel -->
    </div>
</div>
@endsection