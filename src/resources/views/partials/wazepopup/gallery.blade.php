@include('core.partials.wazepopup.setup1')

<div class="col-12 h-100 d-flex flex-column">
    <div class="col-12 h-100 d-flex flex-column overflow-hidden gallery-parent">

        <div class="position-relative col-12 p-3 mb-5">
            <div class="col-12 d-flex flex-row justify-content-between">
                <div class="d-flex flex-column">
                    <p class="text-capitalize fw-bold fs-3">{{ $galleryName }}</p>
                    <p class="text-capitalize fw-bold fs-5 text-muted gallery-selected" data-parent-id="{{ $parent_id }}"></p>
                </div>
                <div class="d-flex flex-row-reverse">
                    <p class="text-capitalize fw-bold fs-3 gallery-option" data-parent-id="{{ $parent_id }}" data-type="close"><span class="mdi mdi-close"></span></p>
                </div>
            </div>
        </div>

        <div class="position-relative col-12 d-flex flex-column align-items-center webkit-fill p-4">
            <img src="{{ $images[0]['url'] ?? "/assets/images/samples/300x300/12.jpg" }}" class="h-100 rounded gellery-display-image shadow-lg" data-parent-id="{{ $parent_id }}">
        </div>

        <div class="position-relative col-12 bg-black p-3 mt-5 gallery-bar-parent" data-parent-id="{{ $parent_id }}">
            <p class="position-absolute top-0 end-0 p-1 px-4 gallery-button rounded-top bg-black" data-parent-id="{{ $parent_id }}">
                <span class="mdi mdi-view-comfy"></span>
            </p>
            <div class="col-12 gallery-bar-body py-2 overflow-auto">
                <div class="form-group mb-4">
                    <label class="fw-bold">Search Templates</label>
                    <br>
                    <small class="text-muted">Need an specific template? you can search your template in here.</small>
                    <input name="name" type="text" class="form-control rounded-3 mt-3 gallery-sreach" placeholder="Search your template" data-parent-id="{{ $parent_id }}">
                </div>
                <div class="d-flex flex-wrap" style="height: 15vh;">
                    @foreach ($images as $index => $image)
                        <div class="h-100 m-1 gallery-image-container {{$index === 0 ? "active" : ""}}" data-id="{{ $image['id'] }}" data-name="{{ $image['name'] }}" data-parent-id="{{ $parent_id }}" data-url="{{ $image['url'] }}">
                            <img src="{{ $image['url'] }}" class="h-100 rounded gallery-image">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@include('core.partials.wazepopup.setup2')