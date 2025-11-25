<div class="card custom-card shadow-none mb-0" id="profile-form" data-role="admin">
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
                       value="{{ $user->role }}"
                       disabled
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
