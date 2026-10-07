@props([
    'word' => true,
])

@php($mask = 'brand-'.substr(md5(uniqid('', true)), 0, 8))

<span {{ $attributes->class(['brand-lockup']) }}>
    <svg class="brand-mark" viewBox="0 0 64 64" aria-hidden="true">
        <mask id="{{ $mask }}" maskUnits="userSpaceOnUse" x="0" y="0" width="64" height="64">
            <rect width="64" height="64" fill="white"/>
            <path fill="black" d="M26 23h12v7h7v12h-7v7H26v-7h-7V30h7v-7Zm5 5v7h-7v2h7v7h2v-7h7v-2h-7v-7h-2Z"/>
        </mask>
        <path
            fill="currentColor"
            mask="url(#{{ $mask }})"
            d="M32 56.8 9.3 35C4.5 30.4 4 22.5 8.3 16.7c4.5-6.1 13.1-7.4 19-2.3L32 18.5l4.7-4.1c5.9-5.1 14.5-3.8 19 2.3 4.3 5.8 3.8 13.7-1 18.3L32 56.8Z"
        />
    </svg>
    @if ($word)
        <span class="brand-name">{{ __('common.brand') }}</span>
    @endif
</span>
