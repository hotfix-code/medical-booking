<template id="consulting-room-modal-template">
    <div>
        <div class="text-start mb-4">
            <label for="consulting-room-name" class="form-label">{{ __('consulting_rooms.fields.name') }}</label>
            <input type="text"
                   class="form-control"
                   id="consulting-room-name-input"
                   placeholder="{{ __('consulting_rooms.placeholders.name') }}">
        </div>
        <div class="text-start mb-4">
            <label for="consulting-room-location" class="form-label">{{ __('consulting_rooms.fields.location') }}</label>
            <input type="text"
                   class="form-control"
                   id="consulting-room-location-input"
                   placeholder="{{ __('consulting_rooms.placeholders.location_optional') }}">
        </div>
    </div>
</template>
