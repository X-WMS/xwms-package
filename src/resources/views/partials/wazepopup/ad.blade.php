@include('core.partials.wazepopup.setup1')

<script>
    const url_start_session = "{{ route('ads.startSession') }}";
    const url_log_view = "{{ route('ads.logView') }}";
    const url_complete_ad = "{{ route('ads.completeSession') }}";
    const url_get_segments = "{{ route('ads.getSegments') }}";
</script>

<div class="col-12 h-100 d-flex flex-column">
    <div class="col-12 h-100 d-flex flex-column align-items-center justify-content-around overflow-hidden">
        
        <div class="col-12 d-flex flex-column align-items-center">
            <div class="ad-parent bg-black position-relative overflow-hidden d-flex flex-column align-items-center rounded col-6">
                <video id="adVideoDisply" class="hidden fullscreen-video">
                    <source src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <video class="d-none" id="adVideo" muted>
                    <source src="" type="video/mp4">
                        Your browser does not support the video tag.
                </video>

                <!-- Voortgangsbalk -->
                <div class="position-absolute col-11 ad-progression-bar-parent rounded-pill overflow-hidden">
                    <div class="ad-progression-bar bg-primary h-100"></div>
                </div>
            </div>

            <!-- Advertentie-omschrijving -->
            <div id="adVideoDescription" class="col-6 p-3 bg-black mt-3 rounded d-flex flex-column align-items-center">
                
            </div>
        </div>

    </div>
</div>



@include('core.partials.wazepopup.setup2')