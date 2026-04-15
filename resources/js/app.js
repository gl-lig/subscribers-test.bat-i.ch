import Alpine from 'alpinejs';

// Make Alpine available globally (Livewire 3 also uses it, but we need it on non-Livewire pages too)
if (! window.Alpine) {
    window.Alpine = Alpine;
    Alpine.start();
}
