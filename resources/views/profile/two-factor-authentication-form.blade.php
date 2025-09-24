<div class="card mb-3">
  <div class="card-body">
    <h5 class="card-title">Two Factor Authentication</h5>
    <p class="text-muted small">Add additional security to your account using two factor authentication.</p>

    @if ($this->enabled)
      @if ($showingConfirmation)
        <div class="alert alert-info">Finish enabling two factor authentication.</div>
      @else
        <div class="alert alert-success">You have enabled two factor authentication.</div>
      @endif
    @else
      <div class="alert alert-secondary">You have not enabled two factor authentication.</div>
    @endif

    @if ($this->enabled)
      @if ($showingQrCode)
        <div class="mb-3">
          <p class="small">
            @if ($showingConfirmation)
              To finish enabling 2FA, scan the QR code with your authenticator app and provide the OTP code.
            @else
              Two factor authentication is enabled. Scan the QR code or use the setup key.
            @endif
          </p>
        </div>

        <div class="mb-3 p-3 bg-white">
          {!! $this->user->twoFactorQrCodeSvg() !!}
        </div>

        <div class="mb-3">
          <p class="small"><strong>Setup Key:</strong> {{ decrypt($this->user->two_factor_secret) }}</p>
        </div>

        @if ($showingConfirmation)
          <div class="mb-3">
            <label class="form-label">Code</label>
            <input id="code" type="text" class="form-control w-50" inputmode="numeric" autocomplete="one-time-code"
                   wire:model="code" wire:keydown.enter="confirmTwoFactorAuthentication">
            @error('code') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            <div class="mt-2">
              <button class="btn btn-primary btn-sm" wire:click="confirmTwoFactorAuthentication">Confirm</button>
            </div>
          </div>
        @endif
      @endif

      @if ($showingRecoveryCodes)
        <div class="mb-3">
          <p class="small">Store these recovery codes in a secure place. They can be used to recover access if you lose your device.</p>
        </div>

        <div class="mb-3">
          <div class="bg-light p-3 font-monospace small">
            @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
              <div>{{ $code }}</div>
            @endforeach
          </div>
        </div>
      @endif
    @endif

    <div class="mt-3 d-flex gap-2 flex-wrap">
      @if (! $this->enabled)
        <button class="btn btn-primary" wire:click="enableTwoFactorAuthentication">Enable</button>
      @else
        @if ($showingRecoveryCodes)
          <button class="btn btn-outline-secondary" wire:click="regenerateRecoveryCodes">Regenerate Recovery Codes</button>
        @elseif ($showingConfirmation)
          <button class="btn btn-primary" wire:click="confirmTwoFactorAuthentication">Confirm</button>
        @else
          <button class="btn btn-outline-secondary" wire:click="showRecoveryCodes">Show Recovery Codes</button>
        @endif

        <button class="btn btn-danger" wire:click="disableTwoFactorAuthentication">Disable</button>
      @endif
    </div>
  </div>
</div>
