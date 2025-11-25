import {LoadingModal, ShowModal} from "@/utils/SwalWrapper.ts";
import {DocumentTypeService} from "@/services/document-type.service.ts";
import {Header} from "@/components/modal";

export const createDocumentTypeModal = async () => {
    const tpl = document.getElementById('document-type-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Create new document type?'),
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
    await LoadingModal('Loading Document Type data...');
    const { data } = await DocumentTypeService.fetch(id);
    const documentType = data.data;

    const tpl = document.getElementById('document-type-modal-template') as HTMLTemplateElement;
    const formHtml = tpl.innerHTML;

    return ShowModal({
        title: Header('Edit document type?'),
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
        confirmButtonText: 'Save Changes',
    });
};

export const deleteDocumentTypeModal = async (id: string) => {
    return ShowModal({
        title: Header('Delete document type?', 'danger'),
        html: `<span style="font-size: 0.85rem;">Are you sure you want to delete the document type?</span>`,
        confirmButtonText: 'Delete',
        preConfirm: async () => {
            const { data } = await DocumentTypeService.delete(id);
            return data;
        },
    });
}
