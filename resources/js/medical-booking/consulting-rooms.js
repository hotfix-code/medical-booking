import {createConsultingRoomModal, deleteConsultingRoomModal, updateConsultingRoomModal} from "@/modals/consultingRoomModal.js";
import {SuccessModal} from "@/utils/SwalWrapper.js";
import {AddRow, DeleteRow, EditRow} from "@/utils/datatables.js";
import { t } from "@/utils/i18n.js";

$(function () {
    const dt = $('#consulting-rooms-table').DataTable();
    const tableBody = $('#consulting-rooms-table tbody');

    $('#add-new-row').on('click', async function () {
        try {
            const { status, value } = await createConsultingRoomModal();
            if (status === 'dismissed') return;
            const consultingRoom = value.data;
            SuccessModal(t('consulting_rooms.flash.created_title'), t('consulting_rooms.flash.created'))
                .then(() => {
                    consultingRoom.location = consultingRoom.location || t('common.states.na');
                    AddRow(dt, consultingRoom)
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-edit', async function () {
        try {
            const consultingRoomId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status, value } = await updateConsultingRoomModal(consultingRoomId);
            if (status === 'dismissed') return;
            const consultingRoom = value.data;
            SuccessModal(t('consulting_rooms.flash.updated_title'), t('consulting_rooms.flash.updated'))
                .then(() => {
                    consultingRoom.location = consultingRoom.location || t('common.states.na');
                    EditRow(dt, consultingRoom, this);
                });
        } catch (err) {
            // Interceptor shows the error modal
        }
    });

    tableBody.on('click', '.action-delete', async function () {
        try {
            const consultingRoomId = $(this).closest('div.datatables-action-buttons').data('id');
            const { status } = await deleteConsultingRoomModal(consultingRoomId);
            if (status === 'dismissed') return;
            SuccessModal(t('consulting_rooms.flash.deleted_title'), t('consulting_rooms.flash.deleted'))
                .then(() => DeleteRow(dt, this));
        } catch (err) {
            // Interceptor shows the error modal
        }
    })
});
