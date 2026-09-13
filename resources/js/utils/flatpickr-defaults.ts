import flatpickr from 'flatpickr';
import { Spanish } from 'flatpickr/dist/l10n/es';

export function applyFlatpickrDefaults(): void {
    const locale = document.documentElement.lang.split('-')[0];
    if (locale === 'es') {
        flatpickr.localize({ ...Spanish, time_24hr: false });
    }
}

applyFlatpickrDefaults();
