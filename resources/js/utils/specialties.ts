type SpecialtyItem = {
    label: string;
    count: number;
    percent: number;
};

type SpecialtiesBundle = {
    period?: string;
    empty?: string;
    subtitles?: Record<string, string>;
    periods?: Record<string, SpecialtyItem[]>;
};

function applySubtitle(key: string, subtitles: Record<string, string> | undefined): void {
    const el = document.querySelector('[data-specialties-subtitle]');
    const text = subtitles?.[key];

    if (el && text) {
        el.textContent = text;
    }
}

function parseBundle(raw: string | null): SpecialtiesBundle | null {
    if (!raw) {
        return null;
    }

    try {
        const parsed: unknown = JSON.parse(raw);

        if (typeof parsed !== 'object' || parsed === null) {
            return null;
        }

        return parsed as SpecialtiesBundle;
    } catch {
        return null;
    }
}

function paintList(root: HTMLElement, items: SpecialtyItem[], empty: string): void {
    root.replaceChildren();

    if (items.length === 0) {
        const emptyEl = document.createElement('div');
        emptyEl.className = 'dashboard-chart-empty';
        emptyEl.textContent = empty;
        root.append(emptyEl);

        return;
    }

    items.forEach((item) => {
        const row = document.createElement('div');
        row.className = 'dashboard-specialty-row';
        row.innerHTML = `<span class="dashboard-specialty-name"></span><div class="dashboard-specialty-track"><span class="dashboard-specialty-fill"></span></div><span class="dashboard-specialty-count"></span>`;

        const name = row.querySelector('.dashboard-specialty-name');
        const fill = row.querySelector<HTMLElement>('.dashboard-specialty-fill');
        const count = row.querySelector('.dashboard-specialty-count');

        if (name) {
            name.textContent = item.label;
        }

        if (fill) {
            fill.style.width = '0%';
            requestAnimationFrame(() => {
                fill.style.width = `${item.percent}%`;
            });
        }

        if (count) {
            count.textContent = String(item.count);
        }

        root.append(row);
    });
}

export function bindSpecialties(raw: string | null): void {
    const bundle = parseBundle(raw);
    const select = document.querySelector<HTMLSelectElement>('[data-specialties-period]');
    const list = document.querySelector<HTMLElement>('[data-specialties-list]');

    if (!bundle || !list) {
        return;
    }

    const empty = bundle.empty ?? '';
    const periodKey = select?.value || bundle.period || 'month';

    paintList(list, bundle.periods?.[periodKey] ?? [], empty);
    applySubtitle(periodKey, bundle.subtitles);

    select?.addEventListener('change', () => {
        applySubtitle(select.value, bundle.subtitles);
        paintList(list, bundle.periods?.[select.value] ?? [], empty);
    });
}
