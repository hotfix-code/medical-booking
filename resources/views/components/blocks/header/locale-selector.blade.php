@props([
    'locales' => $availableLocales ?? collect(),
])

@php
    $current = $locales->firstWhere('code', app()->getLocale()) ?? $locales->first();
@endphp

<div class="header-element country-selector">
    <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside" data-bs-toggle="dropdown">
        <img src="{{ asset($current->image_path) }}" alt="{{ $current->name }}" class="rounded-circle">
    </a>
    <ul class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
        @foreach($locales as $locale)
            <li>
                <form action="{{ route('profile.change-locale') }}" method="POST">
                    @csrf
                    <input type="hidden" name="locale" value="{{ $locale->code }}">
                    <button
                        type="submit"
                        @class([
                            'dropdown-item d-flex align-items-center w-100',
                            'active' => $locale->code === $current->code,
                        ])
                    >
                        <span class="avatar avatar-xs lh-1 me-2">
                            <img src="{{ asset($locale->image_path) }}" alt="{{ $locale->name }}">
                        </span>
                        {{ $locale->name }}
                    </button>
                </form>
            </li>
        @endforeach
    </ul>
</div>
