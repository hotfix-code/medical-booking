<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" data-nav-layout="vertical" data-theme-mode="light" data-header-styles="light" data-menu-styles="light" data-toggled="close">

<head>

    <!-- Meta Data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Medical Booking – Admin &amp; Dashboard Template </title>
    <meta name="Description" content="">
    <meta name="Author" content="">
    <meta name="keywords" content="">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/brand-logos/favicon.ico') }}" type="image/x-icon">

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >

    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet" >
</head>

<body class="bg-white">

<!-- Loader -->
{{--<div id="loader" >--}}
{{--    <img src="../assets/images/media/loader.svg" alt="">--}}
{{--</div>--}}
<!-- Loader -->

<div class="row authentication coming-soon mx-0 justify-content-center">

    <div class="col-xxl-8 col-xl-8 col-lg-8 col-12">
        <div class="authentication-cover text-fixed-white">
            <div class="aunthentication-cover-content text-center py-5 px-sm-5 px-0">
                <div class="row justify-content-center align-items-center h-100">
                    <div class="col-xxl-6 col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12">
                        <h1 class="display-1 text-fixed-white">419</h1>
                        <div class="m-4">
                            <p class="fs-16">The session has expired️ ⚠️</p>
                        </div>
                        <div class="text-center">
                            <a class="btn btn-secondary d-inline-flex gap-1" href="{{ route('dashboard') }}"> <i class="ri-arrow-left-line my-auto "></i> Back to Home </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</body>

</html>
