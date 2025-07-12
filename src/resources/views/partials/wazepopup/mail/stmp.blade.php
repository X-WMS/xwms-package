@include('core.partials.wazepopup.setup1')

<div class="col-12 h-100 d-flex flex-column">
    <div class="col-12 h-100 d-flex flex-column align-items-center justify-content-around overflow-hidden">
        <div class="px-0 col-12" style="max-width: 800px;">
            <div class="col-12 position-relative d-flex flex-column p-3 px-0 rounded bg-black bg-gradient pt-5">
                <div onclick="closeLoader('stmpModal')" class="position-absolute end-0 top-0 p-2 closeConnectOptions">
                    <span class="mdi mdi-close fs-3"></span>
                </div>

                <div class="col-12 d-flex flex-column position-relative">
                    <div class="d-flex flex-column px-3">
                        <h2>Manually connect your SMTP account</h2>
                        <p class="text-muted">
                            Fill in your SMTP details below to securely connect your email account with MailFi.  
                            If you're unsure about any setting, check our 
                            <a href="/help/mail-setup" class="text-primary fw-bold">setup guide</a>.
                        </p>
                    </div>

                    <form class="forms-sample px-3 pb-5 stmp-form position-relative" method="POST" action="{{ route('mailfi.setting.create') }}">@csrf

                        <div class="col-12 progressContent z-1" data-id="mailSettings" data-progress="1">

                            <label class="mb-2" for="name">Give this setting an name</label>
                            <div class="col-12">
                                <div class="form-group d-flex flex-column">
                                    <input type="text" class="form-control mt-3 stmp-data" name="name" placeholder="Enter your setting's name (e.g., my stmp 1)">
                                </div>
                            </div>

                            <h5 class="my-4 fs-4">Stmp Settings</h5>

                
                            <label class="mb-2" for="server">Email Provider</label>
                            <br>
                            <small class="form-text text-muted">
                                Select your email provider from the list. If your provider is not listed, choose "My provider not in list" and enter the SMTP server manually.
                            </small>
                            <div class="pb-3"></div>

                            @php
                                $emailProviders = [
                                    ['value' => "", 'name' => "My provider not in list"],
                                    ['value' => "smtp.gmail.com", 'name' => "Gmail (smtp.gmail.com)"],
                                    ['value' => "smtp.office365.com", 'name' => "Office 365 (smtp.office365.com)"],
                                    ['value' => "smtp-mail.outlook.com", 'name' => "Outlook (smtp-mail.outlook.com)"],
                                    ['value' => "smtp.yahoo.com", 'name' => "Yahoo Mail (smtp.yahoo.com)"],
                                    ['value' => "smtp.zoho.com", 'name' => "Zoho Mail (smtp.zoho.com)"],
                                    ['value' => "smtp.aol.com", 'name' => "AOL Mail (smtp.aol.com)"],
                                    ['value' => "smtp.yandex.com", 'name' => "Yandex (smtp.yandex.com)"],
                                    ['value' => "smtp.protonmail.com", 'name' => "ProtonMail (smtp.protonmail.com)"],
                                    ['value' => "smtp.gmx.com", 'name' => "GMX (smtp.gmx.com)"],
                                    ['value' => "smtp.ionos.com", 'name' => "IONOS (smtp.ionos.com)"],
                                    ['value' => "smtp.sendgrid.net", 'name' => "SendGrid (smtp.sendgrid.net)"],
                                    ['value' => "smtp.mailgun.org", 'name' => "Mailgun (smtp.mailgun.org)"],
                                    ['value' => "smtp.mandrillapp.com", 'name' => "Mandrill (smtp.mandrillapp.com)"],
                                    ['value' => "smtp.postmarkapp.com", 'name' => "Postmark (smtp.postmarkapp.com)"],
                                    ['value' => "smtp.fastmail.com", 'name' => "FastMail (smtp.fastmail.com)"],
                                    ['value' => "smtp.mail.com", 'name' => "Mail.com (smtp.mail.com)"],
                                ];
                            
                                $defaultEmailProvider = [
                                    'name' => "Gmail (smtp.gmail.com)",
                                    'value' => "smtp.gmail.com"
                                ];
                            @endphp
                        

                            <div class="col-12 form-input-select position-relative">
                                <div 
                                    class="col-12 form-select text-light py-0_8 form-input-sign rounded-0 modal-dropdown" 
                                    data-id="stmpprovider" 
                                    data-errortype="stmpprovider" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false"
                                >
                                    <p class="m-0 fw-normal">{{ $defaultEmailProvider['name'] }}</p>
                                </div>
                            
                                <ul class="dropdown-menu col-12 p-0 overflow-auto" style="max-height: 400px;" aria-labelledby="stmp-provider">
                                    @foreach ($emailProviders as $provider)
                                        <li 
                                            class="dropdown-option p-1 px-3 @if($provider['value'] === $defaultEmailProvider['value']) selected @endif" 
                                            data-value="{{ $provider['value'] }}"
                                        >
                                            {{ $provider['name'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            
                                <input id="stmp-provider" class="dynamic-select stmp-data" data-id="smtp-host" name="server" data-name="stmpprovider" type="hidden" value="{{ $defaultEmailProvider['value'] }}">
                            </div>
                            
                            <div class="manual-input-container col-12" data-id="smtp-host" style="display: none;">
                                <div class="form-group d-flex flex-column">
                                    <input type="text" class="form-control mt-3 stmp-data" name="server" placeholder="Enter your email provider's SMTP server (e.g., smtp.yourprovider.com)" value="smtp.gmail.com">
                                </div>
                            </div>
                            
                    
                            {{-- ------------------ --}}
                            <label class="mb-2 pt-5" for="email">Your SMTP Email *</label>
                            <br>
                            <small class="form-text text-muted">
                                This is the email address or username used to authenticate with your SMTP server. 
                                Make sure it's correctly entered to avoid sending issues.
                            </small>
                            <div class="form-group d-flex flex-column">
                                <input name="email" type="text" class="form-control mt-3 stmp-data" placeholder="Enter your SMTP username or email">
                            </div>

                            {{-- ------------------ --}}
                            <label class="mb-2" for="password">Your SMTP Password *</label>
                            <br>
                            <small class="form-text text-muted">
                                This is the password or app-specific password required for SMTP authentication. 
                                Some providers require you to generate an app password instead of using your actual password.
                            </small>
                            <div class="form-group d-flex flex-column">
                                <input name="password" type="password" class="form-control mt-3 stmp-data" placeholder="Enter your SMTP password">
                            </div>
                    
                            {{-- ----------------- --}}
                            <label class="mb-2" for="port">Port Number</label>
                            <br>
                            <small class="form-text text-muted">
                                The port number depends on your provider.
                            </small>
                            <div class="pb-3"></div>

                            @php
                                $smtpPorts = [
                                    ['value' => "", 'name' => "Custom Port"],
                                    ['value' => "587", 'name' => "587 (Recommended - STARTTLS)"],
                                    ['value' => "465", 'name' => "465 (SSL/TLS)"],
                                    ['value' => "2525", 'name' => "2525 (Alternative - STARTTLS)"],
                                    ['value' => "25", 'name' => "25 (Legacy - Server-to-Server, often blocked)"],
                                ];

                                // Stel standaard port in
                                $defaultPort = [
                                    'name' => "587 (Recommended - STARTTLS)",
                                    'value' => "587"
                                ];
                            @endphp

                            <div class="col-12 form-input-select position-relative">
                                <div 
                                    class="col-12 form-select text-light py-0_8 form-input-sign rounded-0 modal-dropdown" 
                                    data-id="stmport" 
                                    data-errortype="stmport" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false"
                                >
                                    <p class="m-0 fw-normal">{{ $defaultPort['name'] }}</p>
                                </div>
                            
                                <ul class="dropdown-menu col-12 p-0 overflow-auto" style="max-height: 400px;" aria-labelledby="stmp-port">
                                    @foreach ($smtpPorts as $port)
                                        <li 
                                            class="dropdown-option p-1 px-3 @if($port['value'] === $defaultPort['value']) selected @endif" 
                                            data-value="{{ $port['value'] }}"
                                        >
                                            {{ $port['name'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            
                                <input id="stmp-port" class="dynamic-select stmp-data" data-id="smtp-port" name="port" data-name="stmport" type="hidden" value="{{ $defaultPort['value'] }}">
                            </div>
                            
                            <div class="manual-input-container col-12" data-id="smtp-port" style="display: none;">
                                <div class="form-group d-flex flex-column">
                                    <input name="port" type="number" class="form-control mt-3 stmp-data" placeholder="Enter a custom SMTP port (e.g., 1038)" value="587">
                                </div>
                            </div>

                            {{-- ------------------ --}}
                            <label class="mb-2 pt-5" for="batch">Batch Limit *</label>
                            <br>
                            <small class="form-text text-muted">
                                Set the number of emails sent per batch to optimize SMTP performance and avoid rate limits.<br>
                            </small>
                            <div class="form-group d-flex flex-column">
                                <input name="batch" type="number" class="form-control mt-3 stmp-data" placeholder="e.g., 50 or 100" min="1" max="500">
                            </div>
                        </div>
                
                        <div class="col-12 progressContent z-1" data-id="mailSettings" data-progress="2">
                            <h5 class="my-4 fs-4">Security Options</h5>
                
                            {{-- ----------------- --}}
                            <label class="mb-2">Enable Authentication</label>
                            <br>
                            <small class="form-text text-muted">Use secure authentication for outgoing emails.</small>
                            <div class="form-check mt-3">
                                <div class="form-check-label">
                                    <label class="form-check-label text-muted">
                                        <input name="authentication" type="checkbox" class="form-check-input stmp-data" checked>
                                        Require Authentication
                                    </label>

                                    <label class="error mt-2 text-danger" for="authentication"></label>
                                </div>
                            </div>
                            <hr>
                    
                            <button type="button" class="btn btn-gradient-primary mt-3 addStmpData">Verify</button>
                        </div>
                    </form>

                    <div class="col-12 h-100 stmp_verify_screen position-absolute z-2 p-2 flex-column align-items-center justify-content-center">
                        <div class="d-flex flex-column">
                            <div class="flip-square-loader mx-auto"></div>
                            <p class="m-0 text-capitalize fs-6 stmp_verify_screen_title">verifying your data</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('core.partials.wazepopup.setup2')
