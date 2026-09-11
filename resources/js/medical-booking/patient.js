import {createPatientModal, deletePatientModal, updatePatientModal} from "@/modals/patientModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";
import { t } from "@/utils/i18n.js";

$(function () {
    const dt = $('#patients-table').DataTable();
    const tableBody = $('#patients-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createPatientModal();
            if (status === 'dismissed') return;
            const patient = value.data;
            SuccessModal(t('patients.flash.created_title'), t('patients.flash.created'))
                .then(() => {
                    patient.full_name = `${patient.firstname} ${patient.lastname}`;
                    patient.document_type = patient.document_type.name;
                    patient.phone = patient.phone || t('common.states.na');
                    AddRow(dt, patient);
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-edit', async function () {
        try {
            const patientId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status, value } = await updatePatientModal(patientId);
            if (status === 'dismissed') return;
            const patient = value.data;
            SuccessModal(t('patients.flash.updated_title'), t('patients.flash.updated'))
                .then(() => {
                    patient.full_name = `${patient.firstname} ${patient.lastname}`;
                    patient.document_type = patient.document_type.name;
                    patient.phone = patient.phone || t('common.states.na');
                    EditRow(dt, patient, this);
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-delete', async function () {
        try {
            const patientId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status } = await deletePatientModal(patientId);
            if (status === 'dismissed') return;
            SuccessModal(t('patients.flash.deleted_title'), t('patients.flash.deleted'))
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
