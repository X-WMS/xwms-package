<div class="col-md-4 grid-margin stretch-card">
    <div class="card aligner-wrapper">
        <div class="card-body">
            <div class="absolute left top bottom h-100 v-strock-2 bg-{{ $backround }}"></div>
            <p class="text-muted mb-2">{{ $title }}</p>
            <div class="d-flex align-items-center">
                <h2 class="font-weight-medium mb-2">${{ number_format($data['total'], 2) }}</h2>
                <h5 class="font-weight-medium text-{{ $data['percentage_color'] }} ms-2">
                    {{ $data['percentage_change'] >= 0 ? '+' : '' }}{{ number_format($data['percentage_change'], 1) }}%
                </h5>
            </div>
            <div class="d-flex align-items-center">
                <div class="bg-{{ $backround }} dot-indicator"></div>
                <p class="text-muted mb-0 ms-2">
                    This month {{ strtolower($title) }} 
                    ${{ number_format($data['monthly_total'], 2) }}
                </p>
            </div>
        </div>
    </div>
</div>
