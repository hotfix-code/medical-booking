@props([
    'id' => null,
    'permission' => null,
    'buttons' => [],
    'renderButtons' => [
        'view' => false,
        'edit' => false,
        'delete' => false
    ],
])

@php
    $buttonConfig = [
        'view' => [
            'action' => 'action-view',
            'icon' => 'bx bi-eye',
            'color' => 'text-warning',
            'permission' => '.view'
        ],
        'edit' => [
            'action' => 'action-edit',
            'icon' => 'bx bx-edit',
            'color' => 'text-info',
            'permission' => '.edit'
        ],
        'delete' => [
            'action' => 'action-delete',
            'icon' => 'bx bx-trash',
            'color' => 'text-danger',
            'permission' => '.delete',
            'extraClass' => 'btn-sm'
        ]
    ];

    foreach ($buttons as $button)
    {
        if (array_key_exists($button, $renderButtons))
        {
            $renderButtons[$button] = true;
        }
    }
@endphp

<div class="btn-group datatables-action-buttons" data-id="{{ $id }}">
    @foreach($buttonConfig as $buttonType => $config)
        @if($renderButtons[$buttonType] && (is_null($permission) || auth()->user()->can($permission . $config['permission']) || auth()->user()->hasRole('super-admin')))
            <button type="button" class="btn btn-icon-only border-0 {{ $config['extraClass'] ?? '' }} {{ $config['action'] }}">
                <i class="{{ $config['icon'] }} {{ $config['color'] }}"></i>
            </button>
        @endif
    @endforeach
</div>
