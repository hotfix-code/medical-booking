import Swal, { SweetAlertIcon, SweetAlertResult } from 'sweetalert2';
import { Header } from "@/components/modal";
import { t } from '@/utils/i18n';

type DismissReason = typeof Swal.DismissReason[keyof typeof Swal.DismissReason];

type ModalOptions<T = any> = {
    title: string;
    text?: string;
    icon?: SweetAlertIcon
    html?: string;
    confirmButtonText?: string;
    cancelButtonText?: string;
    denyButtonText?: string;
    showCancelButton?: boolean;
    showConfirmButton?: boolean;
    showDenyButton?: boolean;
    allowOutsideClick?: boolean;
    width?: string | number;
    input?: 'text' | 'email' | 'textarea' | null;
    inputLabel?: string;
    inputValue?: any;
    didOpen?: (inputValue: any) => Promise<T>;
    preConfirm?: (inputValue: any) => Promise<T>;
    preDeny?: (inputValue: any) => Promise<T>;
};

export type ModalResult = {
    status: 'confirmed' | 'denied' | 'dismissed';
    value?: any;
    reason?: DismissReason;
};

export async function ShowModal<T = any>(options: ModalOptions<T>): Promise<ModalResult> {
    const swalResult: SweetAlertResult = await Swal.fire({
        title: options.title,
        text: options.text,
        html: options.html ?? undefined,
        icon: options.icon ?? undefined,
        input: options.input ?? undefined,
        inputLabel: options.inputLabel,
        inputValue: options.inputValue,
        showCancelButton: options.showCancelButton ?? true,
        showConfirmButton: options.showConfirmButton ?? true,
        showDenyButton: options.showDenyButton ?? false,
        confirmButtonText: options.confirmButtonText ?? t('common.actions.confirm'),
        cancelButtonText: options.cancelButtonText ?? t('common.actions.cancel'),
        denyButtonText: options.denyButtonText ?? t('common.actions.deny'),
        allowOutsideClick: options.allowOutsideClick ?? false,
        width: options.width ?? undefined,
        showLoaderOnConfirm: !!options.preConfirm,
        didOpen: options.didOpen,
        preConfirm: options.preConfirm,
        preDeny: options.preDeny,
    });

    if (swalResult.isConfirmed) return { status: 'confirmed', value: swalResult.value ?? (true as any) };
    if (swalResult.isDenied)    return { status: 'denied',    value: swalResult.value ?? (true as any) };
    return { status: 'dismissed', reason: swalResult.dismiss };
}

export const LoadingModal = async (title?: string, timer: number = 200) => {
    Swal.fire({
        title: title ?? t('messages.loading'),
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    await new Promise(r => setTimeout(r, timer));
};

export const SuccessModal = async (title?: string, text?: string) => {
    Swal.fire({
        title: Header(title ?? t('messages.success')),
        text: text ?? t('messages.event_success'),
        confirmButtonText: t('messages.ok'),
    })
};


export const ErrorModal = async (title?: string, message?: string, errors: object = {}) => {
    Swal.fire({
        title: Header(title ?? t('messages.error'), 'danger'),
        html: renderErrors(errors) ?? `<span style="font-size: 0.85rem;">${message}</span>`,
        confirmButtonText: t('messages.ok'),
    });
};

export function renderErrors(errors: object) {
    if (Object.keys(errors).length === 0) return null;
    let list = [];

    if (Array.isArray(errors)) {
        list = errors.map(e => e.message ?? e);
    } else if (typeof errors === 'object') {
        Object.values(errors).forEach(arr => {
            if (Array.isArray(arr)) list.push(...arr);
            else list.push(arr);
        });
    }

    if (!list.length) return '';

    return `
        <ul class="text-start m-0 ps-4 py-2" style="font-size: .85rem;">
            ${list.map(m => `<li>${m}</li>`).join('')}
        </ul>
      `;
}
