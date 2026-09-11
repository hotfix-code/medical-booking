import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {PatientService} from "@/services/patient.service.ts";
import {Header} from "@/components/modal";
import flatpickr from "flatpickr";
import { t } from '@/utils/i18n';

export const createPatientModal = async () => {
    const tpl = document.getElementById('patient-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('patients.modals.create')),
        html: formHtml,
        width: 800,
        didOpen: async () => {
            const dp = $('.swal2-container');
            const swal = document.querySelector('.swal2-container') as HTMLInputElement;
            (document.getElementById('patient-firstname-input') as HTMLInputElement).focus();
            const birthdate = document.getElementById('patient-birthdate-input') as HTMLInputElement;

            $('#patient-document-type-input')
                .select2({
                    placeholder: t('patients.placeholders.select_document_type'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                });

            $('#patient-gender-input')
                .select2({
                    placeholder: t('patients.placeholders.select_gender'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                });

            flatpickr(birthdate, {
                appendTo: swal,
            });
        },
        preConfirm: async () => {
            const { data } = await PatientService.create({
                firstname: (document.getElementById('patient-firstname-input') as HTMLInputElement).value.trim(),
                lastname: (document.getElementById('patient-lastname-input') as HTMLInputElement).value.trim(),
                email: (document.getElementById('patient-email-input') as HTMLInputElement).value.trim(),
                document_type_id: (document.getElementById('patient-document-type-input') as HTMLSelectElement).value,
                document_number: (document.getElementById('patient-document-number-input') as HTMLInputElement).value.trim(),
                gender: (document.getElementById('patient-gender-input') as HTMLSelectElement).value as 'male' | 'female' | 'other' || null,
                birthdate: (document.getElementById('patient-birthdate-input') as HTMLInputElement).value || null,
                phone: (document.getElementById('patient-phone-input') as HTMLInputElement).value.trim() || null,
                password: (document.getElementById('patient-password-input') as HTMLInputElement).value,
                password_confirmation: (document.getElementById('patient-password-confirmation-input') as HTMLInputElement).value,
            });
            return data;
        },
    });
}

export const updatePatientModal = async (id: string) => {
    await LoadingModal(t('messages.loading_resource', { resource: t('patients.resource') }));
    const { data } = await PatientService.fetch(id);
    const patient = data.data;

    const tpl = document.getElementById('patient-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('patients.modals.edit')),
        html: formHtml,
        width: 800,
        didOpen: async () => {
            const dp = $('.swal2-container');
            const swal = document.querySelector('.swal2-container') as HTMLInputElement;

            $('#patient-document-type-input')
                .select2({
                    placeholder: t('patients.placeholders.select_document_type'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                })
                .val(patient.document_type_id)
                .trigger('change');

            $('#patient-gender-input')
                .select2({
                    placeholder: t('patients.placeholders.select_gender'),
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                })
                .val(patient.gender)
                .trigger('change');

            flatpickr('#patient-birthdate-input', {
                appendTo: swal,
            });

            const firstnameInput = document.getElementById('patient-firstname-input') as HTMLInputElement;
            const lastnameInput = document.getElementById('patient-lastname-input') as HTMLInputElement;
            const emailInput = document.getElementById('patient-email-input') as HTMLInputElement;
            const documentTypeInput = document.getElementById('patient-document-type-input') as HTMLSelectElement;
            const documentNumberInput = document.getElementById('patient-document-number-input') as HTMLInputElement;
            const genderInput = document.getElementById('patient-gender-input') as HTMLSelectElement;
            const birthdateInput = document.getElementById('patient-birthdate-input') as HTMLInputElement;
            const phoneInput = document.getElementById('patient-phone-input') as HTMLInputElement;
            const passwordInput = document.getElementById('patient-password-input') as HTMLInputElement;
            const passwordConfirmationInput = document.getElementById('patient-password-confirmation-input') as HTMLInputElement;

            if (firstnameInput && patient.user) firstnameInput.value = patient.user.firstname;
            if (lastnameInput && patient.user) lastnameInput.value = patient.user.lastname;
            if (emailInput && patient.user) emailInput.value = patient.user.email;
            if (documentTypeInput) documentTypeInput.value = patient.document_type_id;
            if (documentNumberInput) documentNumberInput.value = patient.document_number;
            if (genderInput) genderInput.value = patient.gender || '';
            if (birthdateInput) birthdateInput.value = patient.birthdate || '';
            if (phoneInput) phoneInput.value = patient.phone || '';

            // Make password fields optional for updates
            if (passwordInput) passwordInput.placeholder = t('common.placeholders.password_unchanged');
            if (passwordConfirmationInput) passwordConfirmationInput.placeholder = t('common.placeholders.password_unchanged');

            firstnameInput.focus();
        },
        preConfirm: async () => {
            const passwordValue = (document.getElementById('patient-password-input') as HTMLInputElement).value;
            const passwordConfirmationValue = (document.getElementById('patient-password-confirmation-input') as HTMLInputElement).value;

            const payload: any = {
                firstname: (document.getElementById('patient-firstname-input') as HTMLInputElement).value.trim(),
                lastname: (document.getElementById('patient-lastname-input') as HTMLInputElement).value.trim(),
                email: (document.getElementById('patient-email-input') as HTMLInputElement).value.trim(),
                document_type_id: (document.getElementById('patient-document-type-input') as HTMLSelectElement).value,
                document_number: (document.getElementById('patient-document-number-input') as HTMLInputElement).value.trim(),
                gender: (document.getElementById('patient-gender-input') as HTMLSelectElement).value as 'male' | 'female' | 'other' || null,
                birthdate: (document.getElementById('patient-birthdate-input') as HTMLInputElement).value || null,
                phone: (document.getElementById('patient-phone-input') as HTMLInputElement).value.trim() || null,
            };

            // Only include password if provided
            if (passwordValue) {
                payload.password = passwordValue;
                payload.password_confirmation = passwordConfirmationValue;
            }

            const { data } = await PatientService.update(id, payload);
            return data;
        },
        confirmButtonText: t('common.actions.save_changes'),
    });
};

export const deletePatientModal = async (id: string) => {
    return ShowModal({
        title: Header(t('patients.modals.delete'), 'danger'),
        html: `<span style="font-size: 0.85rem;">${t('messages.confirm_delete', { resource: t('patients.resource') })}</span>`,
        confirmButtonText: t('common.actions.delete'),
        preConfirm: async () => {
            const { data } = await PatientService.delete(id);
            return data;
        },
    });
}
