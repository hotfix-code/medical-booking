<div class="card custom-card shadow-none">
    <div class="card-header justify-content-between">
        <div class="card-title">
            {{ __('profile.password_change') }}
        </div>
    </div>
    <div class="card-body">
        <div class="row gy-3">
            <div class="col-xl-6">
                <label for="input-password" class="form-label">{{ __('profile.new_password') }}</label>
                <input type="password"
                       class="form-control"
                       id="input-password"
                       placeholder="{{ __('profile.new_password') }}"
                >
            </div>
            <div class="col-xl-6">
                <label for="input-password-confirmation" class="form-label">{{ __('profile.confirm_new_password') }}</label>
                <input type="password"
                       class="form-control"
                       id="input-password-confirmation"
                       placeholder="{{ __('profile.confirm_new_password') }}"
                >
            </div>
        </div>
    </div>
    <div class="card-footer border-top-0 text-center">
        <button id="btn-update-password" class="btn btn-primary">
            {{ __('profile.update_password') }}
        </button>
    </div>
</div>
