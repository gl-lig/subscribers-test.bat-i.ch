@props(['type', 'compact' => false])

@php
    $s = $compact ? 'text-xs' : 'text-sm';
    $ico = $compact ? 'text-[11px]' : 'text-xs';
    $sp = $compact ? 'space-y-1.5' : 'space-y-2.5';
    $logoH = $compact ? 'h-3' : 'h-3.5';
@endphp

<ul class="{{ $sp }}">
    <li class="flex items-center gap-2.5 {{ $s }}">
        <i class="fa-regular fa-map {{ $ico }} w-4 text-center text-gray-400"></i>
        <span>{{ __('Parcelles') }}: <strong>{{ $type->parcelles_unlimited ? '∞' : $type->parcelles_count }}</strong></span>
    </li>

    <li class="flex items-center gap-2.5 {{ $s }}">
        <i class="fa-regular fa-bell {{ $ico }} w-4 text-center text-gray-400"></i>
        <span>{{ __('Alertes') }}: <strong>{{ $type->alertes_count }}</strong></span>
    </li>

    <li class="flex items-center gap-2.5 {{ $s }}">
        <i class="fa-regular fa-hard-drive {{ $ico }} w-4 text-center text-gray-400"></i>
        <span>{{ __('Stockage') }}: <strong>{{ $type->stockage_unlimited ? '∞' : $type->stockage_go . ' Go' }}</strong></span>
    </li>

    <li class="flex items-center gap-2.5 {{ $s }}">
        <i class="fa-regular fa-folder-open {{ $ico }} w-4 text-center {{ $type->cloud_externe ? 'text-gray-400' : 'text-gray-300' }}"></i>
        @if($type->cloud_externe)
        <span class="flex items-center gap-2">
            <span>{{ __('Cloud externe') }}</span>
            <span class="inline-flex items-center gap-1.5">
                {{-- kDrive --}}
                <svg class="{{ $logoH }}" viewBox="0 0 24 24" title="kDrive"><rect width="24" height="24" rx="5" fill="#0050FF"/><text x="12" y="17" text-anchor="middle" font-size="14" font-weight="bold" fill="white">k</text></svg>
                {{-- Google Drive --}}
                <svg class="{{ $logoH }}" viewBox="0 0 87.3 78" title="Google Drive"><path d="M6.6 66.85L3.3 61.35 29.1 17.1h26.5L6.6 66.85z" fill="#0066DA"/><path d="M29.2 78L2.9 78 29.1 17.1h26.5L29.2 78z" fill="#0066DA" opacity="0"/><path d="M55.6 17.1L84.4 66.85H31.2L57.5 17.1H55.6z" fill="#00AC47" opacity="0"/><path d="M29.1 17.1l26.5 0 28.2 49.75H57.3L29.1 17.1z" fill="#00AC47"/><path d="M84.4 66.85L57.3 66.85 29.2 78 55.6 78 84.4 66.85z" fill="#EA4335" opacity="0"/><path d="M57.3 66.85H3.3l25.9 11.15h56.2L57.3 66.85z" fill="#FFBA00" opacity="0"/><path d="M84.4 66.85H31.2L4.8 78h53.4l26.2-11.15z" fill="#00832D" opacity="0"/><path d="M84.4 66.85l-27.1 0-28.1-49.75h26.4l28.8 49.75z" fill="#00AC47" opacity="0"/><path d="M6.6 66.85l24.6 0L55.6 17.1H29.1L6.6 66.85z" fill="#0066DA" opacity="0"/><path d="M31.2 66.85l-24.6 0L29.1 17.1l26.5 0-24.4 49.75z" fill="#0066DA"/><path d="M55.6 17.1L29.1 17.1 6.6 66.85h24.6L55.6 17.1z" fill="#0066DA" opacity="0"/><path d="M55.6 17.1h-26.5L6.6 66.85l24.6 0L55.6 17.1z" fill="#0066DA" opacity="0"/><path d="M84.4 66.85H31.2L55.6 17.1h0L84.4 66.85z" fill="#00AC47"/><path d="M84.4 66.85H31.2l-2.0 11.15H85.3l-0.9-11.15z" fill="#FFBA00" opacity="0"/><path d="M31.2 66.85H84.4l2.0 11.15H29.2l2.0-11.15z" fill="#EA4335" opacity="0"/><path d="M31.2 66.85L6.6 66.85 29.2 78l2.0-11.15z" fill="#00832D" opacity="0"/><path d="M6.6 66.85H31.2L29.2 78 6.6 66.85z" fill="#2684FC"/><path d="M31.2 66.85l53.2 0L58.2 78H29.2l2.0-11.15z" fill="#FFBA00"/></svg>
                {{-- OneDrive --}}
                <svg class="{{ $logoH }}" viewBox="0 0 24 18" title="OneDrive"><path d="M14.22 4.5a5.9 5.9 0 00-4.6 2.17A4.46 4.46 0 007.1 6a4.52 4.52 0 00-4.26 3.06A3.58 3.58 0 000 12.54 3.46 3.46 0 003.46 16h5.27l6.36-6.36a4.84 4.84 0 01-.87-5.14z" fill="#0364B8"/><path d="M9.41 7.22a5.88 5.88 0 014.81-2.72 5.88 5.88 0 014.88 2.59A4.36 4.36 0 0124 11.26 4.36 4.36 0 0119.64 16H8.73l6.36-6.36a4.84 4.84 0 00-5.68-2.42z" fill="#0078D4"/><path d="M9.41 7.22l-.79.43a4.84 4.84 0 006.47 6.99L8.73 16H3.46A3.46 3.46 0 010 12.54a3.58 3.58 0 012.84-3.48A4.51 4.51 0 019.41 7.22z" fill="#1490DF"/><path d="M15.09 9.65a4.84 4.84 0 01-.47 5L8.73 16h10.91A4.36 4.36 0 0024 11.26a4.36 4.36 0 00-4.67-4.17 5.88 5.88 0 00-4.24 2.56z" fill="#28A8EA"/></svg>
                {{-- Dropbox --}}
                <svg class="{{ $logoH }}" viewBox="0 0 43.35 40.4" title="Dropbox"><path d="M12.87 0L0 8.1l8.8 7.06 12.87-8.4L12.87 0zM0 22.22l12.87 8.1 8.8-7.06-12.87-8.4L0 22.22zM21.67 23.26l8.8 7.06 12.88-8.1-8.81-7.06-12.87 8.1zM43.35 8.1L30.47 0l-8.8 7.06 12.87 8.1 8.81-7.06zM21.72 25.16l-8.85 6.98-4.02-2.63v2.95l12.87 7.73 12.87-7.73v-2.95l-4.02 2.63-8.85-6.98z" fill="#0061FF"/></svg>
                {{-- Proton Drive --}}
                <svg class="{{ $logoH }}" viewBox="0 0 24 24" title="Proton Drive"><path d="M3.78 3A2.78 2.78 0 001 5.78v12.44A2.78 2.78 0 003.78 21h16.44A2.78 2.78 0 0023 18.22V8.56a2.78 2.78 0 00-2.78-2.78h-8.06L10.5 3H3.78z" fill="#6D4AFF"/><path d="M11 11.5a2.5 2.5 0 015 0v.5h1v-.5a3.5 3.5 0 00-7 0v.5h1v-.5z" fill="white" opacity=".8"/><rect x="10" y="12" width="7" height="5" rx="1" fill="white"/></svg>
            </span>
        </span>
        @else
        <span class="text-gray-400">{{ __('Cloud externe') }}</span>
        @endif
    </li>

    <li class="flex items-center gap-2.5 {{ $s }}">
        <i class="fa-solid fa-shield-halved {{ $ico }} w-4 text-center {{ $type->lot_sauvegarde ? 'text-gray-400' : 'text-gray-300' }}"></i>
        <span class="{{ $type->lot_sauvegarde ? '' : 'text-gray-400' }}">{{ __('Lot de sauvegarde') }}</span>
    </li>

    @if($type->workspace_enabled)
    <li class="flex items-center gap-2.5 {{ $s }}">
        <i class="fa-regular fa-user-group {{ $ico }} w-4 text-center text-gray-400"></i>
        <span>{{ __('Workspace') }}: <strong>{{ $type->workspace_unlimited ? '∞' : $type->workspace_count }}</strong></span>
    </li>
    @else
    <li class="flex items-center gap-2.5 {{ $s }}">
        <i class="fa-regular fa-user-group {{ $ico }} w-4 text-center text-gray-300"></i>
        <span class="text-gray-400">{{ __('Workspace') }}</span>
    </li>
    @endif
</ul>
