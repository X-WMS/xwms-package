@include('core.partials.wazepopup.setup1')

<div class="col-12 h-100 d-flex flex-column">
    <div class="col-12 h-100 d-flex flex-column overflow-hidden bg-dark-dark">
        <div class="container p-5 my-auto rounded shadow-lg overflow-auto">

            <div class="d-flex flex-row justify-content-between align-items-center mb-5">
                <div>
                    <p class="m-0 mail_recipients_title">untitled</p>
                </div>
                <div class="d-flex flex-row align-items-center gap-3">
                    <button class="btn btn-danger mail_recipients_cancel">exit</button>
                    <button class="btn btn-info mail_recipients_option_button mail_recipients_update">update</button>
                    <button class="btn btn-success mail_recipients_option_button mail_recipients_create">create</button>
                </div>
            </div>

            <input type="email" placeholder="email" class="mb-5 mail_recipients_email">

            <form class="form-inline repeater mail_recipients_replacers">
                <div data-repeater-list="group-a">

                    <div class="d-none">
                        <div data-repeater-item data-replacer-id="formailer" class="col-12 d-flex flex-row align-items-center justify-content-between gap-3 mb-3">
                            <div class="col-3">
                                <div class="form-group m-0">
                                    <input type="text" class="form-control mail_recipient_key" name="name" placeholder="fill in the recipient key">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group m-0">
                                    <input type="text" class="form-control mail_recipient_value" name="value" placeholder="fill in the recipient value">
                                </div>
                            </div>
                            <div class="col-auto h-100">
                                <button type="button" data-repeater-delete class="btn btn-gradient-danger btn-icon d-flex flex-column align-items-center justify-content-center">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-column mailR_content">
                        <div data-repeater-item data-replacer-id="formailer" class="col-12 d-flex flex-row align-items-center justify-content-between gap-3 mb-3">
                            <div class="col-3">
                                <div class="form-group m-0">
                                    <input type="text" class="form-control mail_recipient_key" name="name" placeholder="fill in the recipient key">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group m-0">
                                    <input type="text" class="form-control mail_recipient_value" name="value" placeholder="fill in the recipient value">
                                </div>
                            </div>
                            <div class="col-auto h-100">
                                <button type="button" data-repeater-delete class="btn btn-gradient-danger btn-icon d-flex flex-column align-items-center justify-content-center">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" data-repeater-create class="btn btn-gradient-info btn-sm icon-btn ms-2 mb-2" data-id="">
                    <i class="mdi mdi-plus"></i> Add
                </button>
            </form>
        </div>
    </div>
</div>

@include('core.partials.wazepopup.setup2')