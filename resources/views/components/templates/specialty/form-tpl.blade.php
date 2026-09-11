<template id="specialty-modal-template">
    <div>
        <div class="text-start mb-4">
            <label for="specialty-name" class="form-label">{{ __('specialties.fields.name') }}</label>
            <input type="text"
                   class="form-control"
                   id="specialty-name-input"
                   placeholder="{{ __('specialties.placeholders.name') }}">
        </div>
        <div class="text-start mb-4">
            <label for="specialty-description" class="form-label">{{ __('specialties.fields.description') }}</label>
            <textarea class="form-control"
                      id="specialty-description-input"
                      rows="4"
                      placeholder="{{ __('specialties.placeholders.description_optional') }}"></textarea>
        </div>
    </div>
</template>
