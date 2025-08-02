@extends('layouts.app')

@section('content')
<div class="container mt-4">
  <h4 class="mb-4 fw-bold">Edit User</h4>

  <form action="{{ route('kelola-user.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label for="name" class="form-label">Nama Lengkap</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
    </div>

    <div class="mb-3">
      <label for="role" class="form-label">Role</label>
      <select name="role" class="form-select" required>
        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success">Update</button>
    <a href="{{ route('kelola-user.index') }}" class="btn btn-secondary">Batal</a>
  </form>
</div>
@endsection
