@props(['roles'])

<template id="user-modal-template">
    <div class="user-modal-container">
        <div class="text-start mb-4">
            <label for="user-firstname" class="form-label">First Name</label>
            <input type="text"
                   class="form-control"
                   id="user-firstname-input"
                   placeholder="First Name">
        </div>
        <div class="text-start mb-4">
            <label for="user-lastname" class="form-label">Last Name</label>
            <input type="text"
                   class="form-control"
                   id="user-lastname-input"
                   placeholder="Last Name">
        </div>
        <div class="text-start mb-4">
            <label for="user-email" class="form-label">Email</label>
            <input type="email"
                   class="form-control"
                   id="user-email-input"
                   placeholder="Email">
        </div>
        <div class="text-start mb-4">
            <label for="user-locale" class="form-label">Role</label>
            <select class="form-control"
                    id="user-role-input">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->uuid }}">
                        {{ ucwords($role->name) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="text-start mb-4">
            <label for="user-password" class="form-label">Password</label>
            <input type="password"
                   class="form-control"
                   id="user-password-input"
                   placeholder="Password">
        </div>
        <div class="text-start mb-4">
            <label for="user-password-confirmation" class="form-label">Confirm Password</label>
            <input type="password"
                   class="form-control"
                   id="user-password-confirmation-input"
                   placeholder="Confirm Password">
        </div>
    </div>
</template>
