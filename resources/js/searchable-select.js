/*
 * Searchable selects, everywhere, without touching the markup.
 *
 * Any <select> with MIN_OPTIONS options or more (or data-searchable="on") opens a floating
 * panel with a search box instead of the native list. The <select> itself is left untouched
 * (Livewire morphs it freely): choosing an option sets select.value and fires "input" and
 * "change", so wire:model behaves exactly as with the native list.
 * Opt out with data-searchable="off".
 */

const MIN_OPTIONS = 8;

let panel = null;
let current = null;
let active = -1;

/** Arabic-aware normalisation: no diacritics/tatweel, unified alef / ya / ta marbuta, digits. */
function normalise(text) {
    return (text || '')
        .toLowerCase()
        .replace(/[ً-ْـ]/g, '')
        .replace(/[أإآٱ]/g, 'ا')
        .replace(/ى/g, 'ي')
        .replace(/ة/g, 'ه')
        .replace(/[٠-٩]/g, (d) => String('٠١٢٣٤٥٦٧٨٩'.indexOf(d)))
        .trim();
}

function isSearchable(select) {
    if (!(select instanceof HTMLSelectElement) || select.multiple || select.disabled || select.size > 1) {
        return false;
    }
    if (select.dataset.searchable === 'off') {
        return false;
    }
    return select.dataset.searchable === 'on' || select.options.length >= MIN_OPTIONS;
}

function buildPanel() {
    panel = document.createElement('div');
    panel.className = 'ss-panel';
    panel.setAttribute('role', 'listbox');
    panel.innerHTML = '<input type="search" class="ss-search" autocomplete="off"><ul class="ss-list"></ul><p class="ss-empty"></p>';
    document.body.appendChild(panel);

    const input = panel.querySelector('.ss-search');
    input.addEventListener('input', () => render(input.value));
    input.addEventListener('keydown', onKey);
    panel.addEventListener('mousedown', (e) => {
        const item = e.target.closest('.ss-item');
        if (item) {
            e.preventDefault();
            choose(item.dataset.index);
        }
    });
}

function options() {
    return Array.from(current.options).map((o, index) => ({
        index,
        text: o.text,
        group: o.parentElement instanceof HTMLOptGroupElement ? o.parentElement.label : '',
        disabled: o.disabled,
        hidden: o.hidden,
        selected: o.selected,
        key: normalise(o.text + ' ' + (o.parentElement instanceof HTMLOptGroupElement ? o.parentElement.label : '')),
    })).filter((o) => !o.hidden);
}

function render(query) {
    const terms = normalise(query).split(/\s+/).filter(Boolean);
    const list = panel.querySelector('.ss-list');
    const matches = options().filter((o) => terms.every((t) => o.key.includes(t)));

    list.innerHTML = '';
    let lastGroup = null;
    for (const o of matches) {
        if (o.group && o.group !== lastGroup) {
            const g = document.createElement('li');
            g.className = 'ss-group';
            g.textContent = o.group;
            list.appendChild(g);
            lastGroup = o.group;
        }
        const li = document.createElement('li');
        li.className = 'ss-item' + (o.selected ? ' ss-selected' : '') + (o.disabled ? ' ss-disabled' : '');
        li.dataset.index = o.index;
        li.textContent = o.text;
        list.appendChild(li);
    }

    panel.querySelector('.ss-empty').textContent = matches.length ? '' : (document.documentElement.lang === 'ar' ? 'لا توجد نتائج' : 'No results');
    const items = list.querySelectorAll('.ss-item:not(.ss-disabled)');
    active = Array.from(items).findIndex((li) => li.classList.contains('ss-selected'));
    if (active < 0 && items.length) {
        active = 0;
    }
    highlight();
}

function highlight() {
    const items = panel.querySelectorAll('.ss-item:not(.ss-disabled)');
    items.forEach((li, i) => li.classList.toggle('ss-active', i === active));
    items[active]?.scrollIntoView({ block: 'nearest' });
}

function onKey(e) {
    const items = panel.querySelectorAll('.ss-item:not(.ss-disabled)');
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        active = Math.min(active + 1, items.length - 1);
        highlight();
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        active = Math.max(active - 1, 0);
        highlight();
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (items[active]) {
            choose(items[active].dataset.index);
        }
    } else if (e.key === 'Escape' || e.key === 'Tab') {
        e.preventDefault();
        e.stopPropagation(); // do not close the modal behind
        close(true);
    }
}

function position() {
    if (!current || !panel) {
        return;
    }
    const r = current.getBoundingClientRect();
    const width = Math.max(r.width, 240);
    const below = window.innerHeight - r.bottom;
    panel.style.width = width + 'px';
    panel.style.left = Math.min(Math.max(8, document.dir === 'rtl' ? r.right - width : r.left), window.innerWidth - width - 8) + 'px';
    if (below < 280 && r.top > below) {
        panel.style.top = '';
        panel.style.bottom = (window.innerHeight - r.top + 4) + 'px';
    } else {
        panel.style.bottom = '';
        panel.style.top = (r.bottom + 4) + 'px';
    }
}

function open(select) {
    if (!panel) {
        buildPanel();
    }
    current = select;
    panel.style.display = 'block';
    position();
    const input = panel.querySelector('.ss-search');
    input.value = '';
    input.placeholder = document.documentElement.lang === 'ar' ? 'بحث...' : 'Search...';
    render('');
    input.focus();
}

function close(refocus = false) {
    if (panel) {
        panel.style.display = 'none';
    }
    if (refocus && current) {
        current.focus();
    }
    current = null;
}

function choose(index) {
    const select = current;
    const option = select.options[Number(index)];
    if (!option || option.disabled) {
        return;
    }
    close(true);
    if (select.selectedIndex !== option.index) {
        select.selectedIndex = option.index;
        select.dispatchEvent(new Event('input', { bubbles: true }));
        select.dispatchEvent(new Event('change', { bubbles: true }));
    }
}

document.addEventListener('mousedown', (e) => {
    if (panel && panel.style.display === 'block' && !panel.contains(e.target) && e.target !== current) {
        close();
    }
    const select = e.target instanceof HTMLSelectElement ? e.target : null;
    if (select && e.button === 0 && isSearchable(select)) {
        e.preventDefault();
        select.focus();
        current === select ? close() : open(select);
    }
}, true);

document.addEventListener('keydown', (e) => {
    const select = e.target;
    if (select instanceof HTMLSelectElement && isSearchable(select)
        && (e.key === 'Enter' || e.key === ' ' || (e.altKey && e.key === 'ArrowDown'))) {
        e.preventDefault();
        open(select);
    }
}, true);

window.addEventListener('resize', () => position());
document.addEventListener('scroll', (e) => {
    if (current && panel && !panel.contains(e.target)) {
        position();
    }
}, true);
document.addEventListener('livewire:navigating', () => close());
