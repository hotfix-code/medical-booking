import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {ConsultingRoomService} from "@/services/consulting-room.service.ts";
import {Header} from "@/components/modal";

export const createConsultingRoomModal = async () => {
    const tpl = document.getElementById('consulting-room-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Create new consulting room?'),
        html: formHtml,
        didOpen: async () => {
            (document.getElementById('consulting-room-name-input') as HTMLInputElement).focus();
        },
        preConfirm: async () => {
            const { data } = await ConsultingRoomService.create({
                name: (document.getElementById('consulting-room-name-input') as HTMLInputElement).value.trim(),
                location: (document.getElementById('consulting-room-location-input') as HTMLInputElement).value.trim() || undefined,
            });
            return data;
        },
    });
}

export const updateConsultingRoomModal = async (id: string) => {
    await LoadingModal('Loading Consulting Room data...');
    const { data } = await ConsultingRoomService.fetch(id);
    const consultingRoom = data.data;

    const tpl = document.getElementById('consulting-room-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Edit consulting room?'),
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
                location: (document.getElementById('consulting-room-location-input') as HTMLInputElement).value.trim() || undefined,
            });
            return data;
        },
        confirmButtonText: 'Save Changes',
    });
};

export const deleteConsultingRoomModal = async (id: string) => {
    return ShowModal({
        title: Header('Delete consulting room?', 'danger'),
        html: `<span style="font-size: 0.85rem;">Are you sure you want to delete the consulting room?</span>`,
        confirmButtonText: 'Delete',
        preConfirm: async () => {
            const { data } = await ConsultingRoomService.delete(id);
            return data;
        },
    });
}
