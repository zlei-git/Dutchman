@props(['status'])

@php
    $classes = match(strtoupper($status)) {
        'PENDING' => 'badge-warning',
        'CONFIRMED' => 'badge-primary',
        'PROCESSING' => 'badge-accent',
        'READY', 'SHIPPED', 'ARRIVED' => 'badge-info',
        'COMPLETED', 'PAID' => 'badge-success',
        'CANCELLED', 'FAILED', 'NO_SHOW' => 'badge-danger',
        default => 'badge-muted'
    };
@endphp

<span class="badge {{ $classes }}">
    {{ str_replace('_', ' ', strtoupper($status)) }}
</span>
