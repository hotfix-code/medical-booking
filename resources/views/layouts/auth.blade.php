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

    <!-- Main Theme Js -->
    <script src="{{ asset('assets/js/authentication-main.js') }}"></script>

    <!-- Bootstrap Css -->
    <link id="style" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" >

    <!-- Style Css -->
    <link href="{{ asset('assets/css/styles.min.css') }}" rel="stylesheet" >

    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" >

</head>

<body>

<!-- Loader -->
<div id="loader" >
    <img src="../assets/images/media/loader.svg" alt="">
</div>
<!-- Loader -->

{{ $slot }}

<!-- Bootstrap JS -->
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Show Password JS -->
<script src="{{ asset('assets/js/show-password.js') }}"></script>

</body>

</html>
