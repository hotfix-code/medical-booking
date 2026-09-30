function bindSettingsTabs(): void {
    document.querySelectorAll<HTMLButtonElement>('[data-settings-tab]').forEach((button) => {
        button.addEventListener('click', () => {
            const tab = button.getAttribute('data-settings-tab');

            document.querySelectorAll<HTMLButtonElement>('[data-settings-tab]').forEach((item) => {
                const active = item === button;
                item.classList.toggle('active', active);
                item.setAttribute('aria-selected', active ? 'true' : 'false');
            });

            document.querySelectorAll<HTMLElement>('[data-settings-panel]').forEach((panel) => {
                panel.hidden = panel.getAttribute('data-settings-panel') !== tab;
            });

            const url = new URL(window.location.href);
            url.searchParams.set('tab', tab ?? 'appearance');
            history.replaceState(null, '', url);
        });
    });
}

function luminance(hex: string): number {
    const value = hex.replace('#', '');
    const channel = (start: number): number => {
        const piece = Number.parseInt(value.slice(start, start + 2), 16) / 255;

        return piece <= 0.04045 ? piece / 12.92 : ((piece + 0.055) / 1.055) ** 2.4;
    };

    return (0.2126 * channel(0)) + (0.7152 * channel(2)) + (0.0722 * channel(4));
}

function contrast(first: number, second: number): number {
    const lighter = Math.max(first, second);
    const darker = Math.min(first, second);

    return (lighter + 0.05) / (darker + 0.05);
}

function sidebarInk(hex: string): string {
    const background = luminance(hex);
    const dark = '#111827';
    const light = '#f8fafc';

    return contrast(luminance(dark), background) >= contrast(luminance(light), background) ? dark : light;
}

function applyAccentColor(preview: HTMLElement, hex: string): void {
    if (hex === '') {
        delete preview.dataset.accentColor;
        preview.style.removeProperty('--settings-preview-accent');

        return;
    }

    preview.dataset.accentColor = hex;
    preview.style.setProperty('--settings-preview-accent', hex);
}

function applySidebarColor(preview: HTMLElement, hex: string): void {
    if (hex === '') {
        delete preview.dataset.sidebarColor;
        preview.style.removeProperty('--settings-preview-sidebar');
        preview.style.removeProperty('--settings-preview-ink');

        return;
    }

    preview.dataset.sidebarColor = hex;
    preview.style.setProperty('--settings-preview-sidebar', hex);
    preview.style.setProperty('--settings-preview-ink', sidebarInk(hex));
}

function bindSidebarPreview(): void {
    const preview = document.querySelector<HTMLElement>('[data-settings-preview]');

    if (!preview) {
        return;
    }

    document.querySelectorAll<HTMLInputElement>('input[name="sidebar_style"]').forEach((input) => {
        input.addEventListener('change', () => {
            if (!input.checked) {
                return;
            }

            preview.dataset.sidebarStyle = input.value;
        });
    });

    document.querySelectorAll<HTMLInputElement>('input[name="sidebar_color"]').forEach((input) => {
        input.addEventListener('change', () => {
            if (!input.checked) {
                return;
            }

            applySidebarColor(preview, input.value);
        });
    });

    document.querySelectorAll<HTMLInputElement>('input[name="accent_color"]').forEach((input) => {
        input.addEventListener('change', () => {
            if (!input.checked) {
                return;
            }

            applyAccentColor(preview, input.value);
        });
    });
}

bindSettingsTabs();
bindSidebarPreview();
