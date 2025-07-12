@include('core.partials.wazepopup.setup1')

<div class="col-12 h-100 d-flex flex-column">
    <div class="col-12 h-100 d-flex flex-column align-items-center justify-content-around overflow-hidden">
        
        <div class="col-12 d-flex flex-column align-items-center">
            <div class="col-md-4 col-sm-6 p-5 appLoaderParent rounded">
                <p id="pageLoaderTitle" class="m-0 p-2 col-12 text-center fs-3 text-uppercase fw-bold pt-3 pb-4"></p>
                <div class="border-0 p-0">
                    <div class="flip-square-loader mx-auto"></div>
                </div>

                <div class="d-flex flex-column py-4">
                    <p class="m-0 p-2 col-12 text-center text-capitalize">loading</p>
                    <div class="loader-progression-bar-parent col-12 rounded-pill overflow-hidden">
                        <div id="LoaderProgression" class="h-100 bg-success loader-progression-bar"></div>
                    </div>
                </div>

                <div class="d-flex flex-column mt-3">
                    <p class="text-success mb-3 text-capitalize">Tips:</p>
                    <div id="pageLoaderTips" class="d-flex flex-column"></div>
                </div>
            </div>
        </div>

    </div>
</div>

@include('core.partials.wazepopup.setup2')