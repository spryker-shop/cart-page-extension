import Component from 'ShopUi/models/component';

export default class PackagingUnitQuantitySelector extends Component {
    qtyInSalesUnitInput: HTMLInputElement;
    qtyInBaseUnitInput: HTMLInputElement;
    measurementUnitInput: HTMLSelectElement;
    addToCartButton: HTMLButtonElement;
    leadSalesUnitSelect: HTMLSelectElement;

    baseUnit: any;
    salesUnits: any;
    currentSalesUnit: any;
    productQuantityStorage: any;
    currentValue: Number;
    translations: any;
    leadSalesUnits: any;
    productPackagingUnitStorage: any;
    amountInSalesUnitInput: any;
    isAmountBlockEnabled: boolean;
    currentLeadSalesUnit: any;

    protected readyCallback(event?: Event): void {
        this.qtyInSalesUnitInput = <HTMLInputElement>document.querySelector('#sales-unit-quantity');
        this.qtyInBaseUnitInput = <HTMLInputElement>document.querySelector('#base-unit-quantity');
        this.measurementUnitInput = <HTMLSelectElement>document.querySelector('.select-measurement-unit');
        this.addToCartButton = <HTMLButtonElement>document.getElementById('add-to-cart-button');
        this.leadSalesUnitSelect = <HTMLSelectElement>document.querySelector('.select-lead-measurement-unit');
        this.amountInSalesUnitInput = <HTMLInputElement>document.querySelector('#default-amount');

        this.initJson();
        this.initTranslations();
        this.initCurrentSalesUnit();
        this.initCurrentLeadSalesUnit();
        this.mapEvents();
    }

    private initJson() {
        let jsonSchemaContainer = document.getElementsByClassName(this.name + '__json')[0];
        if (jsonSchemaContainer.hasAttribute('json')) {
            let jsonString = jsonSchemaContainer.getAttribute('json');
            let jsonData = JSON.parse(jsonString);

            console.log(jsonData);

            if (jsonData.hasOwnProperty('baseUnit')) {
                this.baseUnit = jsonData.baseUnit;
            }

            if (jsonData.hasOwnProperty('salesUnits')) {
                this.salesUnits = jsonData.salesUnits;
            }

            if(jsonData.hasOwnProperty('leadSalesUnits')) {
                this.leadSalesUnits = jsonData.leadSalesUnits;
            }

            if (jsonData.hasOwnProperty('isAmountBlockEnabled')) {
                this.isAmountBlockEnabled = jsonData.isAmountBlockEnabled;
            }

            if (jsonData.hasOwnProperty('productPackagingUnitStorage')) {
                this.productPackagingUnitStorage = jsonData.productPackagingUnitStorage;
            }

            if (jsonData.hasOwnProperty('productQuantityStorage')) {
                this.productQuantityStorage = jsonData.productQuantityStorage;
            }
        }
    }

    private initTranslations() {
        this.translations = JSON.parse(document.getElementById('measurement-unit-translation').innerHTML)
    }

    private initCurrentSalesUnit() {
        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {
                if (this.salesUnits[key].is_default) {
                    this.currentSalesUnit = this.salesUnits[key];
                }
            }
        }
    }

    private initCurrentLeadSalesUnit() {
        for (let key in this.leadSalesUnits) {
            if (this.leadSalesUnits.hasOwnProperty(key)) {
                if (this.leadSalesUnits[key].is_default) {
                    this.currentLeadSalesUnit = this.leadSalesUnits[key];
                }
            }
        }
    }

    private mapEvents() {
        this.qtyInSalesUnitInput.addEventListener('change', (event: Event) => this.qtyInputChange());
        this.measurementUnitInput.addEventListener('change', (event: Event) => this.measurementUnitInputChange(event));

        if(this.isAmountBlockEnabled) {
            this.amountInSalesUnitInput.addEventListener('input', (event: Event) => this.amountInputChange());
            this.leadSalesUnitSelect.addEventListener('change', (event: Event) => this.leadSalesUnitSelectChange(event));
        }
    }

    private qtyInputChange(qtyInSalesUnits?: number) {
        if (typeof qtyInSalesUnits === 'undefined') {
            qtyInSalesUnits = +this.qtyInSalesUnitInput.value;
        }
        let error = false;
        let qtyInBaseUnits = this.multiply(qtyInSalesUnits, +this.currentSalesUnit.conversion);
        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
            error = true;
            this.hideNotifications();
        } else if (qtyInBaseUnits < this.getMinQuantity()) {
            error = true;
            this.hideNotifications();
        } else if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            error = true;
            this.hideNotifications();
        }

        // if(this.isAmountBlockEnabled) {
        //     this.amountInputChange();
        // }

        if (error) {
            this.addToCartButton.setAttribute("disabled", "disabled");
            this.askCustomerForCorrectInput(qtyInSalesUnits);
            return;
        }
        this.qtyInBaseUnitInput.value = qtyInBaseUnits.toString();
        this.addToCartButton.removeAttribute("disabled");
        this.hideNotifications();
        return;
    }

    private hideNotifications() {
        document.querySelector('.measurement-unit-choice').classList.add('is-hidden');
        document.getElementById('quantity-between-units').classList.add('is-hidden');
        document.getElementById('minimum-quantity').classList.add('is-hidden');
        document.getElementById('maximum-quantity').classList.add('is-hidden');
    }

    private askCustomerForCorrectInput(qtyInSalesUnits: number) {
        let choicesList = document.querySelector('#measurement-unit-choices .list');
        let currentChoice = document.querySelector('.measurement-unit-choice #current-choice');
        let minChoice = this.getMinChoice(qtyInSalesUnits);
        let maxChoice = this.getMaxChoice(qtyInSalesUnits, minChoice);
        choicesList.innerHTML = '';
        currentChoice.innerHTML = '';
        currentChoice.textContent = `${this.round(qtyInSalesUnits, 4)} ${this.getUnitName(this.currentSalesUnit.product_measurement_unit.code)}`;

        let choiceElements = [];
        choiceElements.push(this.createChoiceElement(minChoice));
        if (maxChoice != minChoice) {
            choiceElements.push(this.createChoiceElement(maxChoice));
        }

        choiceElements.forEach((element) => (element !== null) ? choicesList.appendChild(element) : null);

        document.querySelector('.measurement-unit-choice').classList.remove('is-hidden');
    }

    private createChoiceElement(qtyInBaseUnits: number) {
        if (qtyInBaseUnits > 0) {
            let choiceElem = document.createElement('span');
            let qtyInSalesUnits = qtyInBaseUnits / this.currentSalesUnit.conversion;
            let measurementSalesUnitName = this.getUnitName(this.currentSalesUnit.product_measurement_unit.code);
            let measurementBaseUnitName = this.getUnitName(this.baseUnit.code);

            choiceElem.classList.add('link');
            choiceElem.setAttribute('data-base-unit-qty', qtyInBaseUnits.toString());
            choiceElem.setAttribute('data-sales-unit-qty', qtyInSalesUnits.toString());
            choiceElem.textContent = `(${this.round(qtyInSalesUnits, 4).toString().toString()} ${measurementSalesUnitName}) = (${qtyInBaseUnits} ${measurementBaseUnitName})`;
            choiceElem.onclick = function (event: Event) {
                let element = event.srcElement as HTMLSelectElement;
                let qtyInBaseUnits = parseFloat(element.dataset.baseUnitQty);
                let qtyInSalesUnits = parseFloat(element.dataset.salesUnitQty);
                this.selectQty(qtyInBaseUnits, qtyInSalesUnits);
            }.bind(this);

            choiceElem.style.display = 'block';

            return choiceElem;
        }

        return null;
    }

    private selectQty(qtyInBaseUnits: number, qtyInSalesUnits: number) {
        this.qtyInBaseUnitInput.value = qtyInBaseUnits.toString();
        this.qtyInSalesUnitInput.value = this.round(qtyInSalesUnits, 4).toString().toString();
        this.addToCartButton.removeAttribute("disabled");
        document.querySelector('.measurement-unit-choice').classList.add('is-hidden');
    }

    private getMinChoice(qtyInSalesUnits: number) {
        let qtyInBaseUnits = this.floor(this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion));

        if (qtyInBaseUnits < this.getMinQuantity()) {
            return this.getMinQuantity();
        }

        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0 || (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity())) {
            return this.getMinChoice((qtyInBaseUnits - 1) / this.currentSalesUnit.conversion)
        }

        return qtyInBaseUnits;
    }

    private getMaxChoice(qtyInSalesUnits: number, minChoice: number) {
        let qtyInBaseUnits = this.ceil(this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion));

        if (this.getMaxQuantity() > 0 && qtyInBaseUnits > this.getMaxQuantity()) {
            qtyInBaseUnits = this.getMaxQuantity();

            if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0) {
                qtyInBaseUnits = qtyInBaseUnits - ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval());
            }

            return qtyInBaseUnits;
        }

        if ((qtyInBaseUnits - this.getMinQuantity()) % this.getQuantityInterval() !== 0 || qtyInBaseUnits <= minChoice) {
            return this.getMaxChoice((qtyInBaseUnits + 1) / this.currentSalesUnit.conversion, minChoice)
        }

        return qtyInBaseUnits;
    }

    private floor(value: number): number {
        if (Math.floor(value) > 0) {
            return Math.floor(value);
        }

        return Math.ceil(value);
    }

    private ceil(value: number): number {
        return Math.ceil(value);
    }

    private round(value: number, decimals: number): number {
        return Number(Math.round(parseFloat(value + 'e' + decimals)) + 'e-' + decimals);
    }

    private multiply(a: number, b: number): number {
        let result = ((a * 10) * (b * 10)) / 100;
        return Math.floor(result * 1000) / 1000;
    }

    private getMinQuantity() {
        if (typeof this.productQuantityStorage !== 'undefined'
            && this.productQuantityStorage.hasOwnProperty('quantity_min')
        ) {
            return this.productQuantityStorage.quantity_min;
        }

        return 1;
    }

    private getMaxQuantity() {
        if (typeof this.productQuantityStorage !== 'undefined'
            && this.productQuantityStorage.hasOwnProperty('quantity_max')
            && this.productQuantityStorage.quantity_max !== null
        ) {
            return this.productQuantityStorage.quantity_max;
        }

        return 0;
    }

    private getQuantityInterval() {
        if (typeof this.productQuantityStorage !== 'undefined'
            && this.productQuantityStorage.hasOwnProperty('quantity_interval')
        ) {
            return this.productQuantityStorage.quantity_interval;
        }

        return 1;
    }

    private measurementUnitInputChange(event: Event) {
        let salesUnitId = parseInt((event.srcElement as HTMLSelectElement).value);
        let salesUnit = this.getSalesUnitById(salesUnitId);
        console.log(salesUnit);
        let qtyInSalesUnits = +this.qtyInSalesUnitInput.value;
        console.log(qtyInSalesUnits);
        let qtyInBaseUnits = this.multiply(qtyInSalesUnits, this.currentSalesUnit.conversion);
        console.log(this.currentSalesUnit);
        qtyInSalesUnits = qtyInBaseUnits / salesUnit.conversion;
        this.currentSalesUnit = salesUnit;
        this.qtyInSalesUnitInput.value = this.round(qtyInSalesUnits, 4).toString();
        this.qtyInputChange(qtyInSalesUnits);
    }

    private getSalesUnitById(salesUnitId: number) {
        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {
                if (salesUnitId == this.salesUnits[key].id_product_measurement_sales_unit) {
                    return this.salesUnits[key];
                }
            }
        }
    }

    private getBaseSalesUnit() {
        for (let key in this.salesUnits) {
            if (this.salesUnits.hasOwnProperty(key)) {
                if (this.baseUnit.id_product_measurement_unit == this.salesUnits[key].product_measurement_unit.id_product_measurement_unit) {
                    return this.salesUnits[key];
                }
            }
        }
    }

    private getUnitName(key) {
        if (this.translations.hasOwnProperty(key)) {
            return this.translations[key];
        }

        return key;
    }

    private amountInputChange(amountInSalesUnitInput?: number) {
        if (typeof amountInSalesUnitInput === 'undefined') {
            amountInSalesUnitInput = +this.amountInSalesUnitInput.value;
        }

        let error = false;
        let amountInBaseUnits = this.multiply(amountInSalesUnitInput, +this.currentLeadSalesUnit.conversion);
        this.hidePackagingUnitRestrictionNotifications();

        if((amountInBaseUnits - this.getMinAmount()) % this.getAmountInterval() !== 0) {
            error = true;
            document.getElementById('packaging-amount-interval').classList.remove('is-hidden');
        } else if (amountInBaseUnits < this.getMinAmount()) {
            error = true;
            document.getElementById('packaging-amount-min').classList.remove('is-hidden');
        } else if (this.getMaxAmount() > 0 && amountInBaseUnits > this.getMaxAmount()) {
            error = true;
            document.getElementById('packaging-amount-max').classList.remove('is-hidden');
        }

        // this.qtyInputChange();

        if(error) {
            document.querySelector('.packaging-unit-choice').classList.remove('is-hidden');
            this.addToCartButton.setAttribute("disabled", "disabled");
            return;
        }

        this.addToCartButton.removeAttribute("disabled");

        return;
    }

    private leadSalesUnitSelectChange(event: Event) {
        let salesUnitId = parseInt((event.srcElement as HTMLSelectElement).value);
        let salesUnit = this.getLeadSalesUnitById(salesUnitId);
        let amountInSalesUnits = +this.amountInSalesUnitInput.value;
        let amountInBaseUnits = this.multiply(amountInSalesUnits, this.currentLeadSalesUnit.conversion);
        amountInSalesUnits = amountInBaseUnits / salesUnit.conversion;
        this.currentLeadSalesUnit = salesUnit;
        this.amountInSalesUnitInput.value = this.round(amountInSalesUnits, 4).toString();
        this.amountInputChange(amountInSalesUnits);
    }

    private hidePackagingUnitRestrictionNotifications() {
        document.querySelector('.packaging-unit-choice').classList.add('is-hidden');
        document.getElementById('packaging-amount-min').classList.add('is-hidden');
        document.getElementById('packaging-amount-max').classList.add('is-hidden');
        document.getElementById('packaging-amount-interval').classList.add('is-hidden');
    }

    private getLeadSalesUnitById(salesUnitId: number) {
        for (let key in this.leadSalesUnits) {
            if (this.leadSalesUnits.hasOwnProperty(key)) {
                if (salesUnitId == this.leadSalesUnits[key].id_product_measurement_sales_unit) {
                    return this.leadSalesUnits[key];
                }
            }
        }
    }

    private getMinAmount() {
        if (typeof this.productPackagingUnitStorage !== 'undefined'
            && this.productPackagingUnitStorage.hasOwnProperty('amount_min')
            && this.productPackagingUnitStorage.amount_min !== null
        ) {
            return this.productPackagingUnitStorage.amount_min;
        }

        return 1;
    }

    private getMaxAmount() {
        if (typeof this.productPackagingUnitStorage !== 'undefined'
            && this.productPackagingUnitStorage.hasOwnProperty('amount_max')
            && this.productPackagingUnitStorage.amount_max !== null
        ) {
            return this.productPackagingUnitStorage.amount_max;
        }

        return 0;
    }

    private getAmountInterval() {
        if (typeof this.productPackagingUnitStorage !== 'undefined'
            && this.productPackagingUnitStorage.hasOwnProperty('amount_interval')
            && this.productPackagingUnitStorage.amount_interval !== null
        ) {
            return this.productPackagingUnitStorage.amount_interval;
        }

        return 1;
    }
}
