import {createPatientModal, deletePatientModal, updatePatientModal} from "@/modals/patientModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";

$(function () {
    const dt = $('#patients-table').DataTable();
    const tableBody = $('#patients-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createPatientModal();
            if (status === 'dismissed') return;
            const patient = value.data;
            SuccessModal('Patient Created', 'Patient created successfully')
                .then(() => {
                    patient.full_name = `${patient.firstname} ${patient.lastname}`;
                    patient.document_type = patient.document_type.name;
                    patient.phone = patient.phone ?? 'N/A';
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
            SuccessModal('Patient Edited', 'Patient edited successfully')
                .then(() => {
                    patient.full_name = `${patient.firstname} ${patient.lastname}`;
                    patient.document_type = patient.document_type.name;
                    patient.phone = patient.phone ?? 'N/A';
                    EditRow(dt, patient, this)
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
            SuccessModal('Patient Deleted', 'Patient deleted successfully')
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
