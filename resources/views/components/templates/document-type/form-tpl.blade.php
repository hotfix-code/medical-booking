<template id="document-type-modal-template">
    <div>
        <div class="text-start mb-4">
            <label for="document-type-name" class="form-label">{{ __('document_types.fields.name') }}</label>
            <input type="text"
                   class="form-control"
                   id="document-type-name-input"
                   placeholder="{{ __('document_types.placeholders.name') }}">
        </div>
        <div class="text-start mb-4">
            <label for="document-type-code" class="form-label">{{ __('document_types.fields.code') }}</label>
            <input type="text"
                   class="form-control"
                   id="document-type-code-input"
                   placeholder="{{ __('document_types.placeholders.code') }}">
        </div>
    </div>
</template>
