import {createDoctorModal, deleteDoctorModal, updateDoctorModal} from "@/modals/doctorModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";

$(function () {
    const dt = $('#doctors-table').DataTable();
    const tableBody = $('#doctors-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createDoctorModal();
            if (status === 'dismissed') return;
            const doctor = value.data;
            SuccessModal('Doctor Created', 'Doctor created successfully')
                .then(() => {
                    doctor.full_name = `${doctor.firstname} ${doctor.lastname}`;
                    doctor.document_type = doctor.document_type.name;
                    doctor.phone = doctor.phone ?? 'N/A';
                    AddRow(dt, doctor);
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-edit', async function () {
        try {
            const doctorId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status, value } = await updateDoctorModal(doctorId);
            if (status === 'dismissed') return;
            const doctor = value.data;
            SuccessModal('Doctor Edited', 'Doctor edited successfully')
                .then(() => {
                    doctor.full_name = `${doctor.firstname} ${doctor.lastname}`;
                    doctor.document_type = doctor.document_type.name;
                    doctor.phone = doctor.phone ?? 'N/A';
                    EditRow(dt, doctor, this);
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-delete', async function () {
        try {
            const doctorId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status } = await deleteDoctorModal(doctorId);
            if (status === 'dismissed') return;
            SuccessModal('Doctor Deleted', 'Doctor deleted successfully')
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
