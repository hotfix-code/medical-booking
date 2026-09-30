async function copyText(text: string): Promise<void> {
    if (!navigator.clipboard?.writeText) {
        throw new Error('Clipboard API is unavailable');
    }

    await navigator.clipboard.writeText(text);
}

document.querySelectorAll<HTMLButtonElement>('[data-copy-command]').forEach((button) => {
    button.addEventListener('click', async () => {
        const command = button.closest<HTMLElement>('[data-command-copy]')
            ?.querySelector('code')
            ?.textContent
            ?.trim();
        const icon = button.querySelector('i');

        if (!command || !icon) {
            return;
        }

        button.disabled = true;

        try {
            await copyText(command);
            icon.classList.replace('ri-file-copy-line', 'ri-check-line');
            button.setAttribute('aria-label', button.dataset.copiedLabel ?? '');
            button.dataset.feedback = button.dataset.copiedLabel ?? '';
            button.classList.add('copy-feedback-visible');
        } catch {
            icon.classList.replace('ri-file-copy-line', 'ri-error-warning-line');
            button.setAttribute('aria-label', button.dataset.failedLabel ?? '');
            button.dataset.feedback = button.dataset.failedLabel ?? '';
            button.classList.add('copy-feedback-visible', 'copy-feedback-failed');
        }

        window.setTimeout(() => {
            icon.classList.remove('ri-check-line', 'ri-error-warning-line');
            icon.classList.add('ri-file-copy-line');
            button.classList.remove('copy-feedback-visible', 'copy-feedback-failed');
            delete button.dataset.feedback;
            button.setAttribute('aria-label', button.dataset.copyLabel ?? '');
            button.disabled = false;
        }, 1500);
    });
});
