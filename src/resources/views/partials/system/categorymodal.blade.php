<!-- Modal -->

<script>
    const modal_id = '{{ $id }}';
</script>
<script src="{{ asset('assets/js/custom/categoryModal.js') }}"></script>


<div class="modal fade modal-lg" id="{{ $id }}" tabindex="-1" role="dialog"
    aria-labelledby="{{ $id }}Title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Title">Category <span></span></h5>
                <button type="button" class="close modalClose">
                    <span aria-hidden="true" class="modalClose">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xl-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex flex-column align-items-center position-relative col-12">

                                    <form class="forms-sample pt-4 row col-12" method="POST"
                                        action="{{ $item['type'] === 'category' ? route('category.edit', ['id' => $item['id']]) : route('subcategory.edit', ['id' => $item['id']]) }}">

                                        @csrf

                                        <!-- Verborgen veld om de actie aan te geven -->
                                        <input type="hidden" name="action_type" id="action_type" value="update">

                                        <div class="form-group col-xl-12 blue-form">
                                            <label for="UserDataFrontname">Title</label>
                                            <input type="text" name="title" class="form-control"
                                                id="UserDataFrontname" placeholder="{{ $item['title'] }}">
                                        </div>

                                        @if ($item['type'] === 'category')
                                            <div class="form-group col-xl-12 blue-form">
                                                <label for="UserDataLastname">Description</label>
                                                <input name="description" type="text" class="form-control"
                                                    id="UserDataLastname" placeholder="{{ $item['description'] }}">
                                            </div>

                                            <div class="form-group col-xl-12 blue-form">
                                                <label for="UserDataLastname">Icon</label>
                                                <input name="icon" type="text" class="form-control"
                                                    id="UserDataLastname" placeholder="{{ $item['icon'] }}">
                                            </div>

                                            <div class="form-group">
                                                <label>Select parent categories for this subcategory</label>
                                                <select class="js-example-basic-multiple" name="categories[]"
                                                    multiple="multiple" style="width:100%">
                                                    @isset($item['cat_rows'])
                                                        @foreach ($item['cat_rows'] as $row)
                                                            <option {{ $row['selected'] ? 'selected' : '' }} value="{{ $row['id'] }}">{{ $row['title'] }}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                        @else
                                            <div class="form-group">
                                                <label>Select sub-categories for this category</label>
                                                <select class="js-example-basic-multiple" name="subcategories[]"
                                                    multiple="multiple" style="width:100%">
                                                    @isset($item['cat_rows'])
                                                     @foreach ($item['cat_rows'] as $row)
                                                            <option {{ $row['selected'] ? 'selected' : '' }} value="{{ $row['id'] }}">{{ $row['title'] }}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>

                                            <div class="col-md-12 grid-margin">
                                                <h5 class="card-subtitle">HTML CODE</h5>
                                                <textarea name="html" id='tinyMceExample'>{!! $item['html'] !!}</textarea>
                                            </div>
                                        @endif

                                        <div>
                                            <button type="submit" class="btn btn-gradient-primary me-2"
                                                onclick="setAction('update')"
                                                data-id="{{ $item['id'] }}">Save</button>
                                            <a href="/system/help" class="btn btn-dark modalClose me-2"
                                                data-id="{{ $item['id'] }}">Cancel</a>
                                            <button type="submit" class="btn btn-danger modalClose me-2"
                                                onclick="setAction('delete')"
                                                data-id="{{ $item['id'] }}">Delete</button>
                                            <a href="{{ $item['url'] }}" class="btn btn-success modalClose me-2"
                                            data-id="{{ $item['id'] }}">View Category</a>
                                        </div>
                                    </form>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="position-relative d-flex flex-wrap justify-content-between col-12">
                    <div class="d-flex flex-row align-self-center">
                        <span>id <span class="text-muted">-</span> <b>{{ $user['id'] }}</b></span>
                    </div>
                    <div class="d-flex flex-row align-self-center">
                        <span>updated at <span class="text-muted">-</span> <b>{{ $user['updated_at'] }}</b></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>