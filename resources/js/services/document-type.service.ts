import http from '@/utils/http';

export const DocumentTypeService = {
    fetch: (id: string) => http.post(`/document-types/fetch/${id}`),
    create: (payload: { name: string, code: string }) => http.post('/document-types', payload),
    update: (id: string, payload: any) => http.put(`/document-types/${id}`, payload),
    delete: (id: string) => http.delete(`/document-types/${id}`),
};
