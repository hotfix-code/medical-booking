import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {UserService} from "@/services/user.service.ts";
import {Header} from "@/components/modal";

export const createUserModal = async () => {
    const tpl = document.getElementById('user-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Create new user?'),
        html: formHtml,
        didOpen: async () => {
            const dp = $('.swal2-container');
            (document.getElementById('user-firstname-input') as HTMLInputElement).focus();

            $('#user-role-input')
                .select2({
                    placeholder: 'Select Role',
                    allowClear: true,
                    dir: 'ltr',
                    dropdownParent: dp,
                });
        },
        preConfirm: async () => {
            const { data } = await UserService.create({
                firstname: (document.getElementById('user-firstname-input') as HTMLInputElement).value.trim(),
                lastname: (document.getElementById('user-lastname-input') as HTMLInputElement).value.trim(),
                email: (document.getElementById('user-email-input') as HTMLInputElement).value.trim(),
                role_id: (document.getElementById('user-role-input') as HTMLSelectElement).value,
                password: (document.getElementById('user-password-input') as HTMLInputElement).value,
                password_confirmation: (document.getElementById('user-password-confirmation-input') as HTMLInputElement).value,
            });
            return data;
        },
    });
}

export const updateUserModal = async (id: string) => {
    await LoadingModal('Loading User data...');
    const { data } = await UserService.fetch(id);
    const user = data.data;
    const isSuperAdmin = user.roles.some((role: { name: string; }) => role.name === 'super-admin');

    const tpl = document.getElementById('user-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Edit user?'),
        html: formHtml,
        showConfirmButton: !isSuperAdmin,
        didOpen: async () => {
            const dp = $('.swal2-container');
            const firstnameInput = document.getElementById('user-firstname-input') as HTMLInputElement;
            const lastnameInput = document.getElementById('user-lastname-input') as HTMLInputElement;
            const emailInput = document.getElementById('user-email-input') as HTMLInputElement;
            const roleInput = document.getElementById('user-role-input') as HTMLSelectElement;
            const passwordInput = document.getElementById('user-password-input') as HTMLInputElement;
            const passwordConfirmationInput = document.getElementById('user-password-confirmation-input') as HTMLInputElement;

            if (firstnameInput) firstnameInput.value = user.firstname;
            if (lastnameInput) lastnameInput.value = user.lastname;
            if (emailInput) emailInput.value = user.email;
            if (roleInput && user.roles && user.roles.length > 0) {
                const roleId = user.roles[0].uuid;
                $('#user-role-input')
                    .select2({
                        placeholder: 'Select Role',
                        allowClear: true,
                        dir: 'ltr',
                        dropdownParent: dp,
                    })
                    .val(roleId)
                    .trigger('change');
            }


            if (isSuperAdmin) {
                roleInput.innerHTML = `<option value="">Super-Admin</option>`;
                const fields = document.querySelectorAll('.user-modal-container .form-control');
                fields.forEach((field: any): void => {field.disabled = true});
            }

            // Make password fields optional for updates
            if (passwordInput) passwordInput.placeholder = 'Leave blank to keep current password';
            if (passwordConfirmationInput) passwordConfirmationInput.placeholder = 'Leave blank to keep current password';

            firstnameInput.focus();
        },
        preConfirm: async () => {
            const passwordValue = (document.getElementById('user-password-input') as HTMLInputElement).value;
            const passwordConfirmationValue = (document.getElementById('user-password-confirmation-input') as HTMLInputElement).value;

            const payload: any = {
                firstname: (document.getElementById('user-firstname-input') as HTMLInputElement).value.trim(),
                lastname: (document.getElementById('user-lastname-input') as HTMLInputElement).value.trim(),
                email: (document.getElementById('user-email-input') as HTMLInputElement).value.trim(),
                role_id: (document.getElementById('user-role-input') as HTMLSelectElement).value,
            };

            // Only include password if provided
            if (passwordValue) {
                payload.password = passwordValue;
                payload.password_confirmation = passwordConfirmationValue;
            }

            const { data } = await UserService.update(id, payload);
            return data;
        },
        confirmButtonText: 'Save Changes',
    });
};

export const deleteUserModal = async (id: string) => {
    return ShowModal({
        title: Header('Delete user?', 'danger'),
        html: `<span style="font-size: 0.85rem;">Are you sure you want to delete the user?</span>`,
        confirmButtonText: 'Delete',
        preConfirm: async () => {
            const { data } = await UserService.delete(id);
            return data;
        },
    });
}
