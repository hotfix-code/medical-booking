import http from '@/utils/http';

type PatientPayload = {
    firstname: string;
    lastname: string;
    email: string;
    document_type_id: string;
    document_number: string;
    gender?: 'male' | 'female' | 'other' | null;
    birthdate?: string | null;
    phone?: string | null;
    password: string;
    password_confirmation: string;
};

export const PatientService = {
    fetch: (id: string) => http.post(`/patients/fetch/${id}`),
    create: (payload: PatientPayload) => http.post('/patients', payload),
    update: (id: string, payload: Partial<PatientPayload>) => http.put(`/patients/${id}`, payload),
    delete: (id: string) => http.delete(`/patients/${id}`),
};
