<div class="position-absolute h-100 w-100 loader_tussen_parent" style="z-index: 999999; display: none;">
    <div class="d-flex flex-column align-items-center justify-content-around col-12 h-100 position-relative" style="pointer-events: none;">
        <div class="h-100 w-100 position-absolute" style="background-color: rgba(0, 0, 0, 0.5);-webkit-backdrop-filter: blur(5px); backdrop-filter: blur(5px);">
            
        </div>
        <div class="col-12 d-flex flex-column align-items-center" style="z-index: 99999;">
            <p class="fs-3 text-center">This can take an while... <br> do NOT close this window. <br> {{$description}}</p>
            <img src="{{ asset('assets/packages/loaders/images/loader1.gif') }}" alt="loader" class="col-2">
        </div>
    </div>
</div>