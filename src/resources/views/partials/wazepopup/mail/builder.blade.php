@include('core.partials.wazepopup.setup1')

<div class="col-12 h-100 d-flex flex-column">
    <div class="col-12 h-100 d-flex flex-column overflow-hidden bg-dark-dark">
        <div class="d-flex flex-row justify-content-between align-items-center py-2 col-12 pe-5">
            <div class="d-flex flex-row align-items-center">
                <a href="#" class="ms-2"><img src="{{ asset('assets/images/brand/tld.png') }}" alt="logo" height="50" /></a>
                <h4 id="mailConfigName" class="m-0 text-muted">Untitled</h4>
            </div>
            <div class="d-flex flex-row align-items-center">
                <button id="mailConfigExit" type="button"
                    class="btn rounded-pill mx-2 border border-1 border-danger text-danger closeMailConfiguration">Cancel
                    and exit</button>
                <button id="mailConfigSave" type="button" class="btn rounded-pill mx-2 btn-gradient-success">Save and
                    exit</button>
            </div>
        </div>
        <hr class="m-0">
        <div class="d-flex flex-row col-12 webkit-fill">

            <div mc-slidebar-parent class="mc-option-parent h-100 overflow-hidden position-relative z-2">

                <div mc-slidebar data-slidebar="index" mc-active
                    class="mc-option-body col-12 h-100 overflow-auto flex-row z-1">
                    <div class="mc-option-icons-parent h-100 d-flex flex-column justify-content-between">
                        <div class="d-flex flex-column h-100">
                            <div mc-slidebar-toggler data-toggler-id="option-icons"
                                class="position-absolute mc-option-icon-element-active z-1"></div>
                            <div mc-option-selector data-toggler-id="option-icons" data-id="add"
                                class="mc-option-icon-element z-2 active">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="mdi mdi-plus-circle-outline icon"></span>
                                    <span class="title mt-1 text-capitalize">add</span>
                                </div>
                            </div>
                            <div mc-option-selector data-toggler-id="option-icons" data-id="style"
                                class="mc-option-icon-element z-2">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="mdi mdi-format-color-fill icon"></span>
                                    <span class="title mt-1 text-capitalize">style</span>
                                </div>
                            </div>

                            <div mc-help-selector class="mc-option-icon-element z-2 mt-auto">
                                <div class="d-flex flex-column align-items-center">
                                    <span class="mdi mdi-help icon"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mc-option-settings-parent h-100 d-flex flex-column z-2 p-3 px-4 overflow-auto">

                        <div mc-option-selected data-id="add" mc-active class="col-12 flex-column mc-option-selected">
                            <div class="mc-option-heading-options position-relative mb-5">
                                <div class="d-flex flex-row align-items-center col-12">
                                    <h1 mc-card-selector mc-active data-id="content" data-toggler-id="heading-options"
                                        class="m-0 text-capitalize col-6 text-center fs-5 py-3 mc-option-heading-option active">
                                        Content</h1>
                                    <h1 mc-card-selector data-id="layouts" data-toggler-id="heading-options"
                                        class="m-0 text-capitalize col-6 text-center fs-5 py-3 mc-option-heading-option">
                                        Layouts</h1>
                                </div>
                                <div mc-option-toggler data-toggler-id="heading-options"
                                    class="position-absolute col-6 mc-option-heading-bar bottom-0 bg-gradient-primary rounded-top">
                                </div>
                                <hr class="m-0">
                            </div>

                            <div class="mc-option-card-parent position-relative col-12">
                                <div mc-card-selected mc-active data-id="content" class="mc-option-card flex-column">
                                    <p class="m-0 text-uppercase">blocks</p>
                                    <p class="m-0">Drag to add content to your email</p>

                                    <div class="col-12 py-4">
                                        <div class="d-flex flex-wrap">

                                            @php
                                            $basicLayouts = [
                                                    ['id' => 1,'label' => '<span class="mdi mdi-format-text fs-3"></span>','title' => 'heading', 'type' => "heading"],
                                                ];
                                            @endphp

                                            @foreach ($basicLayouts as $Layout)
                                            <div mc-card-option data-id="customLayout{{ $Layout['id'] }}" draggable="true" data-type="{{ $Layout['type'] }}" data-outer="off" class="mc-option-card-option d-flex flex-column align-items-center justify-content-center rounded">
                                                <div class="d-flex flex-column align-items-center justify-content-between">
                                                    <div class="d-flex flex-column align-items-center">
                                                        {!! $Layout['label'] !!}
                                                        <span>{{ $Layout['title'] }}</span>
                                                    </div>
                                                    <span class="mdi mdi-drag-horizontal fs-5 text-muted mt-2"></span>
                                                </div>
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>

                                <div mc-card-selected data-id="layouts" class="mc-option-card flex-column">
                                    <p class="m-0 text-uppercase">Blank layouts</p>
                                    <p class="m-0">Drag to add content to your email</p>

                                    <div class="col-12 py-4">
                                        <div class="d-flex flex-wrap">

                                            @php
                                            $blankLayouts = [
                                                    ['id' => 1,'svg' => 'assets/packages/mailconfig/images/layouts/grid1.svg','title' => '1', 'type' => "drop"],
                                                    ['id' => 2,'svg' => 'assets/packages/mailconfig/images/layouts/grid2.svg','title' => '2', 'type' => "drop"],
                                                    ['id' => 3,'svg' => 'assets/packages/mailconfig/images/layouts/grid3.svg','title' => '2', 'type' => "drop"],
                                                    ['id' => 4,'svg' => 'assets/packages/mailconfig/images/layouts/grid4.svg','title' => '2', 'type' => "drop"],
                                                    ['id' => 5,'svg' => 'assets/packages/mailconfig/images/layouts/grid5.svg','title' => '2', 'type' => "drop"],
                                                    ['id' => 6,'svg' => 'assets/packages/mailconfig/images/layouts/grid6.svg','title' => '2', 'type' => "drop"],
                                                    ['id' => 7,'svg' => 'assets/packages/mailconfig/images/layouts/grid7.svg','title' => '3', 'type' => "drop"],
                                                    ['id' => 8,'svg' => 'assets/packages/mailconfig/images/layouts/grid8.svg','title' => '4', 'type' => "drop"],
                                                ];
                                            @endphp

                                            @foreach ($blankLayouts as $Layout)
                                            <div mc-card-option data-id="blankLayout{{ $Layout['id'] }}" draggable="true" data-type="{{ $Layout['type'] }}" data-outer="on" class="mc-option-card-option d-flex flex-column align-items-center justify-content-center rounded">
                                                <div
                                                    class="d-flex flex-column align-items-center justify-content-between">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <img src="{{ $Layout['svg'] }}" class="col-12 text-light">
                                                        <span>{{ $Layout['title'] }}</span>
                                                    </div>
                                                    <span class="mdi mdi-drag-horizontal fs-5 text-muted mt-2"></span>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div mc-option-selected data-id="style" class="col-12 flex-column mc-option-selected">
                            <p>test styles</p>
                        </div>

                    </div>
                </div>

                <div mc-slidebar data-slidebar="element"
                    class="mc-option-body col-12 h-100 overflow-auto flex-column z-1">
                    <div class="d-flex flex-row align-items-center p-3 py-3 mb-3">
                        <p class="m-0 p-2 px-3 rounded-pill bg-gradient-primary position-absolute left-0"><span
                                class="mdi mdi-chevron-left"></span><span>done</span></p>
                        <h1 class="col text-center text-capitalize fs-4 m-0">text</h1>
                    </div>

                    <div class="mc-option-heading-options position-relative mb-5">
                        <div class="d-flex flex-row align-items-center col-12">
                            <h1 class="m-0 text-capitalize col-4 text-center fs-5 py-3 mc-option-heading-option active">
                                Content</h1>
                            <h1 class="m-0 text-capitalize col-4 text-center fs-5 py-3 mc-option-heading-option">Styles
                            </h1>
                            <h1 class="m-0 text-capitalize col-4 text-center fs-5 py-3 mc-option-heading-option">
                                Visibility</h1>
                        </div>
                        <div
                            class="position-absolute col-4 mc-option-heading-bar bottom-0 bg-gradient-primary rounded-top">
                        </div>
                        <hr class="m-0">
                    </div>

                    <div class="mc-option-card-parent position-relative col-12">
                        <div class="mc-option-card flex-column px-4 active">
                            <p class="m-0 text-uppercase">All Devices</p>
                            <p class="m-0">Colors</p>
                            <div
                                class="p-3 d-flex flex-row align-items-center justify-content-between position-relative selected-color-button mt-3">
                                <div class="d-flex flex-row align-items-center"><span>Block Background</span></div>
                                <div class="d-flex flex-row align-items-center">
                                    <div class="selected-color-display rounded-circle"></div>
                                    <span class="mdi mdi-chevron-right fs-5 ms-2"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div mc-slidebar data-slidebar="color"
                    class="mc-option-body col-12 h-100 overflow-auto flex-column z-1">
                    <div class="d-flex flex-row align-items-center p-3 py-4">
                        <p class="m-0 p-2 px-3 rounded-pill bg-gradient-primary position-absolute left-0"><span
                                class="mdi mdi-chevron-left"></span><span>done</span></p>
                        <h1 class="col text-center text-capitalize fs-5 m-0">Block Background</h1>
                    </div>

                    <hr class="m-0">

                    <div class="position-relative col-12 px-4 py-3">
                        <p class="m-0">In this email</p>
                        <div class="d-flex flex-wrap">
                            <div
                                class="selected-color-display-selector rounded-circle p-1 m-1 d-flex position-relative">
                                <div
                                    class="col-12 h-100 rounded-circle d-flex flex-row align-items-center justify-content-center">
                                    <span class="mdi mdi-plus"></span>
                                </div>
                            </div>
                            <div
                                class="selected-color-display-selector rounded-circle p-1 m-1 d-flex position-relative">
                                <div class="col-12 h-100 rounded-circle child"></div>
                            </div>
                        </div>
                    </div>

                    <hr class="m-0">

                    <div class="position-relative col-12 px-4 py-3">
                        <p class="m-0">Default</p>
                        <div class="d-flex flex-wrap">
                            <div
                                class="selected-color-display-selector rounded-circle p-1 m-1 d-flex position-relative">
                                <div class="col-12 h-100 rounded-circle child"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>





            <div class="mc-body-parent col h-100 d-flex flex-column overflow-hidden position-relative">

                <div class="p-3 col-12 z-1 mc-body-options z-2"></div>

                <div class="mc-editor-parent col-12 d-flex flex-column align-items-center z-0">

                    <div mail-editor-parent class="mc-editor-background mail-template-body col-12 d-flex flex-column align-items-center p-5">
                        <div mail-editor-body class="col-12 rounded overflow-hidden" style="max-width: 660px;background-color: #f4f4f4;">

                            <table mc-table data-mc-table-id="default" role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tbody>
                                    <tr>
                                        <td mc-td style="position: relative;" id="firstTd" data-mc-table-id="default">
                                            <div class="mc-table-body z-1" data-mc-table-id="default">
                                                <table class="mc-table-child" width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr class="mc-tr">
                                                        <td class="mc-td" data-type="drop" data-mc-table-id="default" data-id="11" style="width: 100%; background: #cde; text-align: center;">
                                                            <div class="emailDropZone">
                                                                <div class="position-absolute h-100 w-100 emailDropZoneBlinker z-1"></div>
                                                                <span class="z-2">Drop content here</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@include('core.partials.wazepopup.setup2')