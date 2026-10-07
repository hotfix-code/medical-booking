@use('App\Enums\Role')

@props(['roles'])

<template id="user-modal-template">
    <div class="user-modal-container">
        <div class="text-start mb-4">
            <label for="user-firstname" class="form-label">{{ __('users.fields.firstname') }}</label>
            <input type="text"
                   class="form-control"
                   id="user-firstname-input"
                   placeholder="{{ __('users.fields.firstname') }}">
        </div>
        <div class="text-start mb-4">
            <label for="user-lastname" class="form-label">{{ __('users.fields.lastname') }}</label>
            <input type="text"
                   class="form-control"
                   id="user-lastname-input"
                   placeholder="{{ __('users.fields.lastname') }}">
        </div>
        <div class="text-start mb-4">
            <label for="user-email" class="form-label">{{ __('users.fields.email') }}</label>
            <input type="email"
                   class="form-control"
                   id="user-email-input"
                   placeholder="{{ __('users.fields.email') }}">
        </div>
        <div class="text-start mb-4">
            <label for="user-locale" class="form-label">{{ __('users.fields.role') }}</label>
            <select class="form-control"
                    id="user-role-input">
                <option value="">{{ __('users.placeholders.select_role') }}</option>
                @foreach($roles as $role)
                    <option value="{{ $role->uuid }}">
                        {{ Role::label($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="text-start mb-4">
            <label for="user-password" class="form-label">{{ __('users.fields.password') }}</label>
            <div class="input-group">
                <input type="password"
                       class="form-control"
                       id="user-password-input"
                       placeholder="{{ __('users.fields.password') }}">
                <button class="btn btn-light"
                        type="button"
                        onclick="showPassword('user-password-input', this)"
                        aria-label="{{ __('common.show_password') }}"
                        aria-pressed="false"
                        data-show-label="{{ __('common.show_password') }}"
                        data-hide-label="{{ __('common.hide_password') }}">
                    <i class="ri-eye-off-line align-middle" aria-hidden="true"></i>
                </button>
            </div>
        </div>
        <div class="text-start mb-4">
            <label for="user-password-confirmation" class="form-label">{{ __('users.fields.password_confirmation') }}</label>
            <div class="input-group">
                <input type="password"
                       class="form-control"
                       id="user-password-confirmation-input"
                       placeholder="{{ __('users.fields.password_confirmation') }}">
                <button class="btn btn-light"
                        type="button"
                        onclick="showPassword('user-password-confirmation-input', this)"
                        aria-label="{{ __('common.show_password') }}"
                        aria-pressed="false"
                        data-show-label="{{ __('common.show_password') }}"
                        data-hide-label="{{ __('common.hide_password') }}">
                    <i class="ri-eye-off-line align-middle" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</template>
