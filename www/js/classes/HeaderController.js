class HeaderController
{
    constructor()
    {
        this.saveMenuHeight();

        this.handleShowNotifications();
        this.handleOpenCloseMenu();
    }

    handleShowNotifications()
    {
        document.querySelector("#_show-notifications-b").addEventListener("click", _ => this.showNotifications());
    }

    handleOpenCloseMenu()
    {
        document.querySelector("#_main-menu-control-b").addEventListener("click", _ => this.openCloseMenu());
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

    openCloseMenu()
    {
        const menu = document.querySelector("#_main-menu-container");
        let cycle;

        if(menu.classList.contains("mobile-hide")) {
            // Open
            menu.style.height = "0";
            menu.style.overflowY = "hidden";
            menu.classList.remove("mobile-hide");

            const openMenu = _ => {
                let actualHeight = parseInt(menu.style.height.replace("px", ""));
                menu.style.height = (actualHeight += 4).toString() + "px";

                if(actualHeight >= menu.dataset.height) {
                    clearInterval(cycle);
                }
            }

            cycle = setInterval(openMenu, 10);
        } else {
            // Close
            menu.style.height = menu.dataset.height + "px";
            menu.style.overflowY = "hidden";

            const closeMenu = _ => {
                let actualHeight = parseInt(menu.style.height.replace("px", ""));
                menu.style.height = (actualHeight -= 4).toString() + "px";

                if(actualHeight <= 0) {
                    clearInterval(cycle);
                    menu.classList.add("mobile-hide");
                }
            }

            cycle = setInterval(closeMenu, 10);
        }
    }

    saveMenuHeight()
    {
        const menu = document.querySelector("#_main-menu-container");

        menu.style.visibility = "hidden";
        menu.style.display = "initial";

        menu.dataset.height = menu.scrollHeight.toString();

        menu.style.removeProperty("display");
        menu.style.removeProperty("visibility")
    }
}