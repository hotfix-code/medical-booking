import http from '@/utils/http';

type DoctorPayload = {
    firstname: string;
    lastname: string;
    email: string;
    password: string;
    password_confirmation: string;
    document_type_id: string;
    document_number: string;
    license_number: string;
    phone?: string;
    specialties?: string[];
};

export const DoctorService = {
    available: (id: string, payload: { date: string }) => http.post(`/doctors/fetch/${id}/availability`, payload),
    fetch: (id: string) => http.post(`/doctors/fetch/${id}`),
    fetchBySpecialty: (id: string, specialtyId: string) => http.post(`/doctors/fetch/${id}/specialties/${specialtyId}`),
    create: (payload: DoctorPayload) => http.post('/doctors', payload),
    update: (id: string, payload: Partial<DoctorPayload>) => http.put(`/doctors/${id}`, payload),
    delete: (id: string) => http.delete(`/doctors/${id}`),
};
