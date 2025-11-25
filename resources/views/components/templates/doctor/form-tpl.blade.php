@props([
    'documentTypes',
    'specialties'
])

<template id="doctor-modal-template">
    <div class="row">
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-firstname-input" class="form-label">Firstname</label>
            <input type="text"
                   class="form-control"
                   id="doctor-firstname-input"
                   placeholder="Firstname">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-lastname-input" class="form-label">Lastname</label>
            <input type="text"
                   class="form-control"
                   id="doctor-lastname-input"
                   placeholder="Lastname">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-email-input" class="form-label">Email</label>
            <input type="email"
                   class="form-control"
                   id="doctor-email-input"
                   placeholder="Email">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-role-input" class="form-label">Role</label>
            <input type="text"
                   class="form-control"
                   id="doctor-role-input"
                   value="Doctor" disabled>
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-document-type" class="form-label">Doc. Type</label>
            <select class="form-control"
                    id="doctor-document-type-input">
                <option value="">Select Document Type</option>
                @foreach($documentTypes as $documentType)
                    <option value="{{ $documentType->id }}">
                        {{ $documentType->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-document-number" class="form-label">Doc. Number</label>
            <input type="text"
                   class="form-control"
                   id="doctor-document-number-input"
                   placeholder="Document Number">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-license-number" class="form-label">License Number</label>
            <input type="text"
                   class="form-control"
                   id="doctor-license-number-input"
                   placeholder="License Number">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-phone" class="form-label">Phone</label>
            <input type="text"
                   class="form-control"
                   id="doctor-phone-input"
                   placeholder="Phone (Optional)">
        </div>

        <div class="col-md-12 text-start mb-4">
            <label for="doctor-document-type" class="form-label">Specialties</label>
            <select class="form-control" name="choices-multiple-remove-button" id="choices-multiple-remove-button" multiple>
                @foreach($specialties as $specialty)
                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6 text-start mb-4">
            <label for="doctor-password" class="form-label">Password</label>
            <input type="password"
                   class="form-control"
                   id="doctor-password-input"
                   placeholder="Password">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="doctor-password-confirmation" class="form-label">Confirm Password</label>
            <input type="password"
                   class="form-control"
                   id="doctor-password-confirmation-input"
                   placeholder="Confirm Password">
        </div>
    </div>
</template>
