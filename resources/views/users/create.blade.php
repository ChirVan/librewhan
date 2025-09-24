@extends('layouts.app')

@section('title','Create User')

@section('content')
<div class="container py-4">
  <div class="page-inner">
    <div class="card">
      <div class="card-header"><strong>Create User</strong></div>
      <div class="card-body">
        @if($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}">
          @csrf

          <div class="mb-3">
            <label class="form-label">Name</label>
            <input name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Email</label>
            <input name="email" type="email" class="form-control" value="{{ old('email') }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="usertype" class="form-control" required>
              <option value="">Select role</option>
              <option value="barista" {{ old('usertype')==='barista' ? 'selected':'' }}>Barista</option>
              <option value="admin" {{ old('usertype')==='admin' ? 'selected':'' }}>Admin / Owner</option>
            </select>
            @error('usertype') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <hr>
          <p class="text-muted small">If you want to set a password now, fill both fields. Otherwise a random password will be generated and emailed.</p>

          <div class="mb-3">
            <label class="form-label">Password (optional)</label>
            <input name="password" type="password" class="form-control" autocomplete="new-password">
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
          </div>

          <button class="btn btn-primary">Create User</button>
          <a href="{{ route('admin.users.index') }}" class="btn btn-link">Cancel</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
