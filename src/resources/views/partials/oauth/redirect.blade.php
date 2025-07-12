<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>XWMS Redirecting...</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/brand/logo.png') }}">

    @include('core.partials.oauth.rCss')
</head>

<body onload="" style="background-color: #0e0e0e; color: white;">

    <div id="XWMS_PAGE_LOADER">
        <div class="xwms-page-1">
            <div class="pageLoader">
                <div id="particles-background" class="vertical-centered-box"></div>
                <div id="particles-foreground" class="vertical-centered-box"></div>
                @include('core.partials.oauth.rJs')
                
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
    
            <div class="xwms-page-2">
                <p class="xwms-page-3 loaderPowerdByXwms">powerd by xwms</p>
            </div>
    
            <div class="xwms-page-4">
                <div id="pageLoaderProgression" class="xwms-page-4 bg-gradient-primary"></div>
            </div>
        </div>
    </div>

    <form action="{{ $redirectUri }}" method="POST">
        @csrf
        @if (isset($token))
            <input type="hidden" name="token" value="{{ $token }}">
        @endif
    </form>
</body>
</html>
