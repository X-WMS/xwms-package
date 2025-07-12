<div class="col-12 grid-margin">
    <div class="card card-statistics">
        <div class="row">

        @foreach ($items['data'] as $item)
        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6 border-right">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                    <i class="{{ $item['icon'] }} me-0 me-sm-4 icon-lg"></i>
                    <div class="wrapper text-center text-sm-left">
                        <p class="card-text mb-0">{{ $item['title'] }}</p>
                        <div class="fluid-container">
                            <h3 class="mb-0 font-weight-medium">{{ $item['value'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach 
        </div>
    </div>
</div>