@props([
    'label',
    'canAny' => [],
    'route',
    'icon'
])

@canOrRole($canAny)
    <li class="slide">
        <a
            href="{{ route($route) }}"
            @class([
                "side-menu__item",
                "active" => request()->routeIs($route)
            ])
        >
            <x-ui.lucide-icon name="{{ $icon }}" class="me-2"/>
            <span class="side-menu__label">{{ $label }}</span>
        </a>
    </li>
@endcanOrRole
