@extends('layouts.app')

@section('title','User Management')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Users</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create User</a>
    </div>

    @if(session('status'))
      <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr><th>#</th><th>Name</th><th>Email</th><th>Role</th><th>Created</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach(\App\Models\User::orderBy('id','desc')->limit(50)->get() as $u)
                        <tr>
                            <td>{{ $u->id }}</td>
                            <td>{{ $u->name }}</td>
                            <td>{{ $u->email }}</td>
                            <td>{{ $u->usertype }}</td>
                            <td>{{ $u->created_at->format('Y-m-d') }}</td>
                            <td class="d-flex gap-1">
                                <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-info">Edit</a>
                                <form action="{{ route('admin.users.resetPassword', $u) }}" method="POST" onsubmit="return confirm('Reset password for {{ $u->name }}?')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-warning">Reset Password</button>
                                </form>
                                <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Delete {{ $u->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p class="text-muted small">Showing latest 50 users.</p>
        </div>
    </div>
</div>
@endsection
