<x-app-layout>
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <div>
                    <h1 class="page-title my-auto">{{ __('settings.title') }}</h1>
                    <p class="text-muted mb-0">{{ __('settings.subtitle') }}</p>
                </div>
                <div>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}">{{ __('common.home') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('settings.title') }}</li>
                    </ol>
                </div>
            </div>

            <div class="settings-tabs" role="tablist">
                <button
                    type="button"
                    class="settings-tab {{ $tab === 'appearance' ? 'active' : '' }}"
                    data-settings-tab="appearance"
                    role="tab"
                    aria-selected="{{ $tab === 'appearance' ? 'true' : 'false' }}"
                >
                    <i data-lucide="palette"></i>
                    <span>{{ __('settings.tabs.appearance') }}</span>
                </button>
                <button
                    type="button"
                    class="settings-tab {{ $tab === 'general' ? 'active' : '' }}"
                    data-settings-tab="general"
                    role="tab"
                    aria-selected="{{ $tab === 'general' ? 'true' : 'false' }}"
                >
                    <i data-lucide="settings"></i>
                    <span>{{ __('settings.tabs.general') }}</span>
                </button>
            </div>

            <div class="card custom-card">
                <div class="card-body" data-settings-panel="appearance" @unless($tab === 'appearance') hidden @endunless></div>
                <div class="card-body" data-settings-panel="general" @unless($tab === 'general') hidden @endunless>
                    <p class="text-muted mb-0">{{ __('settings.general.empty') }}</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelectorAll('[data-settings-tab]').forEach((button) => {
                button.addEventListener('click', () => {
                    const tab = button.getAttribute('data-settings-tab');

                    document.querySelectorAll('[data-settings-tab]').forEach((item) => {
                        const active = item === button;
                        item.classList.toggle('active', active);
                        item.setAttribute('aria-selected', active ? 'true' : 'false');
                    });

                    document.querySelectorAll('[data-settings-panel]').forEach((panel) => {
                        panel.hidden = panel.getAttribute('data-settings-panel') !== tab;
                    });

                    const url = new URL(window.location.href);
                    url.searchParams.set('tab', tab);
                    history.replaceState(null, '', url);
                });
            });
        </script>
    @endpush
</x-app-layout>
