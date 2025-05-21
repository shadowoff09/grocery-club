@php $attributes = $unescapedForwardedAttributes ?? $attributes; @endphp

@props([
    'variant' => 'outline',
])

@php
$classes = Flux::classes('shrink-0')
    ->add(match($variant) {
        'outline' => '[:where(&)]:size-6',
        'solid' => '[:where(&)]:size-6',
        'mini' => '[:where(&)]:size-5',
        'micro' => '[:where(&)]:size-4',
    });
@endphp

{{-- Your SVG code here: --}}
<svg 
	{{ $attributes->class($classes) }} 
	data-flux-icon 
	aria-hidden="true"
	viewBox="0 0 24 24"
	fill="none"
	stroke="currentColor"
	stroke-width="1"
	stroke-linecap="round"
	stroke-linejoin="round"
>
	<path d="M18 21V10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1v11" />
	<path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V8a2 2 0 0 1 1.132-1.803l7.95-3.974a2 2 0 0 1 1.837 0l7.948 3.974A2 2 0 0 1 22 8z" />
	<path d="M6 13h12" />
	<path d="M6 17h12" />
</svg>