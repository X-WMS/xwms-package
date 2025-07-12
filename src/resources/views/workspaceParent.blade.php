<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="format-detection" content="telephone=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="author" content="TemplatesJungle">
        <meta name="keywords" content="ecommerce,fashion,store">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- plugins:css -->
        <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
        <!-- endinject -->


        <!-- Plugin css for this page -->
        @stack('title')
        <title>xwms</title>
        <!-- End plugin css for this page -->

        <!-- inject:css -->
        <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
        
        <link rel="stylesheet" href="{{ asset('assets/css/admin/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/custom/style.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/custom/workspace.css') }}">
        @vite('resources/core/scss/extended-bootstrap.scss')

        <link rel="stylesheet" href="{{ asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.css') }}">
        <!-- endinject -->

        <link rel="shortcut icon" href="{{ asset('assets/images/brand/logo.png') }}">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
        
        <link rel="stylesheet" href="{{ asset('assets/packages/loaders/loader.css') }}">
        @stack('styles')
    </head>

    <body>
        <div class="d-flex flex-row-reverse z-2 position-fixed col-12" style="pointer-events: none;">
            <div id="sliderContainer" class="z-2 col-12 position-relative p-3"></div>
        </div>

        @stack('includes')
        @include('core.partials.wazepopup.startloader', ['parent_id' => "startingLoader",'debug' => true])

        <div class="container-scroller z-1">

            @include('layouts.navbar')

            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
                @include('layouts.sidebar')
                <div class="main-panel">
                    @yield('content')
                    <footer class="footer">
                        <div class="d-sm-flex justify-content-center justify-content-sm-between">
                            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block col-12">Powered by <a href="https://xwms.nl/" target="_blank">XWMS</a></span>
                        </div>
                    </footer>
                </div>
            </div>
        </div>

        @vite('resources/core/js/request/request.js')
        @vite('resources/core/packages/slider/script.js')

        <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
        <script src="{{ asset('assets/vendors/jquery-toast-plugin/jquery.toast.min.js') }}"></script>
        <!-- endinject -->

        <!-- inject:js -->
        <script src="{{ asset('assets/js/admin/off-canvas.js') }}"></script>
        <script src="{{ asset('assets/js/admin/hoverable-collapse.js') }}"></script>
        <script src="{{ asset('assets/js/admin/misc.js') }}"></script>
        <script src="{{ asset('assets/js/admin/settings.js') }}"></script>
        <script src="{{ asset('assets/js/admin/todolist.js') }}"></script>
        <script src="{{ asset('assets/js/admin/jquery.cookie.js') }}"></script>

        <script src="{{ asset('assets/js/custom/script.js') }}"></script>
        <!-- endinject -->

        <!-- Custom js for this page -->
        <script src="{{ asset('assets/packages/loaders/loader.js') }}"></script>
        @stack('scripts')
        @stack('bottom')
        <!-- End custom js for this page -->
    </body>
</html>



@php
    $notifications = [];

    // 🔥 Check of er xwmsmessage in de URL zit
    if (request()->has('xwmsmessage_status') && request()->has('xwmsmessage_message')) {
        $notifications[] = [
            'status' => request('xwmsmessage_status'),
            'message' => urldecode(request('xwmsmessage_message'))
        ];
    }

    // Flash messages via session
    foreach (['success', 'error', 'info', 'warning', 'fatal'] as $type) {
        if (session($type)) {
            $notifications[] = [
                'status' => $type,
                'message' => session($type)
            ];
        }
    }

    // Laravel validation errors
    if ($errors->any()) {
        $notifications[] = [
            'status' => 'error',
            'message' => implode('<br>', $errors->all())
        ];
    }
@endphp

@if (!empty($notifications))
    <script>
        $(document).ready(function(){
            const flashNotifications = @json($notifications);
            flashNotifications.forEach(n => handle_notification(n));
        });
    </script>
@endif
