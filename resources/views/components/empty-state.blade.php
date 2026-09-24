@props(['title', 'description' => null, 'actionText' => null, 'actionUrl' => null])

<div class="empty-state">
    <div class="empty-state-icon">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><circle cx="12" cy="11" r="2"/></svg>
    </div>
    <h3 class="empty-state-title">{{ $title }}</h3>
    @if($description)
        <p class="empty-state-desc">{{ $description }}</p>
    @endif
    @if($actionText && $actionUrl)
        <a href="{{ $actionUrl }}" class="btn btn-primary">
            {{ $actionText }}
        </a>
    @endif
</div>
