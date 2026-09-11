import {createAppointmentModal, deleteAppointmentModal, updateAppointmentModal} from "@/modals/appointmentModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";
import { t } from "@/utils/i18n.js";

$(function () {
    const dt = $('#appointments-table').DataTable();
    const tableBody = $('#appointments-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createAppointmentModal();
            if (status === 'dismissed') return;
            const appointment = value.data;
            SuccessModal(t('appointments.flash.created_title'), t('appointments.flash.created'))
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
            SuccessModal(t('appointments.flash.updated_title'), t('appointments.flash.updated'))
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
            SuccessModal(t('appointments.flash.deleted_title'), t('appointments.flash.deleted'))
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
