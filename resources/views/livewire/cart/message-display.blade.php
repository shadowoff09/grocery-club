<div>
    @if (!empty($messages['message']))
        <div class="mt-2 text-{{ $messages['type'] === 'warning' ? 'amber' : 'green' }}-600 text-sm {{ $messages['type'] === 'warning' ? 'text-center' : 'text-left' }}">
            {{ $messages['message'] }}
        </div>
    @endif
</div>
