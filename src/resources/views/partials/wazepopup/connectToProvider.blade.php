@include('core.partials.wazepopup.setup1')

<link rel="stylesheet" href="{{ asset('assets/css/custom/providers.css') }}">
<div class="col-12 h-100 d-flex flex-column">
    <div class="col-12 h-100 d-flex flex-column align-items-center justify-content-around overflow-hidden">
        <div class="px-3" style="max-width: 800px;">
            <div class="position-relative d-flex flex-column p-3 rounded bg-black bg-gradient pt-5">
                <div onclick="closeLoader('providerModal')" class="position-absolute end-0 top-0 p-2 closeConnectOptions"><span class="mdi mdi-close fs-3"></span></div>

                <div class="col-12 d-flex flex-column">
                    <h2>Connect your email account securely.</h2>
                    <p class="text-muted">Choose a trusted provider to link your email with MailFi. This connection allows you to send and manage emails effortlessly — no setup required.</p>
    
                    <div class="col-12 d-flex flex-wrap">
                        <div class="col-12 col-xxl-6 p-2">
                            <a href="{{ route('oauth.redirect', ['google', 'mail']) }}" class="d-flex flex-row align-items-center p-2 px-4 rounded col-12 provider-item">
                                <img src="{{ asset('assets/images/auth/logoG.png') }}">
                                <h3 class="m-0 text-capitalize ms-4">google</h3>
                            </a>
                        </div>
                        <div class="col-12 col-xxl-6 p-2">
                            <a href="{{ route('oauth.redirect', ['microsoft', 'mail']) }}" class="d-flex flex-row align-items-center p-2 px-4 rounded col-12 provider-item">
                                <img src="{{ asset('assets/images/auth/logoM.png') }}">
                                <h3 class="m-0 text-capitalize ms-4">Microsoft</h3>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('core.partials.wazepopup.setup2')