@extends('layouts.app')

@section('title','User Management')

@section('content')


<div class="container py-4">
    <div class="page-inner">
        <div class="page-header mb-4">
            <h3 class="fw-bold mb-3">User Management</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="{{ route('dashboard') }}">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Users</a>
                </li>
            </ul>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary px-4 fw-semibold">
                <i class="fas fa-user-plus me-2"></i> Create User
            </a>
        </div>

        @if(session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-0">
                <div class="table-responsive rounded-4" style="overflow:hidden;">
                    <table class="table table-striped table-hover align-middle mb-0" style="background:#f8fafc;">
                        <thead class="table-light">
                            <tr style="font-weight:600;">
                                <th class="py-3 px-3">#</th>
                                <th class="py-3 px-3">Name</th>
                                <th class="py-3 px-3">Email</th>
                                <th class="py-3 px-3">Role</th>
                                <th class="py-3 px-3">Created</th>
                                <th class="py-3 px-3 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\App\Models\User::orderBy('id','asc')->limit(50)->get() as $u)
                                <tr>
                                    <td class="py-3 px-3">{{ $u->id }}</td>
                                    <td class="py-3 px-3">{{ $u->name }}</td>
                                    <td class="py-3 px-3">{{ $u->email }}</td>
                                    <td class="py-3 px-3 text-capitalize">{{ $u->usertype }}</td>
                                    <td class="py-3 px-3">{{ $u->created_at->format('Y-m-d') }}</td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="d-inline-flex flex-wrap gap-2 justify-content-center">
                                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-info px-3">Edit</a>
                                            <form action="{{ route('admin.users.resetPassword', $u) }}" method="POST" onsubmit="return confirm('Reset password for {{ $u->name }}?')" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-warning px-3">Reset Password</button>
                                            </form>
                                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" onsubmit="return confirm('Delete {{ $u->name }}?')" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger px-3">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <p class="text-muted small mt-3 ms-3">Showing latest 50 users.</p>
            </div>
        </div>
    </div>
</div>
@endsection
