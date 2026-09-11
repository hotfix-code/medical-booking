declare global {
    interface Window {
        i18n?: Record<string, unknown>;
    }
}

export function t(key: string, replace: Record<string, string> = {}): string {
    const value = key.split('.').reduce<unknown>((acc, part) => {
        if (acc && typeof acc === 'object' && part in acc) {
            return (acc as Record<string, unknown>)[part];
        }
        return undefined;
    }, window.i18n);

    if (typeof value !== 'string') {
        return key;
    }

    return Object.entries(replace).reduce(
        (text, [name, to]) => text.split(`:${name}`).join(to),
        value,
    );
}

export function roleLabel(name?: string | null): string {
    if (!name) {
        return t('common.states.na');
    }

    const key = `enums.role.${name}`;
    const label = t(key);

    return label === key ? name : label;
}
