import { Controller } from '@hotwired/stimulus';

/**
 * Sidebar Controller
 * Handles mobile sidebar toggle and overlay
 */
export default class extends Controller {
    static targets = ['sidebar', 'overlay']

    connect() {
        // Close sidebar on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isOpen()) {
                this.close();
            }
        });
    }

    toggle() {
        if (this.isOpen()) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        this.sidebarTarget.classList.add('show');
        this.overlayTarget.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    close() {
        this.sidebarTarget.classList.remove('show');
        this.overlayTarget.classList.remove('show');
        document.body.style.overflow = '';
    }

    isOpen() {
        return this.sidebarTarget.classList.contains('show');
    }
}
