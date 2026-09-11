import {createDoctorModal, deleteDoctorModal, updateDoctorModal} from "@/modals/doctorModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";
import { t } from "@/utils/i18n.js";

$(function () {
    const dt = $('#doctors-table').DataTable();
    const tableBody = $('#doctors-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createDoctorModal();
            if (status === 'dismissed') return;
            const doctor = value.data;
            SuccessModal(t('doctors.flash.created_title'), t('doctors.flash.created'))
                .then(() => {
                    doctor.full_name = `${doctor.firstname} ${doctor.lastname}`;
                    doctor.document_type = doctor.document_type.name;
                    doctor.phone = doctor.phone || t('common.states.na');
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
            SuccessModal(t('doctors.flash.updated_title'), t('doctors.flash.updated'))
                .then(() => {
                    doctor.full_name = `${doctor.firstname} ${doctor.lastname}`;
                    doctor.document_type = doctor.document_type.name;
                    doctor.phone = doctor.phone || t('common.states.na');
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
            SuccessModal(t('doctors.flash.deleted_title'), t('doctors.flash.deleted'))
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
