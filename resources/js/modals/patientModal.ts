import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {PatientService} from "@/services/patient.service.ts";
import {Header} from "@/components/modal";
import flatpickr from "flatpickr";

export const createPatientModal = async () => {
    const tpl = document.getElementById('patient-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Create new patient?'),
        html: formHtml,
        width: 800,
        didOpen: async () => {
            const dp = $('.swal2-container');
            const swal = document.querySelector('.swal2-container') as HTMLInputElement;
            (document.getElementById('patient-firstname-input') as HTMLInputElement).focus();
            const birthdate = document.getElementById('patient-birthdate-input') as HTMLInputElement;

            $('#patient-document-type-input')
                .select2({
                    placeholder: 'Select document type',
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                });

            $('#patient-gender-input')
                .select2({
                    placeholder: 'Select gender',
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
                gender: (document.getElementById('patient-gender-input') as HTMLSelectElement).value as 'male' | 'female' | 'other' || undefined,
                birthdate: (document.getElementById('patient-birthdate-input') as HTMLInputElement).value || undefined,
                phone: (document.getElementById('patient-phone-input') as HTMLInputElement).value.trim() || undefined,
                password: (document.getElementById('patient-password-input') as HTMLInputElement).value,
                password_confirmation: (document.getElementById('patient-password-confirmation-input') as HTMLInputElement).value,
            });
            return data;
        },
    });
}

export const updatePatientModal = async (id: string) => {
    await LoadingModal('Loading Patient data...');
    const { data } = await PatientService.fetch(id);
    const patient = data.data;

    const tpl = document.getElementById('patient-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Edit patient?'),
        html: formHtml,
        width: 800,
        didOpen: async () => {
            const dp = $('.swal2-container');
            const swal = document.querySelector('.swal2-container') as HTMLInputElement;

            $('#patient-document-type-input')
                .select2({
                    placeholder: 'Select document type',
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                })
                .val(patient.document_type_id)
                .trigger('change');

            $('#patient-gender-input')
                .select2({
                    placeholder: 'Select gender',
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
            if (passwordInput) passwordInput.placeholder = 'Leave blank to keep current password';
            if (passwordConfirmationInput) passwordConfirmationInput.placeholder = 'Leave blank to keep current password';

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
                gender: (document.getElementById('patient-gender-input') as HTMLSelectElement).value as 'male' | 'female' | 'other' || undefined,
                birthdate: (document.getElementById('patient-birthdate-input') as HTMLInputElement).value || undefined,
                phone: (document.getElementById('patient-phone-input') as HTMLInputElement).value.trim() || undefined,
            };

            // Only include password if provided
            if (passwordValue) {
                payload.password = passwordValue;
                payload.password_confirmation = passwordConfirmationValue;
            }

            const { data } = await PatientService.update(id, payload);
            return data;
        },
        confirmButtonText: 'Save Changes',
    });
};

export const deletePatientModal = async (id: string) => {
    return ShowModal({
        title: Header('Delete patient?', 'danger'),
        html: `<span style="font-size: 0.85rem;">Are you sure you want to delete the patient?</span>`,
        confirmButtonText: 'Delete',
        preConfirm: async () => {
            const { data } = await PatientService.delete(id);
            return data;
        },
    });
}
