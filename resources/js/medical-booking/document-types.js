import {createDocumentTypeModal, deleteDocumentTypeModal, updateDocumentTypeModal} from "@/modals/documentTypeModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";

$(function () {
    const dt = $('#document-types-table').DataTable();
    const tableBody = $('#document-types-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createDocumentTypeModal();
            if (status === 'dismissed') return;
            const documentType = value.data;
            SuccessModal('Document Type Created', 'Document type created successfully')
                .then(() => AddRow(dt, documentType));
        } catch (err) {
            // Incerceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-edit', async function () {
        try {
            const documentTypeId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status, value } = await updateDocumentTypeModal(documentTypeId);
            if (status === 'dismissed') return;
            const documentType = value.data;
            SuccessModal('Document Type Edited', 'Document type edited successfully')
                .then(() => EditRow(dt, documentType, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-delete', async function () {
        try {
            const documentTypeId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status } = await deleteDocumentTypeModal(documentTypeId);
            if (status === 'dismissed') return;
            SuccessModal('Document Type Deleted', 'Document type deleted successfully')
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
