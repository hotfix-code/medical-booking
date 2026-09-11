@use('App\Enums\Role')
<div class="card custom-card shadow-none mb-0" id="profile-form" data-role="doctor">
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
                <label for="input-license-number" class="form-label">{{ __('common.fields.license') }}</label>
                <input type="text"
                       class="form-control"
                       id="input-license-number"
                       placeholder="{{ __('common.fields.license') }}"
                       value="{{ $user->doctor->license_number }}"
                >
            </div>
            <div class="col-xl-6">
                <label for="input-document-type-id" class="form-label">{{ __('common.fields.document_type') }}</label>
                <select class="form-control select2"
                        id="input-document-type-id"
                        data-placeholder="{{ __('patients.placeholders.select_document_type') }}"
                >
                    <option value="">{{ __('patients.placeholders.select_document_type') }}</option>
                    @foreach($documentTypes as $documentType)
                        <option value="{{ $documentType->id }}"
                            {{ $documentType->id == $user->doctor->document_type_id ? 'selected' : '' }}
                        >
                            {{ $documentType->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-xl-6">
                <label for="input-document-number" class="form-label">{{ __('common.fields.document_number') }}</label>
                <input type="text"
                       class="form-control"
                       id="input-document-number"
                       placeholder="{{ __('common.fields.document_number') }}"
                       value="{{ $user->doctor->document_number }}"
                >
            </div>
            <div class="col-xl-6">
                <label for="input-phone" class="form-label">{{ __('common.fields.phone') }}</label>
                <input type="text"
                       class="form-control"
                       id="input-phone"
                       placeholder="{{ __('common.fields.phone') }}"
                       value="{{ $user->doctor->phone }}"
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
