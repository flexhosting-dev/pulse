import './bootstrap.js';

// Import Bootstrap JS
import * as bootstrap from 'bootstrap';

// Import Chart.js
import Chart from 'chart.js/auto';

// Import styles (SCSS)
import './styles/app.scss';

// Make bootstrap and Chart available globally
window.bootstrap = bootstrap;
window.Chart = Chart;

// Initialize Bootstrap tooltips and popovers
document.addEventListener('turbo:load', () => {
    // Initialize tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));

    // Initialize popovers
    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
    popoverTriggerList.forEach(el => new bootstrap.Popover(el));
});
