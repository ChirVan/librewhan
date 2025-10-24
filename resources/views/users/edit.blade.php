@extends('layouts.app')

@section('title','Edit User')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-header"><strong>Edit User</strong></div>
        <div class="card-body">
            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="usertype" class="form-control">
                        <option value="barista" {{ $user->usertype==='barista' ? 'selected' : '' }}>Barista</option>
                        <option value="admin" {{ $user->usertype==='admin' ? 'selected' : '' }}>Owner/Admin</option>
                    </select>
                </div>
                <button class="btn btn-primary">Save</button>
                <a class="btn btn-danger" href="{{ url('admin/users') }}">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
