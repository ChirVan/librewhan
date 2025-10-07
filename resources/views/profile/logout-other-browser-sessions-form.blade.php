<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">Browser Sessions</h5>
    <p class="text-muted small">
      Manage and log out your active sessions on other browsers and devices.
    </p>

    @if (count($this->sessions) > 0)
      <div class="mb-3">
        @foreach ($this->sessions as $session)
          <div class="d-flex align-items-center mb-2">
            <div class="me-3">
              @if ($session->agent->isDesktop())
                <i class="fas fa-desktop fa-lg text-secondary"></i>
              @else
                <i class="fas fa-mobile-alt fa-lg text-secondary"></i>
              @endif
            </div>
            <div>
              <div class="small">{{ $session->agent->platform() ?? 'Unknown' }} — {{ $session->agent->browser() ?? 'Unknown' }}</div>
              <div class="small text-muted">
                {{ $session->ip_address }},
                @if ($session->is_current_device)
                  <span class="badge bg-success text-white">This device</span>
                @else
                  Last active {{ $session->last_active }}
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="mb-3 text-muted small">No other sessions found.</div>
    @endif

    <div class="d-flex align-items-center">
      <button class="btn btn-outline-primary" type="button" wire:click="$set('confirmingLogout', true)">Log Out Other Browser Sessions</button>
      {{-- $wire.confirmLogout() — Jetstream Livewire components usually provide a method to open the confirmation; if your Livewire class uses a different method name, change the call to match (confirmLogout vs confirmingLogout). If no such method exists, clicking the button can be changed to x-on:click="show = true" and @entangle can be used to bind confirmingLogout. --}}

      {{-- Modal for password confirmation --}}
      <div x-data="{ show: @entangle('confirmingLogout') }"
           x-init="$watch('show', value => {
                     if (value) { const m = new bootstrap.Modal($refs.modal); m.show(); $refs.password && setTimeout(()=>$refs.password.focus(),250); }
                     else { try{ bootstrap.Modal.getInstance($refs.modal)?.hide(); } catch(e){} }
                   })">
        <div class="modal fade" tabindex="-1" x-ref="modal">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Log Out Other Browser Sessions</h5>
                <button type="button" class="btn-close" aria-label="Close" x-on:click="show = false"></button>
              </div>
              <div class="modal-body">
                <p class="text-muted">Please enter your password to confirm you want to log out from other sessions.</p>
                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="Password" x-ref="password"
                         wire:model="password" wire:keydown.enter="logoutOtherBrowserSessions" />
                  @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" x-on:click="show = false">Cancel</button>
                <button type="button" class="btn btn-primary"
                        wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled">
                  Log Out Other Browser Sessions
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="ms-3">
        <span class="text-success small" wire:loading.remove wire:target="logoutOtherBrowserSessions">
          <x-action-message on="loggedOut">Done.</x-action-message>
        </span>
      </div>
    </div>
  </div>
</div>
