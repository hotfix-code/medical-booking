import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {DocumentTypeService} from "@/services/document-type.service.ts";
import {Header} from "@/components/modal";
import { t } from '@/utils/i18n';

export const createDocumentTypeModal = async () => {
    const tpl = document.getElementById('document-type-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('document_types.modals.create')),
        html: formHtml,
        didOpen: async () => {
            (document.getElementById('document-type-name-input') as HTMLInputElement).focus();
        },
        preConfirm: async () => {
            const { data } = await DocumentTypeService.create({
                name: (document.getElementById('document-type-name-input') as HTMLInputElement).value.trim(),
                code: (document.getElementById('document-type-code-input') as HTMLInputElement).value.trim(),
            });
            return data;
        },
    });
}

export const updateDocumentTypeModal = async (id: string) => {
    await LoadingModal(t('messages.loading_resource', { resource: t('document_types.resource') }));
    const { data } = await DocumentTypeService.fetch(id);
    const documentType = data.data;

    const tpl = document.getElementById('document-type-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header(t('document_types.modals.edit')),
        html: formHtml,
        didOpen: async () => {
            const nameInput = document.getElementById('document-type-name-input') as HTMLInputElement;
            const codeInput = document.getElementById('document-type-code-input') as HTMLInputElement;
            if (nameInput) nameInput.value = documentType.name;
            if (codeInput) codeInput.value = documentType.code;
            nameInput.focus();
        },
        preConfirm: async () => {
            const { data } = await DocumentTypeService.update(id, {
                name: (document.getElementById('document-type-name-input') as HTMLInputElement).value.trim(),
                code: (document.getElementById('document-type-code-input') as HTMLInputElement).value.trim(),
            });
            return data;
        },
        confirmButtonText: t('common.actions.save_changes'),
    });
};

export const deleteDocumentTypeModal = async (id: string) => {
    return ShowModal({
        title: Header(t('document_types.modals.delete'), 'danger'),
        html: `<span style="font-size: 0.85rem;">${t('messages.confirm_delete', { resource: t('document_types.resource') })}</span>`,
        confirmButtonText: t('common.actions.delete'),
        preConfirm: async () => {
            const { data } = await DocumentTypeService.delete(id);
            return data;
        },
    });
}
