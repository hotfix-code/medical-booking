@props([
    'label',
    'canAny' => [],
    'route',
    'icon',
    'activeWhen' => []
])

@php
    $patterns = is_array($activeWhen) ? $activeWhen : [$activeWhen];
    $isActive = false;
    foreach ($patterns as $pattern):
        if (request()->routeIs($pattern)):
            $isActive = true;
            break;
        endif;
    endforeach;
@endphp

@canOrRole($canAny)
    <li @class(["slide has-sub", "open" => $isActive])>
        <a href="javascript:void(0);" @class(["side-menu__item", "active" => $isActive])>
            <x-ui.lucide-icon name="{{ $icon }}" class="me-2"/>
            <span class="side-menu__label">{{ $label }}</span>
            <i class="fe fe-chevron-right side-menu__angle"></i>
        </a>
        <ul class="slide-menu child1">
            <li class="slide side-menu__label1">
                <a href="javascript:void(0)">{{ $label }}</a>
            </li>
            {{ $slot }}
        </ul>
    </li>
@endcanOrRole
