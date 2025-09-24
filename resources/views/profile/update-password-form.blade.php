<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">Update Password</h5>
    <p class="text-muted small">Ensure your account uses a min. of 8 characters to stay secure.</p>

    <form wire:submit.prevent="updatePassword">
      <div class="mb-3">
        <label class="form-label">Current Password</label>
        <input id="current_password" type="password" wire:model.defer="state.current_password"
               class="form-control" autocomplete="current-password" />
        @error('state.current_password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">New Password</label>
        <input id="password" type="password" wire:model.defer="state.password" class="form-control" autocomplete="new-password" />
        @error('state.password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
      </div>

      <div class="mb-3">
        <label class="form-label">Confirm New Password</label>
        <input id="password_confirmation" type="password" wire:model.defer="state.password_confirmation" class="form-control" autocomplete="new-password" />
      </div>

      <div class="d-flex align-items-center justify-content-end gap-3 p-2 rounded bg-light bg-opacity-25">
        <button class="btn btn-warning btn-sm px-3" type="submit" wire:loading.attr="disabled">Save</button>
        <div class="text-success small fw-bold" wire:loading.remove wire:target="updatePassword">
          @if (session('status') === 'password-updated') ✓ Saved. @endif
        </div>
      </div>
    </form>
  </div>
</div>
