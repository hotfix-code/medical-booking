@props(['documentTypes'])

<template id="patient-modal-template">
    <div class="row">
        <div class="col-md-6 text-start mb-4">
            <label for="patient-firstname-input" class="form-label">{{ __('common.fields.firstname') }}</label>
            <input type="text"
                   class="form-control"
                   id="patient-firstname-input"
                   placeholder="{{ __('common.fields.firstname') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-lastname-input" class="form-label">{{ __('common.fields.lastname') }}</label>
            <input type="text"
                   class="form-control"
                   id="patient-lastname-input"
                   placeholder="{{ __('common.fields.lastname') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-email-input" class="form-label">{{ __('common.fields.email') }}</label>
            <input type="email"
                   class="form-control"
                   id="patient-email-input"
                   placeholder="{{ __('common.fields.email') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-gender" class="form-label">{{ __('patients.fields.gender') }}</label>
            <select class="form-control"
                    id="patient-gender-input">
                <option value="">{{ __('patients.placeholders.select_gender') }}</option>
                <option value="male">{{ __('enums.gender.male') }}</option>
                <option value="female">{{ __('enums.gender.female') }}</option>
                <option value="other">{{ __('enums.gender.other') }}</option>
            </select>
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-document-type" class="form-label">{{ __('patients.fields.document_type') }}</label>
            <select class="form-control"
                    id="patient-document-type-input">
                <option value="">{{ __('patients.placeholders.select_document_type') }}</option>
                @foreach($documentTypes as $documentType)
                    <option value="{{ $documentType->id }}">
                        {{ $documentType->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-document-number" class="form-label">{{ __('patients.fields.document_number') }}</label>
            <input type="text"
                   class="form-control"
                   id="patient-document-number-input"
                   placeholder="{{ __('patients.fields.document_number') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-birthdate" class="form-label">{{ __('patients.fields.birthdate') }}</label>
            <input type="text"
                   class="form-control"
                   id="patient-birthdate-input"
                   placeholder="{{ __('patients.placeholders.birthdate') }}"
                   readonly>
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-phone" class="form-label">{{ __('patients.fields.phone') }}</label>
            <input type="text"
                   class="form-control"
                   id="patient-phone-input"
                   placeholder="{{ __('patients.placeholders.phone_optional') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-password" class="form-label">{{ __('common.fields.password') }}</label>
            <input type="password"
                   class="form-control"
                   id="patient-password-input"
                   placeholder="{{ __('common.fields.password') }}">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-password-confirmation" class="form-label">{{ __('common.fields.password_confirmation') }}</label>
            <input type="password"
                   class="form-control"
                   id="patient-password-confirmation-input"
                   placeholder="{{ __('common.fields.password_confirmation') }}">
        </div>
    </div>
</template>
