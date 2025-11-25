import http from '@/utils/http';

type SchedulePayload = {
    doctor_id: string;
    specialty_id: string;
    consulting_room_id: string;
    weekday: number;
    start_time: string;
    end_time: string;
};

export const ScheduleService = {
    fetch: (id: string) => http.post(`/schedules/fetch/${id}`),
    create: (payload: SchedulePayload) => http.post('/schedules', payload),
    update: (id: string, payload: Partial<SchedulePayload>) => http.put(`/schedules/${id}`, payload),
    delete: (id: string) => http.delete(`/schedules/${id}`),
};
