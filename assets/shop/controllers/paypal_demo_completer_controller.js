import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        url: String,
        csrf: String,
        delay: { type: Number, default: 5000 },
    };

    static targets = ['spinner', 'spinnerProcessing', 'spinnerError'];

    connect() {
        this.isCompleting = false;
    }

    complete(event) {
        event.preventDefault();

        if (this.isCompleting || !this.hasSpinnerTarget) {
            return;
        }

        this.isCompleting = true;
        event.currentTarget.disabled = true;
        this.spinnerTarget.classList.remove('d-none');

        window.setTimeout(() => this.submit(), this.delayValue);
    }

    reload(event) {
        event.preventDefault();
        window.location.reload();
    }

    async submit() {
        try {
            const response = await fetch(this.urlValue, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-Token': this.csrfValue,
                },
            });

            if (!response.ok) {
                this.showError();
                return;
            }

            const data = await response.json();
            if (typeof data.redirect_url !== 'string') {
                this.showError();
                return;
            }

            window.location.href = data.redirect_url;
        } catch (_error) {
            this.showError();
        }
    }

    showError() {
        if (this.hasSpinnerProcessingTarget) {
            this.spinnerProcessingTarget.classList.add('d-none');
        }

        if (this.hasSpinnerErrorTarget) {
            this.spinnerErrorTarget.classList.remove('d-none');
        }
    }
}
