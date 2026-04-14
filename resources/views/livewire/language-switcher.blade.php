<div x-data="{ open: false }" class="relative" @click.away="open = false">
    <button @click="open = !open" class="flex items-center gap-1.5 rounded-lg px-3 py-1.5 text-sm font-medium text-white/80 transition hover:text-white">
        {{ strtoupper($currentLocale) }}
        <svg class="h-4 w-4 transition" :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
    </button>
    <div x-show="open" x-transition.origin.top.right x-cloak class="absolute right-0 mt-1 w-32 rounded-lg bg-white py-1 shadow-lg ring-1 ring-gray-200 z-50">
        @foreach(['fr' => 'Français', 'de' => 'Deutsch', 'it' => 'Italiano', 'en' => 'English'] as $code => $label)
        <a href="{{ route('locale.switch', $code) }}" class="flex w-full items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 {{ $currentLocale === $code ? 'font-semibold text-batid-bleu' : '' }}">
            {{ $label }}
        </a>
        @endforeach
    </div>
</div>
