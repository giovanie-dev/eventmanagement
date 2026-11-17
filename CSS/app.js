document.addEventListener('DOMContentLoaded', () => {
    const main = document.querySelector('main');
    if (main && !main.id) main.id = 'main-content';
    const controls = document.querySelectorAll('[data-category]');
    const cards = document.querySelectorAll('[data-experience]');
    controls.forEach((control) =>
        control.addEventListener('click', () => {
            const category = control.dataset.category;
            controls.forEach((item) => {
                const active = item.dataset.category === category;
                item.classList.toggle('is-active', active);
                item.setAttribute('aria-pressed', String(active));
            });
            cards.forEach((card) => {
                card.hidden = category !== 'all' && card.dataset.experience !== category;
            });
            const count = document.querySelector('.collection-count');
            if (count) count.textContent = category === 'all' ? '03 univers' : '01 univers';
        }),
    );
    const search = document.querySelector('[data-live-search]');
    if (search) {
        const events = [...document.querySelectorAll('[data-event]')];
        const empty = document.querySelector('[data-no-results]');
        const filter = () => {
            const term = search.value
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim();
            let visible = 0;
            events.forEach((event) => {
                const content = event.textContent
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .toLowerCase();
                event.hidden = !content.includes(term);
                if (!event.hidden) visible++;
            });
            if (empty) empty.hidden = visible !== 0;
            const count = document.querySelector('[data-event-count]');
            if (count) count.textContent = `${visible} événement${visible > 1 ? 's' : ''}`;
        };
        search.addEventListener('input', filter);
        filter();
    }
});
