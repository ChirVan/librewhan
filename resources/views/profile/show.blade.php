@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container py-4">
  <div class="page-inner">
    <div class="row">
      {{-- LEFT: profile photo + basic info (UpdateProfileInformationForm contains photo upload) --}}
      <div class="col-md-4">
        <div class="mb-3">
          {{-- This Livewire component includes the photo input + name/email fields --}}
          @livewire('profile.update-profile-information-form')
        </div>
      </div>

      {{-- RIGHT: password change, 2FA, sessions, delete --}}
      <div class="col-md-8">
        <div class="mb-3">
          @livewire('profile.update-password-form')
        </div>

        <div class="mb-3">
          @livewire('profile.two-factor-authentication-form')
        </div>

        <div class="mb-3">
          @livewire('profile.logout-other-browser-sessions-form')
        </div>

        <div class="mb-3">
          @livewire('profile.delete-user-form')
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
