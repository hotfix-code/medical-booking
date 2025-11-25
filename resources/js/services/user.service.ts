import http from '@/utils/http';

type UserCreate = {
    firstname: string,
    lastname: string,
    email: string,
    role_id: string,
    password: string,
    password_confirmation: string
};

export const UserService = {
    fetch: (id: string) => http.post(`/users/fetch/${id}`),
    create: (payload: UserCreate) => http.post('/users', payload),
    update: (id: string, payload: any) => http.put(`/users/${id}`, payload),
    delete: (id: string) => http.delete(`/users/${id}`),
};
