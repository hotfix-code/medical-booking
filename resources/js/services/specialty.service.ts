import http from '@/utils/http';

export const SpecialtyService = {
    fetch: (id: string) => http.post(`/specialties/fetch/${id}`),
    create: (payload: { name: string, description?: string | null }) => http.post('/specialties', payload),
    update: (id: string, payload: any) => http.put(`/specialties/${id}`, payload),
    delete: (id: string) => http.delete(`/specialties/${id}`),
    fetchWithDoctors: (id: string) => http.post(`/specialties/fetch/${id}/doctors`),
};
