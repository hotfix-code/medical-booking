@props([
    'name',
    'size' => '20px',
])

<i data-lucide="{{ $name }}"
   {{ $attributes->merge(['class' => 'text-primary']) }}
    @style([
    "width: $size",
    "height: $size"
])></i>
