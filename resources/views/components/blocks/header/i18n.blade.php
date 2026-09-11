@php
    $i18n = [
        'common' => __('common'),
        'messages' => __('messages'),
        'appointments' => __('appointments'),
        'patients' => __('patients'),
        'doctors' => __('doctors'),
        'schedules' => __('schedules'),
        'consulting_rooms' => __('consulting_rooms'),
        'specialties' => __('specialties'),
        'users' => __('users'),
        'roles' => __('roles'),
        'document_types' => __('document_types'),
        'profile' => __('profile'),
        'enums' => __('enums'),
    ];
@endphp
<script>
    window.i18n = @json($i18n);
</script>
