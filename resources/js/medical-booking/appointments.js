import {createAppointmentModal, deleteAppointmentModal, updateAppointmentModal} from "@/modals/appointmentModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";

$(function () {
    const dt = $('#appointments-table').DataTable();
    const tableBody = $('#appointments-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createAppointmentModal();
            if (status === 'dismissed') return;
            const appointment = value.data;
            SuccessModal('Appointment Created', 'Appointment created successfully')
                .then(() => AddRow(dt, appointment));
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-edit', async function () {
        try {
            const appointmentId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status, value } = await updateAppointmentModal(appointmentId);
            if (status === 'dismissed') return;
            const appointment = value.data;
            SuccessModal('Appointment Edited', 'Appointment edited successfully')
                .then(() => EditRow(dt, appointment, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-delete', async function () {
        try {
            const appointmentId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status } = await deleteAppointmentModal(appointmentId);
            if (status === 'dismissed') return;
            SuccessModal('Appointment Deleted', 'Appointment deleted successfully')
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
