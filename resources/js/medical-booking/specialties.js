import {
    createSpecialtyModal,
    deleteSpecialtyModal,
    updateSpecialtyModal,
    viewSpecialtyModal
} from "@/modals/specialtyModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";
import { t } from "@/utils/i18n.js";

$(function () {
    const dt = $('#specialties-table').DataTable();
    const tableBody = $('#specialties-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createSpecialtyModal();
            if (status === 'dismissed') return;
            const specialty = value.data;
            SuccessModal(t('specialties.flash.created_title'), t('specialties.flash.created'))
                .then(() => {
                    specialty.description = specialty.description || t('common.states.na');
                    AddRow(dt, specialty)
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-view', async function () {
        try {
            const specialtyId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status, value } = await viewSpecialtyModal(specialtyId);
            if (status === 'dismissed') return;
            const specialty = value.data;
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-edit', async function () {
        try {
            const specialtyId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status, value } = await updateSpecialtyModal(specialtyId);
            if (status === 'dismissed') return;
            const specialty = value.data;
            SuccessModal(t('specialties.flash.updated_title'), t('specialties.flash.updated'))
                .then(() => {
                    specialty.description = specialty.description || t('common.states.na');
                    EditRow(dt, specialty, this);
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-delete', async function () {
        try {
            const specialtyId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status } = await deleteSpecialtyModal(specialtyId);
            if (status === 'dismissed') return;
            SuccessModal(t('specialties.flash.deleted_title'), t('specialties.flash.deleted'))
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
