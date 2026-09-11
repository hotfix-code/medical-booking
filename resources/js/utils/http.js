import axios from 'axios';
import { ErrorModal } from "@/utils/SwalWrapper.js";
import { t } from "@/utils/i18n.js";

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
        title: t('messages.error'),
        message: t('messages.oops'),
        errors: null,
        statusCode: 0,
    };

    if (!error.response) {
        return {
            ...fallback,
            message: t('messages.no_server_response'),
        };
    }

    const { status, data } = error.response;

    // Supports two common forms:
    // a) Laravel Validation: { message: "...", errors: { field: [msg] } }
    // b) Custom format: { status: "error", errors: [{ field, message }], meta: { message } }
    if (data) {
        if (data.status === false) {
            return {
                title: t('messages.oops'),
                message: data.meta?.message || data.message || fallback.message,
                errors: data.errors || null,
                statusCode: status,
            };
        }

        if (data.errors) {
            const meta = data.meta;
            return {
                title: `${meta?.message || t('messages.oops')}`,
                message: data.message || fallback.message,
                errors: data.errors,
                statusCode: status,
            };
        }

        return {
            title: t('messages.error'),
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
