{{-- Bootstrap-friendly Livewire profile update form --}}
<form wire:submit.prevent="updateProfileInformation" class="card">
  <div class="card-body">
    <h5 class="card-title mb-3">Profile Information</h5>

    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
      <div class="mb-3" x-data="{ photoName: null, photoPreview: null }">
        <label class="form-label">Photo</label>

        {{-- file input (hidden) --}}
        <input type="file" id="photo" class="d-none"
               wire:model.live="photo"
               x-ref="photo"
               x-on:change="
                 photoName = $refs.photo.files[0].name;
                 const reader = new FileReader();
                 reader.onload = (e) => { photoPreview = e.target.result; };
                 reader.readAsDataURL($refs.photo.files[0]);
               " />

        {{-- current photo --}}
        <div class="d-flex align-items-center gap-3 mt-2">
          <div>
            <div class="rounded-circle overflow-hidden" style="width:80px;height:80px;">
              <img
                x-show="!photoPreview"
                :src="'{{ $this->user->profile_photo_url }}'"
                alt="{{ $this->user->name }}"
                style="width:100%;height:100%;object-fit:cover;"
              />
              <span x-show="photoPreview" style="display:none"
                    x-bind:style="'background-image: url(' + photoPreview + '); display:inline-block; width:80px; height:80px; background-size:cover; background-position:center; border-radius:50%;'">
              </span>
            </div>
          </div>

          <div>
            <button type="button" class="btn btn-outline-secondary btn-sm me-2" x-on:click.prevent="$refs.photo.click()">
              Select New Photo
            </button>

            @if ($this->user->profile_photo_path)
              <button type="button" wire:click="deleteProfilePhoto" class="btn btn-light btn-sm">
                Remove Photo
              </button>
            @endif

            @error('photo') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
          </div>
        </div>
      </div>
    @endif

    <div class="mb-3">
      <label class="form-label">Name</label>
      <input type="text" wire:model.defer="state.name" class="form-control" required />
      <div class="text-danger small">@error('state.name') {{ $message }} @enderror</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Email</label>
      <input type="email" wire:model.defer="state.email" class="form-control" required />
      <div class="text-danger small">@error('state.email') {{ $message }} @enderror</div>

      @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
        <div class="mt-2">
          <small class="text-muted">Your email address is unverified.</small>
          <button type="button" class="btn btn-link btn-sm p-0 ms-2"
                  wire:click.prevent="sendEmailVerification">Resend verification</button>

          @if ($this->verificationLinkSent)
            <div class="text-success small mt-2">A new verification link has been sent.</div>
          @endif
        </div>
      @endif
    </div>

    <div class="d-flex align-items-center">
      <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">Save</button>
      <div class="ms-3 text-success small" wire:loading.remove wire:target="updateProfileInformation">
        @if (session('status') === 'profile-updated') Saved. @endif
      </div>
    </div>
  </div>
</form>
