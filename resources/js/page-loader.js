/*
 * Centered "loading" indicator (<x-page-loader>) while moving between pages.
 *
 * Pages change with wire:navigate: Livewire fires `livewire:navigate` when a link is followed and
 * `livewire:navigated` once the new page is in place. The indicator only appears if the change
 * takes longer than SHOW_AFTER, so fast moves (and pages restored from history) do not flash it.
 * Plain forms that leave the page (sign out) show it too.
 */
const SHOW_AFTER = 120;
let timer = null;

const loader = () => document.getElementById('page-loader');

function show() {
    clearTimeout(timer);
    timer = setTimeout(() => {
        const el = loader();
        if (el) {
            el.classList.add('is-visible');
            el.setAttribute('aria-hidden', 'false');
        }
    }, SHOW_AFTER);
}

function hide() {
    clearTimeout(timer);
    timer = null;
    const el = loader();
    if (el) {
        el.classList.remove('is-visible');
        el.setAttribute('aria-hidden', 'true');
    }
}

document.addEventListener('livewire:navigate', show);
document.addEventListener('livewire:navigated', hide);

// A full page load from a plain form (not a Livewire one), e.g. signing out.
document.addEventListener('submit', (event) => {
    const form = event.target;
    if (form instanceof HTMLFormElement && !event.defaultPrevented && !form.hasAttribute('wire:submit') && form.target !== '_blank') {
        show();
    }
});

// Coming back with the browser's back button from the page cache: never leave it showing.
window.addEventListener('pageshow', hide);
