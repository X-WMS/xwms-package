<div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
    <div class="card card-statistics">
        <div class="card-body pb-0">
            <p class="text-muted">{{ $title }}</p>
            <div class="d-flex align-items-center">
                <h4 class="font-weight-semibold">{{ number_format($total) }}</h4>
                <h6 class="{{ $growth['daily'] >= 0 ? 'text-success' : 'text-danger' }} font-weight-semibold ms-2">
                    {{ $growth['daily'] >= 0 ? '+' : '' }}{{ $growth['daily'] }}
                </h6>
            </div>
            <small class="text-muted">view history</small>
        </div>
        <canvas class="mt-2" height="40" id="statistics-graph-dark{{ $id }}"></canvas>
    </div>
</div>
