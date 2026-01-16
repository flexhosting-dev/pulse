import { Controller } from '@hotwired/stimulus';

/**
 * Theme Controller
 * Handles dark/light theme toggle with localStorage persistence
 * and system preference detection.
 */
export default class extends Controller {
    static values = {
        storageKey: { type: String, default: 'pulse-theme' }
    }

    connect() {
        this.initializeTheme();
    }

    initializeTheme() {
        const savedTheme = localStorage.getItem(this.storageKeyValue);

        if (savedTheme) {
            this.setTheme(savedTheme);
        } else {
            // Check system preference
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            this.setTheme(prefersDark ? 'dark' : 'light');
        }

        // Listen for system preference changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem(this.storageKeyValue)) {
                this.setTheme(e.matches ? 'dark' : 'light');
            }
        });
    }

    toggle() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
        const newTheme = currentTheme === 'light' ? 'dark' : 'light';

        this.setTheme(newTheme);
        localStorage.setItem(this.storageKeyValue, newTheme);
    }

    setTheme(theme) {
        document.documentElement.setAttribute('data-bs-theme', theme);

        // Dispatch custom event for other components to react
        window.dispatchEvent(new CustomEvent('pulse:theme-changed', {
            detail: { theme }
        }));
    }

    getTheme() {
        return document.documentElement.getAttribute('data-bs-theme') || 'light';
    }
}
