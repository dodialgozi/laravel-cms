<div {{ $attributes->merge(['class' => 'progress animated-progress custom-progress progress-label']) }}>
    <div class="progress-bar bg-{{ $type }}" role="progressbar" style="width: {{ $value }}%" aria-valuenow="{{ $value }}" aria-valuemin="0" aria-valuemax="100"><div class="label">{{ $value }}%</div></div>
</div>