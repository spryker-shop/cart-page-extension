import Component from '../../../models/component';

export default class FlashMessage extends Component {
    readonly defaultDuration: number = 5000
    durationTimeoutId: any

    readyCallback(): void {
        this.mapEvents();
        setTimeout(() => this.showFor(this.defaultDuration));
    }

    mapEvents(): void { 
        this.addEventListener('click', (event: Event) => this.onClick(event));
    }

    onClick(event: Event): void { 
        event.preventDefault();
        this.hide();
    }

    showFor(duration: number) {
        this.classList.add(`${this.componentName}--show`);
        this.durationTimeoutId = setTimeout(() => this.hide(), duration);
    }

    hide() { 
        clearTimeout(this.durationTimeoutId);
        this.classList.remove(`${this.componentName}--show`);
    }
}