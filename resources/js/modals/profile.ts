import {ShowModal} from "@/utils/SwalWrapper.ts";
import {Header} from "@/components/modal";
import {ProfileService} from "@/services/profile.service.ts";
import {Password} from "@/types";

export const updateProfileModal = async (payload: object) => {
    return ShowModal({
        title: Header('Update profile?'),
        html: `<span style="font-size: 0.85rem;">Are you sure you want to update your profile?</span>`,
        confirmButtonText: 'Update',
        preConfirm: async () => {
            const { data } = await ProfileService.update(payload);
            return data;
        }
    });
};

export const updatePasswordModal = async (payload: Password) => {
    return ShowModal({
        title: Header('Update password?'),
        html: `<span style="font-size: 0.85rem;">Are you sure you want to update your password?</span>`,
        confirmButtonText: 'Update',
        preConfirm: async () => {
            const { data } = await ProfileService.updatePassword(payload);
            return data;
        },
    });
};
