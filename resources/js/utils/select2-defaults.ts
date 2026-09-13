import { t } from '@/utils/i18n';

function countMessage(singularKey: string, pluralKey: string, count: number): string {
    return t(count === 1 ? singularKey : pluralKey, { count: String(count) });
}

export function applySelect2Defaults(): void {
    const plugin = $.fn.select2;

    if (!plugin?.defaults?.set) {
        return;
    }

    plugin.defaults.set('language', {
        errorLoading: () => t('select2.error_loading'),
        inputTooLong: (args: { input: string; maximum: number }) =>
            countMessage('select2.input_too_long', 'select2.input_too_long_plural', args.input.length - args.maximum),
        inputTooShort: (args: { input: string; minimum: number }) =>
            t('select2.input_too_short', { count: String(args.minimum - args.input.length) }),
        loadingMore: () => t('select2.loading_more'),
        maximumSelected: (args: { maximum: number }) =>
            countMessage('select2.maximum_selected', 'select2.maximum_selected_plural', args.maximum),
        noResults: () => t('select2.no_results'),
        searching: () => t('select2.searching'),
        removeAllItems: () => t('select2.remove_all_items'),
        removeItem: () => t('select2.remove_item'),
        search: () => t('select2.search'),
    });
}

applySelect2Defaults();
