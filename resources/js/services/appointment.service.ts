import http from '@/utils/http';

type AppointmentCreate = {
    patient_id: string,
    doctor_id: string,
    consulting_room_id: string,
    specialty_id: string,
    schedule_id: string,
    appointment_date: string,
    appointment_time: string,
    status: string,
    is_active?: boolean,
    notes?: string
};

export const AppointmentService = {
    fetch: (id: string) => http.post(`/appointments/fetch/${id}`),
    create: (payload: AppointmentCreate) => http.post('/appointments', payload),
    update: (id: string, payload: any) => http.put(`/appointments/${id}`, payload),
    delete: (id: string) => http.delete(`/appointments/${id}`),
};
