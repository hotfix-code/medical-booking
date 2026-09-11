import {createDocumentTypeModal, deleteDocumentTypeModal, updateDocumentTypeModal} from "@/modals/documentTypeModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";
import { t } from "@/utils/i18n.js";

$(function () {
    const dt = $('#document-types-table').DataTable();
    const tableBody = $('#document-types-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createDocumentTypeModal();
            if (status === 'dismissed') return;
            const documentType = value.data;
            SuccessModal(t('document_types.flash.created_title'), t('document_types.flash.created'))
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
            SuccessModal(t('document_types.flash.updated_title'), t('document_types.flash.updated'))
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
            SuccessModal(t('document_types.flash.deleted_title'), t('document_types.flash.deleted'))
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
