@props(['name', 'class' => '', 'minHeight' => null, 'label' => 'Iklan'])
@php
    $enabled = ($siteSettings[$name . '_enabled'] ?? '0') === '1';
    $html = $siteSettings[$name] ?? '';
@endphp
@if($enabled && filled($html))
    <aside class="{{ $class }} ad-slot" data-ad aria-label="{{ $label }}" @if($minHeight) style="min-height: {{ $minHeight }}" @endif>
        <p class="ad-label">{{ $label }}</p>
        <div class="max-w-full overflow-hidden">{!! $html !!}</div>
    </aside>
@endif
