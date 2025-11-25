import {
    createSpecialtyModal,
    deleteSpecialtyModal,
    updateSpecialtyModal,
    viewSpecialtyModal
} from "@/modals/specialtyModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";

$(function () {
    const dt = $('#specialties-table').DataTable();
    const tableBody = $('#specialties-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createSpecialtyModal();
            if (status === 'dismissed') return;
            const specialty = value.data;
            SuccessModal('Specialty Created', 'Specialty created successfully')
                .then(() => {
                    specialty.description = specialty.description ?? 'N/A';
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
            SuccessModal('Specialty Edited', 'Specialty edited successfully')
                .then(() => EditRow(dt, specialty, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-delete', async function () {
        try {
            const specialtyId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status } = await deleteSpecialtyModal(specialtyId);
            if (status === 'dismissed') return;
            SuccessModal('Specialty Deleted', 'Specialty deleted successfully')
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
