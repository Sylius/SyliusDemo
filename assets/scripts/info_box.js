class DemoInfoBox {
    constructor() {
        this.isOpen = false;
        this.versionSelect = document.getElementById('version-select');
        this.infoBox = document.getElementById('info-box');
        this.infoToggle = document.getElementById('info-toggle');

        if (window.sessionStorage.getItem('sylius:infoBox') !== 'close') {
            this.toggleVisibility();
        }

        this.infoToggle.addEventListener('click', this.toggleVisibility.bind(this));
        this.versionSelect.addEventListener('change', e => window.location.href = e.target.value);
    }

    toggleVisibility() {
        this.infoBox.classList.toggle('show');
        this.infoToggle.classList.toggle('show');
        this.isOpen = !this.isOpen;
        this.saveVisibility();
    }

    saveVisibility() {
        const state = this.isOpen ? 'open' : 'close';
        window.sessionStorage.setItem('sylius:infoBox', state);
    }
}

const demoInfoBox = new DemoInfoBox();
