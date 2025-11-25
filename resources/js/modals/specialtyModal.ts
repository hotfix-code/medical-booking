import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {SpecialtyService} from "@/services/specialty.service.ts";
import {Header} from "@/components/modal";

export const createSpecialtyModal = async () => {
    const tpl = document.getElementById('specialty-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Create new specialty?'),
        html: formHtml,
        didOpen: async () => {
            (document.getElementById('specialty-name-input') as HTMLInputElement).focus();
        },
        preConfirm: async () => {
            const { data } = await SpecialtyService.create({
                name: (document.getElementById('specialty-name-input') as HTMLInputElement).value.trim(),
                description: (document.getElementById('specialty-description-input') as HTMLTextAreaElement).value.trim() || undefined,
            });
            return data;
        },
    });
}

export const viewSpecialtyModal = async (id: string) => {
    await LoadingModal('Loading Specialty data...');
    const { data } = await SpecialtyService.fetch(id);
    const specialty = data.data;

    const tpl = document.getElementById('specialty-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('View specialty'),
        html: formHtml,
        confirmButtonText: 'Ok',
        didOpen: async () => {
            const nameInput = document.getElementById('specialty-name-input') as HTMLInputElement;
            const descriptionInput = document.getElementById('specialty-description-input') as HTMLTextAreaElement;
            if (nameInput) {
                nameInput.value = specialty.name
                nameInput.disabled = true;
            }
            if (descriptionInput) {
                descriptionInput.value = specialty.description || '';
                descriptionInput.disabled = true;
            }
        },
    });
};

export const updateSpecialtyModal = async (id: string) => {
    await LoadingModal('Loading Specialty data...');
    const { data } = await SpecialtyService.fetch(id);
    const specialty = data.data;

    const tpl = document.getElementById('specialty-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Edit specialty?'),
        html: formHtml,
        didOpen: async () => {
            const nameInput = document.getElementById('specialty-name-input') as HTMLInputElement;
            const descriptionInput = document.getElementById('specialty-description-input') as HTMLTextAreaElement;
            if (nameInput) nameInput.value = specialty.name;
            if (descriptionInput) descriptionInput.value = specialty.description || '';
            nameInput.focus();
        },
        preConfirm: async () => {
            const { data } = await SpecialtyService.update(id, {
                name: (document.getElementById('specialty-name-input') as HTMLInputElement).value.trim(),
                description: (document.getElementById('specialty-description-input') as HTMLTextAreaElement).value.trim() || undefined,
            });
            return data;
        },
        confirmButtonText: 'Save Changes',
    });
};

export const deleteSpecialtyModal = async (id: string) => {
    return ShowModal({
        title: Header('Delete specialty?', 'danger'),
        html: `<span style="font-size: 0.85rem;">Are you sure you want to delete the specialty?</span>`,
        confirmButtonText: 'Delete',
        preConfirm: async () => {
            const { data } = await SpecialtyService.delete(id);
            return data;
        },
    });
}
