import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {SpecialtyService} from "@/services/specialty.service.ts";
import {Header} from "@/components/modal";
import { t } from '@/utils/i18n';

export const createSpecialtyModal = async () => {
    const tpl = document.getElementById('specialty-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('specialties.modals.create')),
        html: formHtml,
        didOpen: async () => {
            (document.getElementById('specialty-name-input') as HTMLInputElement).focus();
        },
        preConfirm: async () => {
            const { data } = await SpecialtyService.create({
                name: (document.getElementById('specialty-name-input') as HTMLInputElement).value.trim(),
                description: (document.getElementById('specialty-description-input') as HTMLTextAreaElement).value.trim() || null,
            });
            return data;
        },
    });
}

export const viewSpecialtyModal = async (id: string) => {
    await LoadingModal(t('messages.loading_resource', { resource: t('specialties.resource') }));
    const { data } = await SpecialtyService.fetch(id);
    const specialty = data.data;

    const tpl = document.getElementById('specialty-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('specialties.modals.view')),
        html: formHtml,
        confirmButtonText: t('messages.ok'),
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
    await LoadingModal(t('messages.loading_resource', { resource: t('specialties.resource') }));
    const { data } = await SpecialtyService.fetch(id);
    const specialty = data.data;

    const tpl = document.getElementById('specialty-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('specialties.modals.edit')),
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
                description: (document.getElementById('specialty-description-input') as HTMLTextAreaElement).value.trim() || null,
            });
            return data;
        },
        confirmButtonText: t('common.actions.save_changes'),
    });
};

export const deleteSpecialtyModal = async (id: string) => {
    return ShowModal({
        title: Header(t('specialties.modals.delete'), 'danger'),
        html: `<span style="font-size: 0.85rem;">${t('messages.confirm_delete', { resource: t('specialties.resource') })}</span>`,
        confirmButtonText: t('common.actions.delete'),
        preConfirm: async () => {
            const { data } = await SpecialtyService.delete(id);
            return data;
        },
    });
}
