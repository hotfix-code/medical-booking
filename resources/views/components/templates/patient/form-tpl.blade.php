@props(['documentTypes'])

<template id="patient-modal-template">
    <div class="row">
        <div class="col-md-6 text-start mb-4">
            <label for="patient-firstname-input" class="form-label">Firstname</label>
            <input type="text"
                   class="form-control"
                   id="patient-firstname-input"
                   placeholder="Firstname">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-lastname-input" class="form-label">Lastname</label>
            <input type="text"
                   class="form-control"
                   id="patient-lastname-input"
                   placeholder="Lastname">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-email-input" class="form-label">Email</label>
            <input type="email"
                   class="form-control"
                   id="patient-email-input"
                   placeholder="Email">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-gender" class="form-label">Gender</label>
            <select class="form-control"
                    id="patient-gender-input">
                <option value="">Select Gender (Optional)</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-document-type" class="form-label">Document Type</label>
            <select class="form-control"
                    id="patient-document-type-input">
                <option value="">Select Document Type</option>
                @foreach($documentTypes as $documentType)
                    <option value="{{ $documentType->id }}">
                        {{ $documentType->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-document-number" class="form-label">Document Number</label>
            <input type="text"
                   class="form-control"
                   id="patient-document-number-input"
                   placeholder="Document Number">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-birthdate" class="form-label">Birthdate</label>
            <input type="text"
                   class="form-control"
                   id="patient-birthdate-input"
                   placeholder="YYYY-MM-DD"
                   readonly>
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-phone" class="form-label">Phone</label>
            <input type="text"
                   class="form-control"
                   id="patient-phone-input"
                   placeholder="Phone (Optional)">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-password" class="form-label">Password</label>
            <input type="password"
                   class="form-control"
                   id="patient-password-input"
                   placeholder="Password">
        </div>
        <div class="col-md-6 text-start mb-4">
            <label for="patient-password-confirmation" class="form-label">Confirm Password</label>
            <input type="password"
                   class="form-control"
                   id="patient-password-confirmation-input"
                   placeholder="Confirm Password">
        </div>
    </div>
</template>
