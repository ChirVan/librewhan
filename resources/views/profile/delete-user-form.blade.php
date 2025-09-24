<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">Delete Account</h5>
    <p class="text-muted small">
      Permanently delete your account. Once deleted, all data will be lost.
      Please download anything you want to keep before continuing.
    </p>

    <button class="btn btn-danger" type="button" x-data="{ open: @entangle('confirmingUserDeletion') }"
            x-on:click="open = true">
      Delete Account
    </button>

    {{-- Bootstrap modal controlled via Alpine entangled to Livewire property --}}
    <div x-data="{ show: @entangle('confirmingUserDeletion') }"
         x-init="$watch('show', value => {
                   if (value) { const m = new bootstrap.Modal($refs.modal); m.show(); $refs.password && setTimeout(()=>$refs.password.focus(),250); }
                   else { try { bootstrap.Modal.getInstance($refs.modal)?.hide(); } catch(e){} }
                 })">
      <div class="modal fade" tabindex="-1" role="dialog" x-ref="modal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Delete Account</h5>
              <button type="button" class="btn-close" aria-label="Close" x-on:click="show = false"></button>
            </div>
            <div class="modal-body">
              <p class="text-muted">
                Are you sure you want to delete your account? This action is permanent.
                Please confirm by entering your password.
              </p>

              <div class="mb-3">
                <input type="password"
                       class="form-control"
                       placeholder="Password"
                       x-ref="password"
                       wire:model="password"
                       wire:keydown.enter="deleteUser">
                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" x-on:click="show = false">Cancel</button>
              <button type="button" class="btn btn-danger"
                      wire:click="deleteUser"
                      wire:loading.attr="disabled">Delete Account</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
