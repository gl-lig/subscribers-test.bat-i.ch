import Alpine from 'alpinejs';
import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.css';

window.Alpine = Alpine;

// Alpine component for intl-tel-input phone field
Alpine.data('phoneInput', () => ({
    iti: null,
    init() {
        const input = this.$refs.phoneField;
        if (!input) return;

        this.iti = intlTelInput(input, {
            initialCountry: 'ch',
            preferredCountries: ['ch', 'fr', 'de', 'it', 'at'],
            separateDialCode: true,
            formatOnDisplay: true,
            nationalMode: false,
            utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.0.0/build/js/utils.js',
            geoIpLookup: function(callback) {
                fetch('https://ipapi.co/json/')
                    .then(res => res.json())
                    .then(data => callback(data.country_code))
                    .catch(() => callback('ch'));
            },
        });

        // Sync full E.164 number to Livewire on change
        input.addEventListener('countrychange', () => {
            this.syncNumber();
        });
        input.addEventListener('input', () => {
            this.syncNumber();
        });
    },
    syncNumber() {
        if (this.iti) {
            const fullNumber = this.iti.getNumber();
            if (fullNumber) {
                this.$wire.set('phone', fullNumber);
            }
        }
    },
    submitPhone() {
        this.syncNumber();
        this.$wire.verifyPhone();
    }
}));

Alpine.start();
