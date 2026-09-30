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

            @if (session('success'))
                <div class="alert alert-success" role="status">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
            @endif

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

            <div data-settings-panel="appearance"@unless($tab === 'appearance') hidden @endunless>
                <form method="POST" action="{{ route('settings.appearance.update') }}">
                    @csrf
                    @method('PUT')
                <div class="row">
                    <div class="col-lg-7">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-2 mb-3">
                                    <i data-lucide="panel-left" class="settings-style-icon"></i>
                                    <div>
                                        <h2 class="settings-style-title">{{ __('settings.style.title') }}</h2>
                                        <p class="text-muted mb-0">{{ __('settings.style.subtitle') }}</p>
                                    </div>
                                </div>

                                <fieldset class="settings-style-options">
                                    <legend class="visually-hidden">{{ __('settings.style.title') }}</legend>

                                    <label class="settings-style-option">
                                        <input type="radio" name="sidebar_style" value="light" @checked($sidebarStyle === 'light')>
                                        <span class="settings-style-option-box">
                                            <span class="settings-style-option-head">
                                                <span class="settings-style-radio"></span>
                                                <span>{{ __('settings.style.light') }}</span>
                                            </span>
                                            <span class="settings-style-thumb is-light" aria-hidden="true">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="settings-style-option-caption">{{ __('settings.style.light_caption') }}</span>
                                    </label>

                                    <label class="settings-style-option">
                                        <input type="radio" name="sidebar_style" value="dark" @checked($sidebarStyle === 'dark')>
                                        <span class="settings-style-option-box">
                                            <span class="settings-style-option-head">
                                                <span class="settings-style-radio"></span>
                                                <span>{{ __('settings.style.dark') }}</span>
                                            </span>
                                            <span class="settings-style-thumb is-dark" aria-hidden="true">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                            </span>
                                        </span>
                                        <span class="settings-style-option-caption">{{ __('settings.style.dark_caption') }}</span>
                                    </label>
                                </fieldset>

                                <div class="settings-color-block">
                                    <h3 class="settings-color-title">{{ __('settings.sidebar_color.title') }}</h3>
                                    <p class="text-muted mb-3">{{ __('settings.sidebar_color.subtitle') }}</p>
                                    <fieldset class="settings-swatches">
                                        <legend class="visually-hidden">{{ __('settings.sidebar_color.title') }}</legend>
                                        <label class="settings-swatch is-none">
                                            <input type="radio" name="sidebar_color" value="" @checked($sidebarColor === '')>
                                            <span>{{ __('settings.sidebar_color.none') }}</span>
                                        </label>
                                        @foreach ($sidebarColors as $hex)
                                            <label class="settings-swatch">
                                                <input type="radio" name="sidebar_color" value="{{ $hex }}" @checked($sidebarColor === $hex)>
                                                <span style="background: {{ $hex }}"></span>
                                            </label>
                                        @endforeach
                                    </fieldset>
                                </div>

                                <div class="settings-color-block">
                                    <h3 class="settings-color-title">{{ __('settings.accent_color.title') }}</h3>
                                    <p class="text-muted mb-3">{{ __('settings.accent_color.subtitle') }}</p>
                                    <fieldset class="settings-swatches">
                                        <legend class="visually-hidden">{{ __('settings.accent_color.title') }}</legend>
                                        <label class="settings-swatch is-none">
                                            <input type="radio" name="accent_color" value="" @checked($accentColor === '')>
                                            <span>{{ __('settings.accent_color.none') }}</span>
                                        </label>
                                        @foreach ($accentColors as $hex)
                                            <label class="settings-swatch">
                                                <input type="radio" name="accent_color" value="{{ $hex }}" @checked($accentColor === $hex)>
                                                <span style="background: {{ $hex }}"></span>
                                            </label>
                                        @endforeach
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="card custom-card">
                            <div class="card-body">
                                <div class="d-flex align-items-start gap-2 mb-3">
                                    <i data-lucide="eye" class="settings-style-icon"></i>
                                    <div>
                                        <h2 class="settings-style-title" id="settings-preview-title">{{ __('settings.preview.title') }}</h2>
                                        <p class="text-muted mb-0">{{ __('settings.preview.subtitle') }}</p>
                                    </div>
                                </div>

                                <div
                                    class="settings-preview"
                                    data-settings-preview
                                    data-sidebar-style="{{ $sidebarStyle }}"
                                    @if ($sidebarColor !== '') data-sidebar-color="{{ $sidebarColor }}" @endif
                                    @if ($accentColor !== '') data-accent-color="{{ $accentColor }}" @endif
                                    @if ($previewStyle !== '') style="{{ $previewStyle }}" @endif
                                    aria-hidden="true"
                                >
                                    <div class="settings-preview-sidebar">
                                        <span class="settings-preview-logo"></span>
                                        <span class="settings-preview-row"></span>
                                        <span class="settings-preview-row is-active"></span>
                                        <span class="settings-preview-row"></span>
                                        <span class="settings-preview-row"></span>
                                    </div>
                                    <div class="settings-preview-card">
                                        <span class="settings-skeleton is-title"></span>
                                        <span class="settings-skeleton"></span>
                                        <span class="settings-skeleton is-short"></span>
                                        <span class="settings-skeleton"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="settings-save">
                        <button type="submit" class="btn btn-primary" data-settings-save>
                            <i data-lucide="save"></i>
                            <span>{{ __('settings.save') }}</span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="card custom-card" data-settings-panel="general"@unless($tab === 'general') hidden @endunless>
                <div class="card-body">
                    <p class="text-muted mb-0">{{ __('settings.general.empty') }}</p>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        @vite('resources/js/medical-booking/settings.ts')
    @endpush
</x-app-layout>
