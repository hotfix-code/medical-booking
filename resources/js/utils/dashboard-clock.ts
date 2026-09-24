function pad(value: number): string {
    return String(value).padStart(2, '0');
}

function zonedParts(date: Date, timeZone: string): {hour: number; minute: number; second: number} {
    const parts = new Intl.DateTimeFormat('en-US', {
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        hourCycle: 'h23',
        timeZone,
    }).formatToParts(date);

    const valueOf = (type: Intl.DateTimeFormatPartTypes): number =>
        Number(parts.find((part) => part.type === type)?.value ?? 0);

    return {
        hour: valueOf('hour'),
        minute: valueOf('minute'),
        second: valueOf('second'),
    };
}

function formatDate(date: Date, locale: string, timeZone: string): string {
    const formatted = new Intl.DateTimeFormat(locale === 'es' ? 'es-ES' : 'en-GB', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        timeZone,
    }).format(date);

    return formatted.charAt(0).toUpperCase() + formatted.slice(1);
}

function formatTime(date: Date, locale: string, timeZone: string): string {
    const {hour, minute, second} = zonedParts(date, timeZone);
    const hour12 = hour % 12 || 12;
    const period = locale === 'es'
        ? (hour >= 12 ? 'p.m.' : 'a.m.')
        : (hour >= 12 ? 'PM' : 'AM');

    return `${pad(hour12)}:${pad(minute)}:${pad(second)} ${period}`;
}

export function startDashboardClock(root: HTMLElement): void {
    const dateEl = root.querySelector<HTMLElement>('[data-clock="date"]');
    const timeEl = root.querySelector<HTMLElement>('[data-clock="time"]');

    if (!dateEl || !timeEl) {
        return;
    }

    const locale = document.documentElement.lang || 'en';
    const timeZone = root.dataset.timezone || 'UTC';
    const serverNow = Date.parse(root.dataset.iso ?? '');
    const origin = Date.now();

    const tick = (): void => {
        const now = Number.isNaN(serverNow)
            ? new Date()
            : new Date(serverNow + (Date.now() - origin));

        dateEl.textContent = formatDate(now, locale, timeZone);
        timeEl.textContent = formatTime(now, locale, timeZone);
    };

    tick();
    timeEl.classList.add('is-ready');
    window.setInterval(tick, 1000);
}
