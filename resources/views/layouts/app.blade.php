@use('App\Enums\Role')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">
<head>
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ __('common.app_name') }}</title>
    <meta name="Description" content="">
    <meta name="Author" content="">
    <meta name="keywords" content="">
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <x-blocks.header.i18n />
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >
    <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet" >
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" >
    <link href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet" >

    @stack('css')

    <link rel="stylesheet" href="{{ asset('assets/css/app-custom.css') }}">

    @stack('custom-css')
</head>
<body>
<div class="page">
    <header class="app-header">
        <div class="main-header-container container-fluid">
            <div class="header-content-left">
                <div class="header-element">
                    <div class="horizontal-logo">
                        <a href="{{ route('dashboard') }}" class="header-logo">
                            <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                        </a>
                    </div>
                </div>
                <div class="header-element">
                    <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                </div>
            </div>
            <div class="header-content-right">
                <x-blocks.header.locale-selector />
                <div class="header-element main-profile-user">
                    <a href="#" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="me-xxl-2 me-0">
                                <img src="{{ asset('assets/images/faces/9.jpg') }}" alt="img" width="32" height="32" class="rounded-circle">
                            </div>
                            <div class="d-xxl-block d-none my-auto">
                                <h6 class="fw-semibold mb-0 lh-1 fs-14">
                                    {{ Auth::user()->fullname }}
                                </h6>
                                <span class="op-7 fw-normal d-block fs-11 text-muted">{{ Role::label(Auth::user()->role) }}</span>
                            </div>
                        </div>
                    </a>
                    <ul class="main-header-dropdown dropdown-menu pt-0 header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                        <li class="drop-heading d-xxl-none d-block">
                            <div class="text-center">
                                <h5 class="text-dark mb-0 fs-14 fw-semibold">
                                    {{ Auth::user()->fullname }}
                                </h5>
                                <small class="text-muted">{{ Role::label(Auth::user()->role) }}</small>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <a class="d-flex w-100" href="{{ route('profile.edit') }}">
                                <i class="fe fe-user fs-18 me-2 text-primary"></i>{{ __('common.profile') }}
                            </a>
                        </li>
                        <li class="dropdown-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="d-flex w-100" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    <i class="fe fe-info fs-18 me-2 text-primary"></i>{{ __('common.logout') }}
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <aside class="app-sidebar sticky" id="sidebar">
        <div class="main-sidebar-header">
            <a href="{{ route('dashboard') }}" class="header-logo">
                <img src="{{ asset('assets/images/brand-logos/logo.png') }}" alt="logo" class="desktop-logo">
                <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
            </a>
        </div>
        <div class="main-sidebar" id="sidebar-scroll">
            <nav class="main-menu-container nav nav-pills flex-column sub-open">
                <div class="slide-left" id="slide-left">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
                </div>
                <ul class="main-menu">

                    <x-blocks.nav.item-category :label="__('nav.categories.main')" />
                    <x-blocks.nav.item-link :label="__('nav.items.dashboard')" route="dashboard" icon="home" />

                    <x-blocks.nav.item-category :label="__('nav.categories.user_management')" :canAny="['patient.view', 'doctor.view']"/>
                    <x-blocks.nav.item-sub :label="__('nav.items.patients')" icon="users-round" :canAny="['patient.view']" active-when="patients.*">
                        <x-blocks.nav.link :label="__('nav.items.list')" route="patients.index" :canAny="['patient.view']"/>
                    </x-blocks.nav.item-sub>
                    <x-blocks.nav.item-sub :label="__('nav.items.doctors')" icon="stethoscope" :canAny="['doctor.view']" active-when="doctors.*">
                        <x-blocks.nav.link :label="__('nav.items.list')" route="doctors.index" :canAny="['doctor.view']"/>
                    </x-blocks.nav.item-sub>

                    <x-blocks.nav.item-category :label="__('nav.categories.scheduling')" :canAny="['schedule.view', 'consulting_room.view']"/>
                    <x-blocks.nav.item-sub :label="__('nav.items.doctor_schedules')" icon="calendar-clock" :canAny="['schedule.view']" active-when="schedules.*">
                        <x-blocks.nav.link :label="__('nav.items.list')" route="schedules.index" :canAny="['schedule.view']"/>
                    </x-blocks.nav.item-sub>
                    <x-blocks.nav.item-sub :label="__('nav.items.consulting_room')" icon="hospital" :canAny="['consulting_room.view']" active-when="consulting-rooms.*">
                        <x-blocks.nav.link :label="__('nav.items.list')" route="consulting-rooms.index" :canAny="['consulting_room.view']"/>
                    </x-blocks.nav.item-sub>

                    <x-blocks.nav.item-category :label="__('nav.categories.appointment')" :canAny="['appointment.view']"/>
                    <x-blocks.nav.item-sub :label="__('nav.items.appointments')" icon="calendar-plus" :canAny="['appointment.view']" active-when="appointments.*">
                        <x-blocks.nav.link :label="__('nav.items.list')" route="appointments.index" :canAny="['appointment.view']"/>
                    </x-blocks.nav.item-sub>

                    <x-blocks.nav.item-category :label="__('nav.categories.clinical')" :canAny="['specialty.view']"/>
                    <x-blocks.nav.item-sub :label="__('nav.items.specialties')" icon="bookmark" :canAny="['specialty.view']" active-when="specialties.*">
                        <x-blocks.nav.link :label="__('nav.items.list')" route="specialties.index" :canAny="['specialty.view']"/>
                    </x-blocks.nav.item-sub>

                    <x-blocks.nav.item-category :label="__('nav.categories.system')" :canAny="['user.view', 'role.view']"/>
                    <x-blocks.nav.item-link :label="__('nav.items.roles_permissions')" route="roles.index" icon="shield-check" :canAny="['role.view']"/>
                    <x-blocks.nav.item-sub :label="__('nav.items.admin_users')" icon="shield-user" :canAny="['user.view']" active-when="users.*">
                        <x-blocks.nav.link :label="__('nav.items.list')" route="users.index" :canAny="['user.view']"/>
                    </x-blocks.nav.item-sub>

                    <x-blocks.nav.item-category :label="__('nav.categories.configuration')" :canAny="['document_type.view']"/>
                    <x-blocks.nav.item-link :label="__('nav.items.document_types')" route="document-types.index" icon="id-card" :canAny="['document_type.view']"/>

                    @if (app()->environment('local') && config('changelog.enabled'))
                        <x-blocks.nav.item-category :label="__('nav.categories.development')"/>
                        <x-blocks.nav.item-link :label="__('nav.items.changelogs')" route="changelogs.index" icon="scroll-text" />
                    @endif
                </ul>
                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
            </nav>
        </div>
    </aside>

    {{ $slot }}

    <x-section.footer/>
</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

<div class="scrollToTop">
    <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
</div>
<div id="responsive-overlay"></div>

<script src="{{ asset('assets/libs/popper/core/umd/popper.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>
<script src="{{ asset('assets/js/sticky.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.js') }}"></script>

@stack('scripts')

<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons()</script>

@stack('custom-scripts')

</body>
</html>
