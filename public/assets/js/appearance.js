(function () {
    function clearSwitcher() {
        localStorage.removeItem('sashMenu');
        localStorage.removeItem('sashverticalstyles');
    }

    function apply(appearance) {
        const root = document.documentElement;

        root.setAttribute('data-menu-styles', appearance.sidebarStyle);

        if (appearance.sidebarColor) {
            root.style.setProperty('--menu-bg', appearance.sidebarColor);
            root.style.setProperty('--menu-prime-color', appearance.sidebarInk);
            root.style.setProperty('--menu-border-color', 'transparent');
        } else {
            root.style.removeProperty('--menu-bg');
            root.style.removeProperty('--menu-prime-color');
            root.style.removeProperty('--menu-border-color');
        }

        if (appearance.accentRgb) {
            root.style.setProperty('--primary-rgb', appearance.accentRgb);
        } else {
            root.style.removeProperty('--primary-rgb');
        }
    }

    window.clearAppearanceSwitcher = clearSwitcher;
    window.applyAppearance = apply;
})();
