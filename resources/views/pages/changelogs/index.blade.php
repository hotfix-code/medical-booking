@use('App\Enums\ChangelogCategory')

@php
    $categoryStyles = [
        'added' => ['color' => 'success'],
        'changed' => ['color' => 'info'],
        'fixed' => ['color' => 'warning'],
        'technical' => ['color' => 'secondary'],
    ];
@endphp

<x-app-layout>
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <div>
                    <h1 class="page-title my-auto">{{ __('changelog.title') }}</h1>
                    <p class="text-muted mb-0">{{ __('changelog.subtitle') }}</p>
                </div>
            </div>

            @forelse($releases as $version => $entries)
                <div class="card custom-card mb-4">
                    <div class="card-header justify-content-between">
                        <div>
                            <h2 class="card-title mb-1" style="font-size: 1.5em;">v{{ $version }}</h2>
                            <span class="text-muted fs-12">
                                {{ $entries->first()->released_at->format('Y-m-d') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach(ChangelogCategory::cases() as $category)
                            @php
                                $categoryEntries = $entries->filter(
                                    fn ($entry) => $entry->category === $category
                                );
                            @endphp

                            @if($categoryEntries->isNotEmpty())
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <h3 class="fs-14 fw-semibold mb-0">
                                        {{ __('changelog.categories.' . $category->value) }}
                                    </h3>
                                    <span class="badge bg-{{ $categoryStyles[$category->value]['color'] }}-transparent text-{{ $categoryStyles[$category->value]['color'] }}">
                                        {{ $categoryEntries->count() }}
                                    </span>
                                </div>
                                <ul class="list-group list-group-flush mb-4">
                                    @foreach($categoryEntries as $entry)
                                        <li class="list-group-item px-0 py-2 border-0 border-bottom d-flex align-items-start gap-2">
                                            <i class="ri-checkbox-blank-circle-fill fs-8 text-{{ $categoryStyles[$category->value]['color'] }} mt-2"></i>
                                            <span>{{ $entry->translatedDescription() }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-info">{{ __('changelog.empty') }}</div>
            @endforelse
        </div>
    </div>
</x-app-layout>
