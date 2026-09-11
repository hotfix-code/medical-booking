import {createUserModal, deleteUserModal, updateUserModal} from "@/modals/userModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";
import { t, roleLabel } from "@/utils/i18n.js";

$(function () {
    const dt = $('#users-table').DataTable();
    const tableBody = $('#users-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createUserModal();
            if (status === 'dismissed') return;
            const user = value.data;
            SuccessModal(t('users.flash.created_title'), t('users.flash.created'))
                .then(() => {
                    user.full_name = `${user.firstname} ${user.lastname}`;
                    user.role_name = roleLabel(user.roles[0]?.name);
                    AddRow(dt, user);
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-edit', async function () {
        try {
            const userId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status, value } = await updateUserModal(userId);
            if (status === 'dismissed') return;
            const user = value.data;
            SuccessModal(t('users.flash.updated_title'), t('users.flash.updated'))
                .then(() => {
                    user.full_name = `${user.firstname} ${user.lastname}`;
                    user.role_name = roleLabel(user.roles[0]?.name);
                    EditRow(dt, user, this);
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-delete', async function () {
        try {
            const userId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status } = await deleteUserModal(userId);
            if (status === 'dismissed') return;
            SuccessModal(t('users.flash.deleted_title'), t('users.flash.deleted'))
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
