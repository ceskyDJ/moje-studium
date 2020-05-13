class HeaderController
{
    constructor(menuController)
    {
        this.menuController = menuController;

        menuController.saveMenuHeight(document.querySelector("#_main-menu-container"));

        this.handleShowNotifications();
        this.handleOpenCloseMenu();
    }

    handleShowNotifications()
    {
        document.querySelector("#_show-notifications-b").addEventListener("click", _ => this.showNotifications());
    }

    handleOpenCloseMenu()
    {
        document.querySelector("#_main-menu-control-b").addEventListener("click", _ => {
            this.menuController.openCloseMenu(document.querySelector("#_main-menu-container"));
        });
    }

    showNotifications()
    {
        const box = document.querySelector("#_notifications-box");
        const button = event.target;

        if(box.dataset.open === "true") {
            // Close
            box.classList.remove("active");

            // Send a request to clear notifications (they've been already read)
            axios.head("/application/home/clear-notifications")
                .then(response => {
                    button.classList.remove("active");
                });

            box.dataset.open = "false";
        } else {
            // Open
            box.classList.add("active");

            box.dataset.open = "true";
        }
    }
}