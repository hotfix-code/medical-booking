import {updatePasswordModal, updateProfileModal} from "@/modals/profile.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import flatpickr from "flatpickr";
import { t } from "@/utils/i18n.js";

$(function () {
    const roleFieldConfig = {
        'admin': ['firstname', 'lastname', 'email'],
        'doctor': ['firstname', 'lastname', 'email', 'document-type-id', 'document-number', 'license-number', 'phone'],
        'patient': ['firstname', 'lastname', 'email', 'gender', 'document-type-id', 'document-number', 'birthdate', 'phone'],
    };

    const buildFormData = (userRole) => {
        const form = document.querySelector(`#profile-form`);
        const data = {};

        roleFieldConfig[userRole].forEach(fieldName => {
            const elem = form.querySelector(`#input-${fieldName}`);
            if (elem && !elem.disabled) {
                data[fieldName.replaceAll('-', '_')] = elem.value;
            }
        });

        return data;
    }

    const selectElements = $('.select2');
    selectElements.each(function () {
        const select = $(this);
        const placeholder = select.data('placeholder');
        select.select2({
            placeholder: placeholder,
            allowClear: true,
            dir: 'ltr',
        });
    });

    $('#btn-save-changes').on('click', async function (e) {
        e.preventDefault();
        const role = $('#profile-form').data('role');
        const data = buildFormData(role);

        const { status, value } = await updateProfileModal(data);
        if (status === 'dismissed') return;
        const user = value.data;

        SuccessModal(t('profile.flash.updated_title'), t('profile.flash.updated'))
            .then(() => {
                // console.log(user);
            })
    })

    $('#btn-update-password').on('click', async function (e) {
        const data = {
            'password': $('#input-password').val(),
            'password_confirmation': $('#input-password-confirmation').val(),
        };

        const { status, value } = await updatePasswordModal(data);
        if (status === 'dismissed') return;
        SuccessModal(t('profile.flash.password_updated_title'), t('profile.flash.password_updated'))
            .then(() => {
                $('#input-password').val('');
                $('#input-password-confirmation').val('');
            });
    });

    const birthdate = $('#input-birthdate');
    if (birthdate.length) {
        flatpickr(birthdate);
    }
});
