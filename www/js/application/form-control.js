document.querySelectorAll("._select-input-buttons ._button").forEach(button => button.addEventListener("click", event => {
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
    } else if(buttonType === "down") {
        if(--selectedItemId < 0) {
            selectedItemId = dataItems.length - 1;
        }
    }

    // Change value
    input.value = dataItems.item(selectedItemId).dataset.value;
    input.dataset.selectedItem = selectedItemId.toString();
}));

// START icon chooser
const updateIconChooser = selectElement => {
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
};

if(document.querySelector(".icon-chooser") !== null) {
    document.addEventListener("DOMContentLoaded", _ => document.querySelectorAll(".icon-chooser").forEach(item => updateIconChooser(item)));
    document.querySelector(".icon-chooser").addEventListener("change", event => updateIconChooser(event.target));
}
// END icon chooser

// START calendar
const setupCalendar = (container, input) => {
    const datepicker = new Datepickk();

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

        input.value = day + " " + date.getDate() + ". " + (date.getMonth() + 1) + ".";
    };

    input.addEventListener("click", _ => datepicker.show());
    container.querySelector("._open-calendar").addEventListener("click", _ => datepicker.show());
};

document.querySelectorAll("._calendar-container").forEach(item => setupCalendar(item, item.querySelector("._calendar-input")));
// END calendar

// START class chooser
const chooseSchoolSelect = document.querySelector("#_choose-school-select");
if(chooseSchoolSelect !== null) {
    chooseSchoolSelect.addEventListener("change", event => {
        const chooseClassSelect = document.querySelector("#_choose-class-select");

        // Remove old options
        chooseClassSelect.querySelectorAll("._added-option").forEach(item => {
            chooseClassSelect.removeChild(item);
        });

        // Download options for class chooser
        axios.get("/application/class/get-classes/" + chooseSchoolSelect.value)
            .then(response => {
                response.data.forEach(item => {
                    const option = document.createElement("option");
                    option.text = item.displayName;
                    option.setAttribute("value", item.id);
                    option.classList.add("_added-option");

                    chooseClassSelect.appendChild(option);
                });
            })
            .catch(error => {

            })
            .then(_ => {
                // Change text in class chooser
                chooseClassSelect.querySelectorAll("option").forEach(item => {
                    item.removeAttribute("selected");
                });

                chooseClassSelect.querySelector("#_choose-class-option").setAttribute("selected", "selected");
            });
    });
}
// END class chooser