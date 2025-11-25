type Input = {
    grid?: string;
    id?: string;
    name?: string;
    type?: string,
    label?: string,
    placeholder?: string,
    value?: string,
    disabled?: boolean,
    required?: boolean,
    readonly?: boolean,
    autofocus?: boolean,
    autocomplete?: string,
};

export const Input = (props: Input) => {

    const defaultProps = {
        type: 'text',
        label: '',
        placeholder: '',
        value: '',
        disabled: false,
        required: false,
        readonly: false,
        autofocus: false,
        autocomplete: 'off',
        grid: 'col-xl-4 col-lg-6 col-md-6 col-sm-12',
        id: 'input-label',
        name: 'input-name',
    };

    Object.assign(defaultProps, props);

    return `
        <div class="${defaultProps.grid}">
            <label for="${defaultProps.id}" class="form-label float-start">${defaultProps.label}:</label>
            <input type="${defaultProps.type}"
                   class="form-control"
                   id="${defaultProps.id}"
                   name="${defaultProps.name}"
                   placeholder="${defaultProps.placeholder}"
                   value="${defaultProps.value}"
            >
        </div>
    `;
};
