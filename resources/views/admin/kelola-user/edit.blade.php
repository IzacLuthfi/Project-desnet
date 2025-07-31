@extends('layouts.admin')

@section('content')
<h3>Edit User</h3>
<form action="{{ route('kelola-user.update', $user->id) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Password (Kosongkan jika tidak diubah)</label>
        <input type="password" name="password" class="form-control">
    </div>
    <div class="mb-3">
        <label>Role</label>
        <select name="role" class="form-control" required>
            <option value="Admin" @if($user->role=='Admin') selected @endif>Admin</option>
            <option value="HOD" @if($user->role=='HOD') selected @endif>HOD</option>
            <option value="PM" @if($user->role=='PM') selected @endif>PM</option>
            <option value="Staff" @if($user->role=='Staff') selected @endif>Staff</option>
        </select>
    </div>
    <button type="submit" class="btn btn-success">Update</button>
</form>
@endsection
