@props([
    'label',
    'canAny' => [],
])

@canOrRole($canAny)
    <li class="slide__category">
            <span class="category-name">
                {{ $label }}
            </span>
    </li>
@endcanOrRole
