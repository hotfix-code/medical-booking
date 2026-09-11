import http from '@/utils/http';

export const ConsultingRoomService = {
    fetch: (id: string) => http.post(`/consulting-rooms/fetch/${id}`),
    create: (payload: { name: string, location?: string | null }) => http.post('/consulting-rooms', payload),
    update: (id: string, payload: any) => http.put(`/consulting-rooms/${id}`, payload),
    delete: (id: string) => http.delete(`/consulting-rooms/${id}`),
};
