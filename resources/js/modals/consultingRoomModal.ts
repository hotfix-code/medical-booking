import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {ConsultingRoomService} from "@/services/consulting-room.service.ts";
import {Header} from "@/components/modal";
import { t } from '@/utils/i18n';

export const createConsultingRoomModal = async () => {
    const tpl = document.getElementById('consulting-room-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('consulting_rooms.modals.create')),
        html: formHtml,
        didOpen: async () => {
            (document.getElementById('consulting-room-name-input') as HTMLInputElement).focus();
        },
        preConfirm: async () => {
            const { data } = await ConsultingRoomService.create({
                name: (document.getElementById('consulting-room-name-input') as HTMLInputElement).value.trim(),
                location: (document.getElementById('consulting-room-location-input') as HTMLInputElement).value.trim() || null,
            });
            return data;
        },
    });
}

export const updateConsultingRoomModal = async (id: string) => {
    await LoadingModal(t('messages.loading_resource', { resource: t('consulting_rooms.resource') }));
    const { data } = await ConsultingRoomService.fetch(id);
    const consultingRoom = data.data;

    const tpl = document.getElementById('consulting-room-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('consulting_rooms.modals.edit')),
        html: formHtml,
        didOpen: async () => {
            const nameInput = document.getElementById('consulting-room-name-input') as HTMLInputElement;
            const locationInput = document.getElementById('consulting-room-location-input') as HTMLInputElement;
            if (nameInput) nameInput.value = consultingRoom.name;
            if (locationInput) locationInput.value = consultingRoom.location || '';
            nameInput.focus();
        },
        preConfirm: async () => {
            const { data } = await ConsultingRoomService.update(id, {
                name: (document.getElementById('consulting-room-name-input') as HTMLInputElement).value.trim(),
                location: (document.getElementById('consulting-room-location-input') as HTMLInputElement).value.trim() || null,
            });
            return data;
        },
        confirmButtonText: t('common.actions.save_changes'),
    });
};

export const deleteConsultingRoomModal = async (id: string) => {
    return ShowModal({
        title: Header(t('consulting_rooms.modals.delete'), 'danger'),
        html: `<span style="font-size: 0.85rem;">${t('messages.confirm_delete', { resource: t('consulting_rooms.resource') })}</span>`,
        confirmButtonText: t('common.actions.delete'),
        preConfirm: async () => {
            const { data } = await ConsultingRoomService.delete(id);
            return data;
        },
    });
}
