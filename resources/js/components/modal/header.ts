type Color = 'primary' | 'secondary' | 'success' | 'danger' | 'warning' | 'info';

export const Header = (title: string, color: Color = 'primary') => {
    return `
            <div class="d-flex justify-content-center align-items-center">
                <i class="fe fe-alert-triangle text-${color} me-1 fs-3"></i>
                <p class="m-0 text-center fs-5">${title}</p>
            </div>
        `;
};

export default Header;
