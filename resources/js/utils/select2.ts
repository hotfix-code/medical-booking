/**
 * Clears a Select2 dropdown by removing the selected value and all options except the first one.
 * This is useful for dynamic selects where options are loaded based on other selections.
 *
 * @param {JQuery<HTMLElement> | string} selector - A jQuery object or a selector string representing the Select2 dropdown to clear.
 * @return {void} This method does not return a value.
 */
function clearSingleSelect2(selector: JQuery<HTMLElement> | string): void {
    const select = typeof selector === 'string' ? $(selector) : selector;
    select.val('').trigger('change');
    select.find('option').slice(1).remove();
    select.trigger('change');
}

/**
 * Resets a Select2 dropdown to its initial state by clearing the selected value but preserving all original options.
 * This is ideal when you want to deselect the current choice but keep all available options.
 *
 * @param {JQuery<HTMLElement> | string} selector - A jQuery object or a selector string representing the Select2 dropdown to reset.
 * @return {void} This method does not return a value.
 */
function resetSingleSelect2(selector: JQuery<HTMLElement> | string): void {
    const select = typeof selector === 'string' ? $(selector) : selector;
    // Only clear the selected value, keep all options
    select.val('').trigger('change');
}

/**
 * Resets Select2 components to their initial state by clearing selected values while preserving all options.
 * Any Select2 instance associated with the provided selector(s) will have their selection cleared
 * but will maintain all their original options.
 *
 * @param {...(JQuery<HTMLElement> | string | (JQuery<HTMLElement> | string)[])} selectors
 *        One or more selectors, JQuery elements, or arrays of selectors/JQuery elements
 *        for which the Select2 component should be reset.
 * @return {void} This function doesn't return any value.
 *
 * @example
 * // Single selector (string) - clears selection, keeps all options
 * resetSelect2('#select1');
 *
 * @example
 * // Single selector (jQuery object) - clears selection, keeps all options
 * resetSelect2($('#select1'));
 *
 * @example
 * // Multiple individual selectors
 * resetSelect2('#select1', '#select2', '#select3');
 * resetSelect2($('#select1'), $('#select2'));
 *
 * @example
 * // Array of selectors
 * resetSelect2(['#select1', '#select2', '#select3']);
 * resetSelect2([$('#select1'), $('#select2')]);
 *
 * @example
 * // Mixed formats - combines individual selectors, arrays, and jQuery objects
 * resetSelect2('#select1', ['#select2', '#select3'], $('#select4'));
 */
export function resetSelect2(
    ...selectors: (JQuery<HTMLElement> | string | (JQuery<HTMLElement> | string)[])[]
): void {
    if (selectors.length === 0) {
        console.warn('resetSelect2: No selectors were provided. The function requires at least one selector to work.');
        return;
    }

    selectors.forEach(selector => {
        if (Array.isArray(selector)) {
            selector.forEach(item => resetSingleSelect2(item));
        } else {
            resetSingleSelect2(selector);
        }
    });
}

/**
 * Clears Select2 components by removing selected values and all options except the first one.
 * This is useful for dependent dropdowns where you need to remove dynamically loaded options.
 *
 * @param {...(JQuery<HTMLElement> | string | (JQuery<HTMLElement> | string)[])} selectors
 *        One or more selectors, JQuery elements, or arrays of selectors/JQuery elements
 *        for which the Select2 component should be cleared.
 * @return {void} This function doesn't return any value.
 *
 * @example
 * // Clear single selector - removes selection and all options except first
 * clearSelect2('#countrySelect');
 *
 * @example
 * // Clear multiple dependent selects after country change
 * clearSelect2('#stateSelect', '#citySelect');
 *
 * @example
 * // Clear array of selectors
 * clearSelect2(['#stateSelect', '#citySelect', '#districtSelect']);
 */
export function clearSelect2(
    ...selectors: (JQuery<HTMLElement> | string | (JQuery<HTMLElement> | string)[])[]
): void {
    if (selectors.length === 0) {
        console.warn('clearSelect2: No selectors were provided. The function requires at least one selector to work.');
        return;
    }

    selectors.forEach(selector => {
        if (Array.isArray(selector)) {
            selector.forEach(item => clearSingleSelect2(item));
        } else {
            clearSingleSelect2(selector);
        }
    });
}
