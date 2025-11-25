import http from '@/utils/http';
import {Password} from "@/types";

export const ProfileService = {
    update: (payload: any) => http.patch(`/profile`, payload),
    updatePassword: (payload: Password) => http.put(`/password`, payload),
};
