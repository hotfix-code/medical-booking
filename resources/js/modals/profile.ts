import {ShowModal} from "@/utils/SwalWrapper.ts";
import {Header} from "@/components/modal";
import {ProfileService} from "@/services/profile.service.ts";
import {Password} from "@/types";
import { t } from '@/utils/i18n';

export const updateProfileModal = async (payload: object) => {
    return ShowModal({
        title: Header(t('profile.modals.update')),
        html: `<span style="font-size: 0.85rem;">${t('profile.modals.update_confirm')}</span>`,
        confirmButtonText: t('common.actions.update'),
        preConfirm: async () => {
            const { data } = await ProfileService.update(payload);
            return data;
        }
    });
};

export const updatePasswordModal = async (payload: Password) => {
    return ShowModal({
        title: Header(t('profile.modals.update_password')),
        html: `<span style="font-size: 0.85rem;">${t('profile.modals.update_password_confirm')}</span>`,
        confirmButtonText: t('common.actions.update'),
        preConfirm: async () => {
            const { data } = await ProfileService.updatePassword(payload);
            return data;
        },
    });
};
