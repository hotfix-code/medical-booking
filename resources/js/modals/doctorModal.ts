import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {DoctorService} from "@/services/doctor.service.ts";
import {Header} from "@/components/modal";
import { t } from '@/utils/i18n';

declare const Choices: any;

export const createDoctorModal = async () => {
    const tpl = document.getElementById('doctor-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('doctors.modals.create')),
        html: formHtml,
        width: 800,
        didOpen: async () => {
            const dp = $('.swal2-container');

            const specialtiesSelect = document.getElementById('choices-multiple-remove-button');
            if (specialtiesSelect) {
                new Choices(specialtiesSelect, { allowHTML: true, removeItemButton: true, });
            }

            $('#doctor-document-type-input')
                .select2({
                    placeholder: t('doctors.placeholders.select_document_type'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                });

            (document.getElementById('doctor-firstname-input') as HTMLInputElement).focus();
        },
        preConfirm: async () => {
            const specialtiesSelect = document.getElementById('choices-multiple-remove-button') as HTMLSelectElement;
            const selectedSpecialties: string[] = Array.from(specialtiesSelect.selectedOptions).map(option => option.value);

            const { data } = await DoctorService.create({
                firstname: (document.getElementById('doctor-firstname-input') as HTMLInputElement).value.trim(),
                lastname: (document.getElementById('doctor-lastname-input') as HTMLInputElement).value.trim(),
                email: (document.getElementById('doctor-email-input') as HTMLInputElement).value.trim(),
                password: (document.getElementById('doctor-password-input') as HTMLInputElement).value,
                password_confirmation: (document.getElementById('doctor-password-confirmation-input') as HTMLInputElement).value,
                document_type_id: (document.getElementById('doctor-document-type-input') as HTMLSelectElement).value,
                document_number: (document.getElementById('doctor-document-number-input') as HTMLInputElement).value.trim(),
                license_number: (document.getElementById('doctor-license-number-input') as HTMLInputElement).value.trim(),
                phone: (document.getElementById('doctor-phone-input') as HTMLInputElement).value.trim() || null,
                specialties: selectedSpecialties,
            });
            return data;
        },
    });
}

export const updateDoctorModal = async (id: string) => {
    await LoadingModal(t('messages.loading_resource', { resource: t('doctors.resource') }));
    const { data } = await DoctorService.fetch(id);
    const doctor = data.data;

    const tpl = document.getElementById('doctor-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('doctors.modals.edit')),
        html: formHtml,
        width: 800,
        didOpen: async () => {
            const dp = $('.swal2-container');

            const documentTypeSelect = $('#doctor-document-type-input')
                .select2({
                    placeholder: t('doctors.placeholders.select_document_type'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                });

            const specialtiesSelect = document.getElementById('choices-multiple-remove-button');
            let choicesInstance;
            if (specialtiesSelect) {
                choicesInstance = new Choices(specialtiesSelect, { allowHTML: true, removeItemButton: true });
            }

            const firstnameInput = document.getElementById('doctor-firstname-input') as HTMLInputElement;
            const lastnameInput = document.getElementById('doctor-lastname-input') as HTMLInputElement;
            const emailInput = document.getElementById('doctor-email-input') as HTMLInputElement;
            const documentTypeInput = document.getElementById('doctor-document-type-input') as HTMLSelectElement;
            const documentNumberInput = document.getElementById('doctor-document-number-input') as HTMLInputElement;
            const licenseNumberInput = document.getElementById('doctor-license-number-input') as HTMLInputElement;
            const phoneInput = document.getElementById('doctor-phone-input') as HTMLInputElement;
            const passwordInput = document.getElementById('doctor-password-input') as HTMLInputElement;
            const passwordConfirmationInput = document.getElementById('doctor-password-confirmation-input') as HTMLInputElement;

            if (firstnameInput && doctor.user) firstnameInput.value = doctor.user.firstname;
            if (lastnameInput && doctor.user) lastnameInput.value = doctor.user.lastname;
            if (emailInput && doctor.user) emailInput.value = doctor.user.email;

            if (documentTypeInput) {
                documentTypeSelect.val(doctor.document_type_id).trigger('change');
            }

            if (documentNumberInput) documentNumberInput.value = doctor.document_number;
            if (licenseNumberInput) licenseNumberInput.value = doctor.license_number;
            if (phoneInput) phoneInput.value = doctor.phone || '';

            // Set selected specialties if they exist
            if (choicesInstance && doctor.specialties && doctor.specialties.length > 0) {
                const specialtyIds = doctor.specialties.map((specialty: any) => specialty.id);
                choicesInstance.setChoiceByValue(specialtyIds);
            }

            // Make password fields optional for updates
            if (passwordInput) passwordInput.placeholder = t('common.placeholders.password_unchanged');
            if (passwordConfirmationInput) passwordConfirmationInput.placeholder = t('common.placeholders.password_unchanged');

            firstnameInput.focus();
        },
        preConfirm: async () => {
            const passwordValue = (document.getElementById('doctor-password-input') as HTMLInputElement).value;
            const passwordConfirmationValue = (document.getElementById('doctor-password-confirmation-input') as HTMLInputElement).value;

            // Get selected specialties from Choices.js
            const specialtiesSelect = document.getElementById('choices-multiple-remove-button') as HTMLSelectElement;
            const selectedSpecialties = Array.from(specialtiesSelect.selectedOptions).map(option => option.value);

            const payload: any = {
                firstname: (document.getElementById('doctor-firstname-input') as HTMLInputElement).value.trim(),
                lastname: (document.getElementById('doctor-lastname-input') as HTMLInputElement).value.trim(),
                email: (document.getElementById('doctor-email-input') as HTMLInputElement).value.trim(),
                document_type_id: (document.getElementById('doctor-document-type-input') as HTMLSelectElement).value,
                document_number: (document.getElementById('doctor-document-number-input') as HTMLInputElement).value.trim(),
                license_number: (document.getElementById('doctor-license-number-input') as HTMLInputElement).value.trim(),
                phone: (document.getElementById('doctor-phone-input') as HTMLInputElement).value.trim() || null,
                specialties: selectedSpecialties,
            };

            // Only include password if provided
            if (passwordValue) {
                payload.password = passwordValue;
                payload.password_confirmation = passwordConfirmationValue;
            }

            const { data } = await DoctorService.update(id, payload);
            return data;
        },
        confirmButtonText: t('common.actions.save_changes'),
    });
};

export const deleteDoctorModal = async (id: string) => {
    return ShowModal({
        title: Header(t('doctors.modals.delete'), 'danger'),
        html: `<span style="font-size: 0.85rem;">${t('messages.confirm_delete', { resource: t('doctors.resource') })}</span>`,
        confirmButtonText: t('common.actions.delete'),
        preConfirm: async () => {
            const { data } = await DoctorService.delete(id);
            return data;
        },
    });
}
