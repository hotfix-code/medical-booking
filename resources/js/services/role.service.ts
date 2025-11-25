import http from '@/utils/http';

export const RoleService = {
    fetch: (id: string) => http.get(`/roles/fetch/${id}`),
    fetchWithPermissions: (id: string) => http.post(`/roles/${id}/permissions-selected`),
    create: (payload: { role: string }) => http.post('/roles', payload),
    update: (id: string, payload: any) => http.put(`/roles/${id}`, payload),
    updatePermissions: (id: string, payload: any) => http.put(`/roles/${id}/permissions`, payload),
    delete: (id: string) => http.delete(`/roles/${id}`),
};
