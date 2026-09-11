@use('App\Enums\Role')
<div class="card custom-card shadow-none mb-0" id="profile-form" data-role="admin">
    <div class="card-header justify-content-between">
        <div class="card-title">
            {{ __('profile.personal_info') }}
        </div>
    </div>
    <div class="card-body">
        <div class="row gy-3">
            <div class="col-xl-6">
                <label for="input-firstname" class="form-label">{{ __('common.fields.firstname') }}</label>
                <input type="text"
                       class="form-control"
                       id="input-firstname"
                       placeholder="{{ __('common.fields.firstname') }}"
                       value="{{ $user->firstname }}"
                >
            </div>
            <div class="col-xl-6">
                <label for="input-lastname" class="form-label">{{ __('common.fields.lastname') }}</label>
                <input type="text"
                       class="form-control"
                       id="input-lastname"
                       placeholder="{{ __('common.fields.lastname') }}"
                       value="{{ $user->lastname }}"
                >
            </div>
            <div class="col-xl-12">
                <label for="input-email" class="form-label">{{ __('common.fields.email') }}</label>
                <input type="text"
                       class="form-control"
                       id="input-email"
                       placeholder="{{ __('common.fields.email') }}"
                       value="{{ $user->email }}"
                >
            </div>
            <div class="col-xl-6">
                <label for="input-role" class="form-label">{{ __('common.fields.role') }}</label>
                <input type="text"
                       class="form-control"
                       id="input-role"
                       placeholder="{{ __('common.fields.role') }}"
                       value="{{ Role::label($user->role) }}"
                       disabled
                >
            </div>
            <div class="col-xl-6">
                <label for="input-language" class="form-label">{{ __('common.fields.language') }}</label>
                <input type="text"
                       class="form-control"
                       id="input-language"
                       placeholder="{{ __('common.fields.language') }}"
                       value="{{ $user->locale }}"
                       disabled
                >
            </div>
        </div>
    </div>
    <div class="card-footer border-0 text-center">
        <button id="btn-save-changes" class="btn btn-primary">
            {{ __('profile.save_changes') }}
        </button>
    </div>
</div>
