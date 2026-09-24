function closeAll(except?: HTMLElement): void {
    document.querySelectorAll<HTMLElement>('[data-period]').forEach((root) => {
        if (root === except) {
            return;
        }

        const menu = root.querySelector<HTMLElement>('[data-period-menu]');
        const trigger = root.querySelector<HTMLButtonElement>('[data-period-trigger]');

        menu?.setAttribute('hidden', '');
        trigger?.setAttribute('aria-expanded', 'false');
        root.classList.remove('is-open');
    });
}

function bindOne(root: HTMLElement): void {
    const trigger = root.querySelector<HTMLButtonElement>('[data-period-trigger]');
    const menu = root.querySelector<HTMLElement>('[data-period-menu]');
    const label = root.querySelector<HTMLElement>('[data-period-label]');
    const select = root.querySelector<HTMLSelectElement>('select');

    if (!trigger || !menu || !select) {
        return;
    }

    const setValue = (value: string, optionLabel: string): void => {
        if (select.value === value) {
            menu.setAttribute('hidden', '');
            trigger.setAttribute('aria-expanded', 'false');
            root.classList.remove('is-open');

            return;
        }

        select.value = value;
        select.dispatchEvent(new Event('change', {bubbles: true}));

        if (label) {
            label.textContent = optionLabel;
        }

        root.querySelectorAll('[data-period-option]').forEach((option) => {
            option.classList.toggle('is-selected', option.getAttribute('data-value') === value);
        });

        menu.setAttribute('hidden', '');
        trigger.setAttribute('aria-expanded', 'false');
        root.classList.remove('is-open');
    };

    trigger.addEventListener('click', (event) => {
        event.stopPropagation();
        const willOpen = menu.hasAttribute('hidden');

        closeAll();

        if (willOpen) {
            menu.removeAttribute('hidden');
            trigger.setAttribute('aria-expanded', 'true');
            root.classList.add('is-open');
        }
    });

    menu.querySelectorAll<HTMLButtonElement>('[data-period-option]').forEach((option) => {
        option.addEventListener('click', (event) => {
            event.stopPropagation();
            setValue(option.dataset.value ?? '', option.textContent?.trim() ?? '');
        });
    });
}

export function bindPeriodSelects(): void {
    document.querySelectorAll<HTMLElement>('[data-period]').forEach(bindOne);

    document.addEventListener('click', () => closeAll());
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeAll();
        }
    });
}
