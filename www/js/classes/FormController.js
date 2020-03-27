class FormController
{
    constructor()
    {
        this.datepickers = [];

        this.initSelectInput();
        this.initIconChooser();
        this.initCalendar();
    }

    initSelectInput()
    {
        if(document.querySelector(".select-input-buttons") === null) {
            return;
        }

        document.querySelectorAll("._select-input-buttons ._button")
            .forEach(button => button.addEventListener("click", _ => this.selectInputChangeValue(button)));
    }

    initIconChooser()
    {
        if(document.querySelector("._icon-chooser") === null) {
            return;
        }

        document.addEventListener("DOMContentLoaded", _ => document.querySelectorAll("._icon-chooser")
            .forEach(item => this.updateIconChooser(item)));
        document.querySelectorAll("._icon-chooser").forEach(item => {
            item.addEventListener("change", event => this.updateIconChooser(event.target));
        });
    }

    initCalendar()
    {
        document.querySelectorAll("._calendar-container")
            .forEach(item => this.setupCalendar(
                item,
                item.querySelector("._calendar-input-date"),
                item.querySelector("._calendar-input-year")
            ));
    }

    selectInputChangeValue(button)
    {
        const buttonsContainer = button.parentElement;

        const input = document.querySelector("#" + buttonsContainer.dataset.for);
        const buttonType = button.dataset.type;
        const data = buttonsContainer.querySelector("._data");
        const dataItems = data.querySelectorAll("._item");
        let selectedItemId = input.dataset.selectedItem;

        // If there isn't selected any item, set default one as selected
        if(selectedItemId === undefined) {
            const dataItemsArray = Array.prototype.slice.call(dataItems);
            input.dataset.selectedItem = selectedItemId = dataItemsArray.indexOf(data.querySelector("[data-default = true]")).toString();
        }

        // Move index of the active item
        selectedItemId = parseInt(selectedItemId);
        if(buttonType === "up") {
            if(++selectedItemId > dataItems.length - 1) {
                selectedItemId = 0;
            }
        }
        else if(buttonType === "down") {
            if(--selectedItemId < 0) {
                selectedItemId = dataItems.length - 1;
            }
        }

        // Change value
        input.value = dataItems.item(selectedItemId).dataset.value;
        input.dataset.selectedItem = selectedItemId.toString();
    }

    updateIconChooser(selectElement)
    {
        const selectedOption = selectElement.querySelector("[value='" + selectElement.value + "']");

        const colorPicker = document.createElement("div");
        colorPicker.setAttribute("class", selectElement.getAttribute("class"));
        colorPicker.style.display = "none";
        selectElement.append(colorPicker);

        const colorPickerFakeOption = document.createElement("span");
        colorPicker.setAttribute("class", selectedOption.getAttribute("class"));
        colorPicker.appendChild(colorPickerFakeOption);

        selectElement.style.color = window.getComputedStyle(colorPickerFakeOption).color;
        selectElement.removeChild(colorPicker);

        const parentOfSelect = selectElement.parentNode;
        parentOfSelect.removeChild(selectElement);
        parentOfSelect.appendChild(selectElement);
    }

    setupCalendar(container, inputDate, inputYear)
    {
        const datepicker = this.datepickers[container] = new Datepickk();

        const today = new Date();

        datepicker.maxSelections = 1;
        datepicker.closeOnSelect = true;
        datepicker.highlight = [today];
        datepicker.minDate = (new Date()).setDate(today.getDate() - 1);
        datepicker.lang = 'cz';
        datepicker.container = container.querySelector("._calendar");

        datepicker.onClose = _ => {
            const date = datepicker.selectedDates[0];

            // Only closed without selecting any value
            if(date === undefined) {
                return;
            }

            const days = ['ne', 'po', 'út', 'st', 'čt', 'pá', 'so'];
            let day = days[date.getDay()];
            day = day.charAt(0).toUpperCase() + day.slice(1);

            inputDate.value = day + " " + date.getDate() + ". " + (date.getMonth() + 1) + ".";
            inputYear.value = date.getFullYear();
        };

        inputDate.addEventListener("click", _ => datepicker.show());
        container.querySelector("._open-calendar").addEventListener("click", _ => datepicker.show());
    }

    setActiveDateInDatepicker(container, newDate)
    {
        this.datepickers[container].unselectAll();
        this.datepickers[container].selectDate(newDate);
    }

    setMinDateInDatepicker(container, minDate)
    {
        this.datepickers[container].minDate = (new Date()).setDate(minDate.getDate() - 1);
    }

    unselectValueInDatepicker(container)
    {
        this.datepickers[container].unselectAll();
    }
}