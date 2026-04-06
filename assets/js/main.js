document.addEventListener('DOMContentLoaded', () => {
    setupNavigation();
    setupSmoothAnchors();
    setupFaq();
    setupReviewSlider();
    setupToasts();
});

function setupNavigation() {
    const toggle = document.querySelector('[data-nav-toggle]');
    const nav = document.querySelector('[data-site-nav]');
    const actions = document.querySelector('.header-actions');

    if (!toggle || !nav || !actions) {
        return;
    }

    toggle.addEventListener('click', () => {
        const isOpen = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!isOpen));
        nav.classList.toggle('is-open', !isOpen);
        actions.classList.toggle('is-open', !isOpen);
        document.body.classList.toggle('nav-open', !isOpen);
    });
}

function setupSmoothAnchors() {
    document.querySelectorAll('a[href*="#"]').forEach((link) => {
        link.addEventListener('click', (event) => {
            const href = link.getAttribute('href');
            if (!href) {
                return;
            }

            const url = new URL(href, window.location.href);
            const samePage = url.pathname === window.location.pathname;
            if (!samePage || !url.hash) {
                return;
            }

            const target = document.querySelector(url.hash);
            if (!target) {
                return;
            }

            event.preventDefault();
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });
}

function setupFaq() {
    const items = Array.from(document.querySelectorAll('.faq-item'));
    if (items.length === 0) {
        return;
    }

    items.forEach((item) => {
        const trigger = item.querySelector('[data-faq-trigger]');
        if (!trigger) {
            return;
        }

        trigger.addEventListener('click', () => {
            const isOpen = item.classList.contains('is-open');

            items.forEach((entry) => {
                entry.classList.remove('is-open');
                const button = entry.querySelector('[data-faq-trigger]');
                if (button) {
                    button.setAttribute('aria-expanded', 'false');
                }
            });

            if (!isOpen) {
                item.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });
    });
}

function setupReviewSlider() {
    const slider = document.querySelector('[data-review-slider]');
    if (!slider) {
        return;
    }

    const slides = Array.from(slider.querySelectorAll('[data-review-slide]'));
    const prevButton = slider.querySelector('[data-review-nav="prev"]');
    const nextButton = slider.querySelector('[data-review-nav="next"]');

    if (slides.length <= 1 || !prevButton || !nextButton) {
        return;
    }

    let index = slides.findIndex((slide) => slide.classList.contains('is-active'));
    if (index < 0) {
        index = 0;
        slides[0].classList.add('is-active');
    }

    const showSlide = (nextIndex) => {
        slides[index].classList.remove('is-active');
        index = (nextIndex + slides.length) % slides.length;
        slides[index].classList.add('is-active');
    };

    prevButton.addEventListener('click', () => showSlide(index - 1));
    nextButton.addEventListener('click', () => showSlide(index + 1));

    window.setInterval(() => showSlide(index + 1), 6500);
}

function setupToasts() {
    const stack = document.getElementById('toast-stack');
    if (!stack) {
        return;
    }

    window.showToast = (message, type = 'info') => {
        const toast = document.createElement('div');
        toast.className = `toast toast--${type}`;
        toast.textContent = message;
        stack.appendChild(toast);

        window.setTimeout(() => {
            toast.remove();
        }, 3600);
    };

    const flashMessage = stack.dataset.flashMessage;
    if (flashMessage) {
        window.showToast(flashMessage, stack.dataset.flashType || 'info');
    }
}
