<div class="card custom-card shadow-none mb-0" id="profile-form" data-role="doctor">
    <div class="card-header justify-content-between">
        <div class="card-title">
            Personal Information
        </div>
    </div>
    <div class="card-body">
        <div class="row gy-3">
            <div class="col-xl-6">
                <label for="input-firstname" class="form-label">Firstname</label>
                <input type="text"
                       class="form-control"
                       id="input-firstname"
                       placeholder="Firstname"
                       value="{{ $user->firstname }}"
                >
            </div>
            <div class="col-xl-6">
                <label for="input-lastname" class="form-label">Lastname</label>
                <input type="text"
                       class="form-control"
                       id="input-lastname"
                       placeholder="Lastname"
                       value="{{ $user->lastname }}"
                >
            </div>
            <div class="col-xl-12">
                <label for="input-email" class="form-label">Email</label>
                <input type="text"
                       class="form-control"
                       id="input-email"
                       placeholder="Email"
                       value="{{ $user->email }}"
                >
            </div>
            <div class="col-xl-6">
                <label for="input-role" class="form-label">Role</label>
                <input type="text"
                       class="form-control"
                       id="input-role"
                       placeholder="Role"
                       value="{{ ucwords($user->role) }}"
                       disabled
                >
            </div>
            <div class="col-xl-6">
                <label for="input-license-number" class="form-label">License</label>
                <input type="text"
                       class="form-control"
                       id="input-license-number"
                       placeholder="License"
                       value="{{ $user->doctor->license_number }}"
                >
            </div>
            <div class="col-xl-6">
                <label for="input-document-type-id" class="form-label">Document Type</label>
                <select class="form-control select2"
                        id="input-document-type-id"
                        data-placeholder="Select Document Type"
                >
                    <option value="">Select Document Type</option>
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
                <label for="input-document-number" class="form-label">Document Number</label>
                <input type="text"
                       class="form-control"
                       id="input-document-number"
                       placeholder="Document Number"
                       value="{{ $user->doctor->document_number }}"
                >
            </div>
            <div class="col-xl-6">
                <label for="input-phone" class="form-label">Phone</label>
                <input type="text"
                       class="form-control"
                       id="input-phone"
                       placeholder="Phone"
                       value="{{ $user->doctor->phone }}"
                >
            </div>
            <div class="col-xl-6">
                <label for="input-language" class="form-label">Language</label>
                <input type="text"
                       class="form-control"
                       id="input-language"
                       placeholder="Language"
                       value="{{ $user->locale }}"
                       disabled
                >
            </div>
        </div>
    </div>
    <div class="card-footer border-0 text-center">
        <button id="btn-save-changes" class="btn btn-primary">
            Save Changes
        </button>
    </div>
</div>
