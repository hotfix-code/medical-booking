@props([
    'label',
    'canAny' => [],
    'route',
])

@canOrRole($canAny)
    <li class="slide">
        <a
            href="{{ route($route) }}"
            @class(["side-menu__item", "active" => request()->routeIs($route)])
        >
            {{ $label }}
        </a>
    </li>
@endcanOrRole
