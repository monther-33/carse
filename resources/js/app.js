import './bootstrap';

// Self-hosted Arabic font: the app must work on an offline office network.
import '@fontsource/ibm-plex-sans-arabic/400.css';
import '@fontsource/ibm-plex-sans-arabic/500.css';
import '@fontsource/ibm-plex-sans-arabic/600.css';
import '@fontsource/ibm-plex-sans-arabic/700.css';

import './searchable-select';
import './page-loader';

// An action sent from a page left open past the session lifetime (419): reload the page instead of
// Livewire's "page expired" confirm (easy to miss, and silent in the installed app). The server
// then shows the sign-in page if the session is gone.
document.addEventListener('livewire:init', () => {
    window.Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (status === 419) {
                preventDefault();
                window.location.reload();
            }
        });
    });
});
