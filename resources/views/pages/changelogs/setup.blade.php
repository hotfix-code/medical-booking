<x-app-layout>
    <div class="main-content app-content">
        <div class="container-fluid">
            <div class="page-header">
                <div>
                    <h1 class="page-title my-auto">{{ __('changelog.setup.title') }}</h1>
                    <p class="text-muted mb-0">{{ __('changelog.setup.description') }}</p>
                </div>
            </div>

            <div class="card custom-card">
                <div class="card-body">
                    <h2 class="fs-16 fw-semibold">{{ __('changelog.setup.environment_title') }}</h2>
                    <p>{{ __('changelog.setup.environment_description') }}</p>
                    <pre class="bg-light p-3 rounded"><code>APP_ENV=local
APP_CHANGELOG_ENABLED=true</code></pre>

                    <h2 class="fs-16 fw-semibold mt-4">{{ __('changelog.setup.commands_title') }}</h2>
                    <p>{{ __('changelog.setup.commands_description') }}</p>
                    <ul class="nav nav-tabs" id="changelog-command-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active"
                                    id="changelog-traditional-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#changelog-traditional-pane"
                                    type="button"
                                    role="tab"
                                    aria-controls="changelog-traditional-pane"
                                    aria-selected="true"
                            >{{ __('changelog.setup.traditional_tab') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link"
                                    id="changelog-sail-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#changelog-sail-pane"
                                    type="button"
                                    role="tab"
                                    aria-controls="changelog-sail-pane"
                                    aria-selected="false"
                            >{{ __('changelog.setup.sail_tab') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link"
                                    id="changelog-sail-binary-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#changelog-sail-binary-pane"
                                    type="button"
                                    role="tab"
                                    aria-controls="changelog-sail-binary-pane"
                                    aria-selected="false"
                            >{{ __('changelog.setup.sail_binary_tab') }}</button>
                        </li>
                    </ul>

                    <div class="tab-content pt-3">
                        <div class="tab-pane fade show active"
                             id="changelog-traditional-pane"
                             role="tabpanel"
                             aria-labelledby="changelog-traditional-tab"
                             tabindex="0"
                        >
                            <p>{{ __('changelog.setup.traditional_description') }}</p>
                            <ol class="ps-3">
                                <li class="mb-3">
                                    {{ __('changelog.setup.clear_config') }}
                                    <div class="position-relative" data-command-copy>
                                        <pre class="bg-light p-3 pe-5 rounded mt-2 mb-0"><code>php artisan config:clear</code></pre>
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                                type="button"
                                                data-copy-command
                                                aria-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copy-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copied-label="{{ __('changelog.setup.command_copied') }}"
                                                data-failed-label="{{ __('changelog.setup.copy_command_failed') }}"
                                        >
                                            <i class="ri-file-copy-line" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    {{ __('changelog.setup.run_migrations') }}
                                    <div class="position-relative" data-command-copy>
                                        <pre class="bg-light p-3 pe-5 rounded mt-2 mb-0"><code>php artisan migrate --path=database/migrations/changelog</code></pre>
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                                type="button"
                                                data-copy-command
                                                aria-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copy-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copied-label="{{ __('changelog.setup.command_copied') }}"
                                                data-failed-label="{{ __('changelog.setup.copy_command_failed') }}"
                                        >
                                            <i class="ri-file-copy-line" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                                <li>
                                    {{ __('changelog.setup.load_entries') }}
                                    <div class="position-relative" data-command-copy>
                                        <pre class="bg-light p-3 pe-5 rounded mt-2 mb-0"><code>php artisan db:seed --class=ChangelogSeeder</code></pre>
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                                type="button"
                                                data-copy-command
                                                aria-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copy-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copied-label="{{ __('changelog.setup.command_copied') }}"
                                                data-failed-label="{{ __('changelog.setup.copy_command_failed') }}"
                                        >
                                            <i class="ri-file-copy-line" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                            </ol>
                        </div>
                        <div class="tab-pane fade"
                             id="changelog-sail-pane"
                             role="tabpanel"
                             aria-labelledby="changelog-sail-tab"
                             tabindex="0"
                        >
                            <p>{{ __('changelog.setup.sail_description') }}</p>
                            <ol class="ps-3">
                                <li class="mb-3">
                                    {{ __('changelog.setup.clear_config') }}
                                    <div class="position-relative" data-command-copy>
                                        <pre class="bg-light p-3 pe-5 rounded mt-2 mb-0"><code>sail artisan config:clear</code></pre>
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                                type="button"
                                                data-copy-command
                                                aria-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copy-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copied-label="{{ __('changelog.setup.command_copied') }}"
                                                data-failed-label="{{ __('changelog.setup.copy_command_failed') }}"
                                        >
                                            <i class="ri-file-copy-line" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    {{ __('changelog.setup.run_migrations') }}
                                    <div class="position-relative" data-command-copy>
                                        <pre class="bg-light p-3 pe-5 rounded mt-2 mb-0"><code>sail artisan migrate --path=database/migrations/changelog</code></pre>
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                                type="button"
                                                data-copy-command
                                                aria-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copy-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copied-label="{{ __('changelog.setup.command_copied') }}"
                                                data-failed-label="{{ __('changelog.setup.copy_command_failed') }}"
                                        >
                                            <i class="ri-file-copy-line" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                                <li>
                                    {{ __('changelog.setup.load_entries') }}
                                    <div class="position-relative" data-command-copy>
                                        <pre class="bg-light p-3 pe-5 rounded mt-2 mb-0"><code>sail artisan db:seed --class=ChangelogSeeder</code></pre>
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                                type="button"
                                                data-copy-command
                                                aria-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copy-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copied-label="{{ __('changelog.setup.command_copied') }}"
                                                data-failed-label="{{ __('changelog.setup.copy_command_failed') }}"
                                        >
                                            <i class="ri-file-copy-line" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                            </ol>
                        </div>
                        <div class="tab-pane fade"
                             id="changelog-sail-binary-pane"
                             role="tabpanel"
                             aria-labelledby="changelog-sail-binary-tab"
                             tabindex="0"
                        >
                            <p>{{ __('changelog.setup.sail_binary_description') }}</p>
                            <ol class="ps-3">
                                <li class="mb-3">
                                    {{ __('changelog.setup.clear_config') }}
                                    <div class="position-relative" data-command-copy>
                                        <pre class="bg-light p-3 pe-5 rounded mt-2 mb-0"><code>./vendor/bin/sail artisan config:clear</code></pre>
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                                type="button"
                                                data-copy-command
                                                aria-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copy-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copied-label="{{ __('changelog.setup.command_copied') }}"
                                                data-failed-label="{{ __('changelog.setup.copy_command_failed') }}"
                                        >
                                            <i class="ri-file-copy-line" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                                <li class="mb-3">
                                    {{ __('changelog.setup.run_migrations') }}
                                    <div class="position-relative" data-command-copy>
                                        <pre class="bg-light p-3 pe-5 rounded mt-2 mb-0"><code>./vendor/bin/sail artisan migrate --path=database/migrations/changelog</code></pre>
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                                type="button"
                                                data-copy-command
                                                aria-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copy-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copied-label="{{ __('changelog.setup.command_copied') }}"
                                                data-failed-label="{{ __('changelog.setup.copy_command_failed') }}"
                                        >
                                            <i class="ri-file-copy-line" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                                <li>
                                    {{ __('changelog.setup.load_entries') }}
                                    <div class="position-relative" data-command-copy>
                                        <pre class="bg-light p-3 pe-5 rounded mt-2 mb-0"><code>./vendor/bin/sail artisan db:seed --class=ChangelogSeeder</code></pre>
                                        <button class="btn btn-light btn-sm position-absolute top-0 end-0 m-2"
                                                type="button"
                                                data-copy-command
                                                aria-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copy-label="{{ __('changelog.setup.copy_command') }}"
                                                data-copied-label="{{ __('changelog.setup.command_copied') }}"
                                                data-failed-label="{{ __('changelog.setup.copy_command_failed') }}"
                                        >
                                            <i class="ri-file-copy-line" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </li>
                            </ol>
                        </div>
                    </div>

                    <div class="alert alert-warning mb-0" role="alert">
                        {{ __('changelog.setup.local_only_warning') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        @vite('resources/js/medical-booking/changelog-setup.ts')
    @endpush
</x-app-layout>
