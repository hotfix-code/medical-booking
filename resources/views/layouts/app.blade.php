<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Medical Booking – Admin &amp; Dashboard Template </title>
    <meta name="Description" content="">
    <meta name="Author" content="">
    <meta name="keywords" content="">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    <!-- Choices JS -->
{{--    <script src="../assets/libs/choices.js/public/assets/scripts/choices.min.js"></script>--}}

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >

    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet" >

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" >

    <!-- Node Waves Css -->
{{--    <link href="../assets/libs/node-waves/waves.min.css" rel="stylesheet" >--}}

    <!-- Simplebar Css -->
    <link href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}" rel="stylesheet" >

    <!-- Color Picker Css -->
{{--    <link rel="stylesheet" href="../assets/libs/flatpickr/flatpickr.min.css">--}}
{{--    <link rel="stylesheet" href="../assets/libs/@simonwep/pickr/themes/nano.min.css">--}}

    <!-- Choices Css -->
{{--    <link rel="stylesheet" href="../assets/libs/choices.js/public/assets/styles/choices.min.css">--}}

    <!-- Jsvector Css -->
{{--    <link rel="stylesheet" href="../assets/libs/jsvectormap/css/jsvectormap.min.css">--}}

    <!-- Swiper Css -->
{{--    <link rel="stylesheet" href="../assets/libs/swiper/swiper-bundle.min.css">--}}

    <!-- Grid Css -->
{{--    <link rel="stylesheet" href="../assets/libs/gridjs/theme/mermaid.min.css">--}}

    @stack('css')

    <link rel="stylesheet" href="{{ asset('assets/css/app-custom.css') }}">

    @stack('custom-css')

</head>

<body>

<!-- Loader -->
{{--<div id="loader" >--}}
{{--    <img src="../assets/images/media/loader.svg" alt="">--}}
{{--</div>--}}
<!-- Loader -->

<div class="page">
    <!-- app-header -->
    <header class="app-header">

        <!-- Start::main-header-container -->
        <div class="main-header-container container-fluid">

            <!-- Start::header-content-left -->
            <div class="header-content-left">

                <!-- Start::header-element -->
                <div class="header-element">
                    <div class="horizontal-logo">
                        <a href="{{ route('dashboard') }}" class="header-logo">
                            <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
                        </a>
                    </div>
                </div>
                <!-- End::header-element -->

                <!-- Start::header-element -->
                <div class="header-element">
                    <!-- Start::header-link -->
                    <a aria-label="Hide Sidebar" class="sidemenu-toggle header-link animated-arrow hor-toggle horizontal-navtoggle" data-bs-toggle="sidebar" href="javascript:void(0);"><span></span></a>
                    <!-- End::header-link -->
                </div>
                <!-- End::header-element -->

            </div>
            <!-- End::header-content-left -->

            <!-- Start::header-content-right -->
            <div class="header-content-right">

                <!-- Start::header-element -->
                <div class="header-element country-selector">
                    <!-- Start::header-link|dropdown-toggle -->
                    <a href="javascript:void(0);" class="header-link dropdown-toggle" data-bs-auto-close="outside" data-bs-toggle="dropdown">
                        <img src="{{ asset(Auth::user()->localeRelation->image_path) }}" alt="img" class="rounded-circle">
                    </a>
                    <!-- End::header-link|dropdown-toggle -->
                    <ul class="main-header-dropdown dropdown-menu dropdown-menu-end" data-popper-placement="none">
                        <li>
                            <form action="{{ route('profile.change-locale') }}" method="POST">
                                @csrf
                                <a class="dropdown-item d-flex align-items-center"
                                   href="{{ route('profile.change-locale') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    <input type="hidden" name="locale" value="en">
                                    <span class="avatar avatar-xs lh-1 me-2">
                                        <img src="{{ asset('assets/images/flags/us_flag.jpg') }}" alt="img">
                                    </span>
                                    English
                                </a>
                            </form>
                        </li>
{{--                        <li>--}}
{{--                            <form action="{{ route('profile.change-locale') }}" method="POST">--}}
{{--                                @csrf--}}
{{--                                <a class="dropdown-item d-flex align-items-center"--}}
{{--                                   href="{{ route('profile.change-locale') }}"--}}
{{--                                   onclick="event.preventDefault(); this.closest('form').submit();"--}}
{{--                                >--}}
{{--                                    <input type="hidden" name="locale" value="es">--}}
{{--                                        <span class="avatar avatar-xs lh-1 me-2">--}}
{{--                                            <img src="{{ asset('assets/images/flags/spain_flag.jpg') }}" alt="img" >--}}
{{--                                        </span>--}}
{{--                                    Spanish--}}
{{--                                </a>--}}
{{--                            </form>--}}
{{--                        </li>--}}
                    </ul>
                </div>
                <!-- End::header-element -->

                <!-- Start::header-element -->
                <div class="header-element main-profile-user">
                    <!-- Start::header-link|dropdown-toggle -->
                    <a href="#" class="header-link dropdown-toggle" id="mainHeaderProfile" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                        <div class="d-flex align-items-center">
                            <div class="me-xxl-2 me-0">
                                <img src="{{ asset('assets/images/faces/9.jpg') }}" alt="img" width="32" height="32" class="rounded-circle">
                            </div>
                            <div class="d-xxl-block d-none my-auto">
                                <h6 class="fw-semibold mb-0 lh-1 fs-14">
                                    {{ Auth::user()->fullname }}
                                </h6>
                                <span class="op-7 fw-normal d-block fs-11 text-muted">{{ ucwords(Auth::user()->roles[0]->name, '- ') }}</span>
                            </div>
                        </div>
                    </a>
                    <!-- End::header-link|dropdown-toggle -->
                    <ul class="main-header-dropdown dropdown-menu pt-0 header-profile-dropdown dropdown-menu-end" aria-labelledby="mainHeaderProfile">
                        <li class="drop-heading d-xxl-none d-block">
                            <div class="text-center">
                                <h5 class="text-dark mb-0 fs-14 fw-semibold">
                                    {{ Auth::user()->fullname }}
                                </h5>
                                <small class="text-muted">{{ ucwords(Auth::user()->roles[0]->name, '- ') }}</small>
                            </div>
                        </li>
                        <li class="dropdown-item">
                            <a class="d-flex w-100" href="{{ route('profile.edit') }}">
                                <i class="fe fe-user fs-18 me-2 text-primary"></i>Profile
                            </a>
                        </li>
{{--                        <li class="dropdown-item">--}}
{{--                            <a class="d-flex w-100" href="#">--}}
{{--                                <i class="fe fe-settings fs-18 me-2 text-primary"></i>Settings--}}
{{--                            </a>--}}
{{--                        </li>--}}
                        <li class="dropdown-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="d-flex w-100" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); this.closest('form').submit();"
                                >
                                    <i class="fe fe-info fs-18 me-2 text-primary"></i>Log Out
                                </a>
                            </form>
                        </li>
                    </ul>
                </div>
                <!-- End::header-element -->

            </div>
            <!-- End::header-content-right -->

        </div>
        <!-- End::main-header-container -->

    </header>
    <!-- /app-header -->
    <!-- Start::app-sidebar -->
    <aside class="app-sidebar sticky" id="sidebar">

        <!-- Start::main-sidebar-header -->
        <div class="main-sidebar-header">
            <a href="{{ route('dashboard') }}" class="header-logo">
                <img src="{{ asset('assets/images/brand-logos/logo.png') }}" alt="logo" class="desktop-logo">
                <img src="{{ asset('assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
            </a>
        </div>
        <!-- End::main-sidebar-header -->

        <!-- Start::main-sidebar -->
        <div class="main-sidebar" id="sidebar-scroll">

            <!-- Start::nav -->
            <nav class="main-menu-container nav nav-pills flex-column sub-open">
                <div class="slide-left" id="slide-left">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
                </div>
                <ul class="main-menu">

                    <!-- Main -->
                    <x-blocks.nav.item-category label="main" />
                    <x-blocks.nav.item-link label="Dashboard" route="dashboard" icon="home" />

                    <!-- User Management -->
                    <x-blocks.nav.item-category label="user management" :canAny="['patient.view', 'doctor.view']"/>
                    <x-blocks.nav.item-sub label="Patients" icon="users-round" :canAny="['patient.view']" active-when="patients.*">
                        <x-blocks.nav.link label="List" route="patients.index" :canAny="['patient.view']"/>
                    </x-blocks.nav.item-sub>
                    <x-blocks.nav.item-sub label="Doctors" icon="stethoscope" :canAny="['doctor.view']" active-when="doctors.*">
                        <x-blocks.nav.link label="List" route="doctors.index" :canAny="['doctor.view']"/>
                    </x-blocks.nav.item-sub>

                    <!-- Scheduling -->
                    <x-blocks.nav.item-category label="scheduling" :canAny="['schedule.view', 'consulting_room.view']"/>
                    <x-blocks.nav.item-sub label="Doctor Schedules" icon="calendar-clock" :canAny="['schedule.view']" active-when="schedules.*">
                        <x-blocks.nav.link label="List" route="schedules.index" :canAny="['schedule.view']"/>
                    </x-blocks.nav.item-sub>
                    <x-blocks.nav.item-sub label="Consulting Room" icon="hospital" :canAny="['consulting_room.view']" active-when="consulting-rooms.*">
                        <x-blocks.nav.link label="List" route="consulting-rooms.index" :canAny="['consulting_room.view']"/>
                    </x-blocks.nav.item-sub>

                    <!-- Appointment -->
                    <x-blocks.nav.item-category label="appointment" :canAny="['appointment.view']"/>
                    <x-blocks.nav.item-sub label="Appointments" icon="calendar-plus" :canAny="['appointment.view']" active-when="appointments.*">
                        <x-blocks.nav.link label="List" route="appointments.index" :canAny="['appointment.view']"/>
                    </x-blocks.nav.item-sub>

                    <!-- Clinical -->
                    <x-blocks.nav.item-category label="clinical" :canAny="['specialty.view']"/>
                    <x-blocks.nav.item-sub label="Specialties" icon="bookmark" :canAny="['specialty.view']" active-when="specialties.*">
                        <x-blocks.nav.link label="List" route="specialties.index" :canAny="['specialty.view']"/>
                    </x-blocks.nav.item-sub>

                    <!-- System -->
                    <x-blocks.nav.item-category label="System" :canAny="['user.view', 'role.view']"/>
                    <x-blocks.nav.item-link label="Roles & Permissions" route="roles.index" icon="shield-check" :canAny="['role.view']"/>
                    <x-blocks.nav.item-sub label="Admin Users" icon="shield-user" :canAny="['user.view']" active-when="users.*">
                        <x-blocks.nav.link label="List" route="users.index" :canAny="['user.view']"/>
                    </x-blocks.nav.item-sub>

                    <!-- Configuration -->
                    <x-blocks.nav.item-category label="Configuration" :canAny="['document_type.view']"/>
                    <x-blocks.nav.item-link label="Document Types" route="document-types.index" icon="id-card" :canAny="['document_type.view']"/>
                </ul>
                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
            </nav>
            <!-- End::nav -->

        </div>
        <!-- End::main-sidebar -->

    </aside>
    <!-- End::app-sidebar -->

    {{ $slot }}

    <!-- Footer Start -->
    <x-section.footer/>
    <!-- Footer End -->

</div>

<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

<!-- Scroll To Top -->
<div class="scrollToTop">
    <span class="arrow"><i class="ri-arrow-up-s-fill fs-20"></i></span>
</div>
<div id="responsive-overlay"></div>
<!-- Scroll To Top -->

<!-- Popper JS -->
<script src="{{ asset('assets/libs/popper/core/umd/popper.min.js') }}"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Defaultmenu JS -->
<script src="{{ asset('assets/js/defaultmenu.min.js') }}"></script>

<!-- Node Waves JS-->
{{--<script src="../assets/libs/node-waves/waves.min.js"></script>--}}

<!-- Sticky JS -->
<script src="{{ asset('assets/js/sticky.js') }}"></script>

<!-- Simplebar JS -->
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.js') }}"></script>

@stack('scripts')

<script src="https://unpkg.com/lucide@latest"></script>
<script>lucide.createIcons()</script>

<!-- Color Picker JS -->
{{--<script src="../assets/libs/@simonwep/pickr/pickr.es5.min.js"></script>--}}



<!-- JSVector Maps JS -->
{{--<script src="../assets/libs/jsvectormap/js/jsvectormap.min.js"></script>--}}

<!-- JSVector Maps MapsJS -->
{{--<script src="../assets/libs/jsvectormap/maps/world-merc.js"></script>--}}

<!-- Apex Charts JS -->
{{--<script src="../assets/libs/apexcharts/apexcharts.min.js"></script>--}}

<!-- Chartjs Chart JS -->
{{--<script src="../assets/libs/chart.js/chart.min.js"></script>--}}

<!-- index -->
{{--<script src="../assets/js/index.js"></script>--}}


<!-- Custom-Switcher JS -->
{{--<script src="../assets/js/custom-switcher.min.js"></script>--}}

<!-- Custom JS -->
{{--<script src="../assets/js/custom.js"></script>--}}

@stack('custom-scripts')

</body>

</html>
