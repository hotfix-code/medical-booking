@use('App\Enums\Role')

@props([
    'documentTypes',
    'specialties'
])

<template id="doctor-modal-template">
    <div class="row">
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-firstname-input" class="form-label">{{ __('common.fields.firstname') }}</label>
            <input type="text"
                   class="form-control"
                   id="doctor-firstname-input"
                   placeholder="{{ __('common.fields.firstname') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-lastname-input" class="form-label">{{ __('common.fields.lastname') }}</label>
            <input type="text"
                   class="form-control"
                   id="doctor-lastname-input"
                   placeholder="{{ __('common.fields.lastname') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-email-input" class="form-label">{{ __('common.fields.email') }}</label>
            <input type="email"
                   class="form-control"
                   id="doctor-email-input"
                   placeholder="{{ __('common.fields.email') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-role-input" class="form-label">{{ __('common.fields.role') }}</label>
            <input type="text"
                   class="form-control"
                   id="doctor-role-input"
                   value="{{ Role::label(Role::Doctor->value) }}" disabled>
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-document-type" class="form-label">{{ __('doctors.fields.document_type') }}</label>
            <select class="form-control"
                    id="doctor-document-type-input">
                <option value="">{{ __('doctors.placeholders.select_document_type') }}</option>
                @foreach($documentTypes as $documentType)
                    <option value="{{ $documentType->id }}">
                        {{ $documentType->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-document-number" class="form-label">{{ __('doctors.fields.document_number') }}</label>
            <input type="text"
                   class="form-control"
                   id="doctor-document-number-input"
                   placeholder="{{ __('doctors.placeholders.document_number') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-license-number" class="form-label">{{ __('doctors.fields.license_number') }}</label>
            <input type="text"
                   class="form-control"
                   id="doctor-license-number-input"
                   placeholder="{{ __('doctors.placeholders.license_number') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-phone" class="form-label">{{ __('doctors.fields.phone') }}</label>
            <input type="text"
                   class="form-control"
                   id="doctor-phone-input"
                   placeholder="{{ __('doctors.placeholders.phone_optional') }}">
        </div>

        <div class="col-md-12 text-start mb-4">
            <label for="doctor-document-type" class="form-label">{{ __('doctors.fields.specialties') }}</label>
            <select class="form-control" name="choices-multiple-remove-button" id="choices-multiple-remove-button" multiple>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 text-start mb-4">
            <label for="doctor-password" class="form-label">{{ __('common.fields.password') }}</label>
            <input type="password"
                   class="form-control"
                   id="doctor-password-input"
                   placeholder="{{ __('common.fields.password') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-password-confirmation" class="form-label">{{ __('common.fields.password_confirmation') }}</label>
            <input type="password"
                   class="form-control"
                   id="doctor-password-confirmation-input"
                   placeholder="{{ __('common.fields.password_confirmation') }}">
        </div>
    </div>
</template>
