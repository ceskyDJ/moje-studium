document.querySelectorAll("._select-input-buttons ._button").forEach(button => button.addEventListener("click", event => {
    const buttonsContainer = button.parentElement;

    const input = document.querySelector("#" + buttonsContainer.dataset.for);
    const buttonType = button.dataset.type;
    const data = buttonsContainer.querySelector("._data");
    const dataItems = data.querySelectorAll("._item");
    let selectedItemId = input.dataset.selectedItem;

    // Pokud není vybrán žádný prvek, nastavit jako vybraný výchozí prvek
    if(selectedItemId === undefined) {
        const dataItemsArray = Array.prototype.slice.call(dataItems);
        input.dataset.selectedItem = selectedItemId = dataItemsArray.indexOf(data.querySelector("[data-default = true]")).toString();
    }

    // Posun indexu aktivního prvku
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

    // Změna hodnoty
    input.value = dataItems.item(selectedItemId).dataset.value;
    input.dataset.selectedItem = selectedItemId.toString();
}));