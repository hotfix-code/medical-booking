import Swal from 'sweetalert2';

function renderErrors(errors) {
    if (!errors) return '';
    // It can come as an array [{field, message}] or as an object {field: [msg, ...]}
    let list = [];
    if (Array.isArray(errors)) {
        list = errors.map(e => e.message ?? e);
    } else if (typeof errors === 'object') {
        Object.values(errors).forEach(arr => {
            if (Array.isArray(arr)) list.push(...arr);
            else list.push(arr);
        });
    }
    if (!list.length) return '';
    return `
    <ul class="text-start m-0 ps-4 py-2" style="font-size: .85rem;">
        ${list.map(m => `<li>${m}</li>`).join('')}
    </ul>
  `;
}

const Modal = {
    success({ title = 'Success', text = '', html, timer = 3000, showConfirmButton = false } = {}) {
        return Swal.fire({
            icon: 'success',
            title,
            text,
            html,
            timer,
            showConfirmButton,
        });
    },

    error({ title = 'Error', message = '', errors = null, footer = null } = {}) {
        const html = renderErrors(errors) || message;
        return Swal.fire({
            icon: 'error',
            title,
            html,
            footer,
        });
    },

    info({ title = 'Info', text = '', html } = {}) {
        return Swal.fire({
            icon: 'info',
            title,
            text,
            html,
        });
    },

    confirm({
                title = 'Are you sure?',
                text = '',
                html,
                confirmButtonText = 'Aceptar',
                cancelButtonText = 'Cancel',
                showCancelButton = true,
                preConfirm, // Optional function that returns a promise
                allowOutsideClick = () => !Swal.isLoading(),
                showLoaderOnConfirm = !!preConfirm,
            } = {}) {
        return Swal.fire({
            icon: 'question',
            title,
            text,
            html,
            showCancelButton,
            confirmButtonText,
            cancelButtonText,
            preConfirm,
            allowOutsideClick,
            showLoaderOnConfirm,
        });
    },

    // Useful when you want to display a loader manually
    loading(title = 'Loading...') {
        Swal.fire({
            title,
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
        });
    },

    close() {
        Swal.close();
    },
};

export default Modal;
