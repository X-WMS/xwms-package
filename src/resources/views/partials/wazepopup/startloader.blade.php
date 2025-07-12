@include('core.partials.wazepopup.setup1')

<link rel="stylesheet" href="{{ asset('assets/css/custom/startLoader.css') }}">
<div class="col-12 h-100 d-flex flex-column">
    <div class="col-12 h-100 d-flex flex-column align-items-center justify-content-around overflow-hidden">
        <div class="pageLoader">
            <div id="particles-background" class="vertical-centered-box"></div>
            <div id="particles-foreground" class="vertical-centered-box"></div>
            
            <div class="vertical-centered-box">
                <div class="content">
                    <div class="loader-circle"></div>
                    <div class="loader-line-mask">
                        <div class="loader-line"></div>
                    </div>
                    <img src="{{ asset('assets/images/brand/logo.png') }}" class="startLoaderImg">
                </div>
            </div>
        </div>

        <div class="position-absolute bottom-0 col-12 d-flex flex-row justify-content-center">
            <p class="m-0 text-center text-uppercase mb-3 loaderPowerdByXwms">powerd by xwms</p>
        </div>

        <div class="position-absolute top-0 col-12">
            <div id="pageLoaderProgression" class="bg-gradient-primary"></div>
        </div>
    </div>
</div>

@if (Request::is("/"))
    <script src="{{ asset('assets/js/custom/startLoader2.js') }}"></script>
@else
    <script src="{{ asset('assets/js/custom/startLoader.js') }}"></script>
@endif

@include('core.partials.wazepopup.setup2')