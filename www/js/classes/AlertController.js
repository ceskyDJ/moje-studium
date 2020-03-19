class AlertController
{
    constructor()
    {
        this.container = document.querySelector("#_alert-container");

        this.disableBasicFormSubmit();

        this.handleCloseByClickOnContainer();
    }

    disableBasicFormSubmit()
    {
        document.querySelectorAll("._alert-form").forEach(form => {
            form.addEventListener("submit", event => event.preventDefault(), true);
        });
    }

    handleCloseByClickOnContainer()
    {
        this.container.addEventListener("click", _ => {
            const alertId = this.container.dataset.activeAlert;

            this.hideAlert(alertId);
        });
    }

    showAlert(id)
    {
        const alert = document.querySelector(`._alert[data-id="${id}"]`);
        const externalContainer = alert.closest("._alert-external-container");

        this.container.dataset.activeAlert = id;

        this.showAlertBackground();
        alert.classList.remove("hide");
        externalContainer.classList.remove("hide");
    }

    hideAlert(id)
    {
        const alert = document.querySelector(`._alert[data-id="${id}"]`);
        const externalContainer = alert.closest("._alert-external-container");

        this.hideAlertBackground();
        alert.classList.add("hide");
        externalContainer.classList.add("hide");
    }

    showAlertBackground()
    {
        this.container.classList.remove("hide");
    }

    hideAlertBackground()
    {
        this.container.classList.add("hide");
    }
}