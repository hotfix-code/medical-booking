import axios from 'axios';
import { ErrorModal } from "@/utils/SwalWrapper.js";

const http = axios.create({
    baseURL: '/',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
    },
});

// CSRF
const token = document.head.querySelector('meta[name="csrf-token"]')?.content;
if (token) {
    http.defaults.headers.common['X-CSRF-TOKEN'] = token;
}

// Error normalizer (adjust it according to your backend)
export function normalizeAxiosError(error) {
    const fallback = {
        title: 'Error',
        message: 'Oops! an error occurred',
        errors: null,
        statusCode: 0,
    };

    if (!error.response) {
        return {
            ...fallback,
            message: 'There was no response from the server',
        };
    }

    const { status, data } = error.response;

    // Supports two common forms:
    // a) Laravel Validation: { message: "...", errors: { field: [msg] } }
    // b) Custom format: { status: "error", errors: [{ field, message }], meta: { message } }
    if (data) {
        if (data.status === false) {
            return {
                title: 'Oops! an error occurred',
                message: data.meta?.message || data.message || fallback.message,
                errors: data.errors || null,
                statusCode: status,
            };
        }

        if (data.errors) {
            const meta = data.meta;
            return {
                // title: 'Validation error',
                title: `${meta?.message || 'Oops! an error occurred'}`,
                message: data.message || fallback.message,
                errors: data.errors,
                statusCode: status,
            };
        }

        return {
            title: 'Error',
            message: data.message || fallback.message,
            errors: null,
            statusCode: status,
        };
    }

    return { ...fallback, statusCode: status };
}

// Response interceptor
http.interceptors.response.use(
    (response) => response,
    (error) => {
        const normalized = normalizeAxiosError(error);

        // If you don't want to show the modal automatically, comment this out.
        if (!error.config?.silent) {
            ErrorModal(normalized.title, normalized.message, normalized.errors);
        }

        return Promise.reject(error);
    }
);

export default http;
