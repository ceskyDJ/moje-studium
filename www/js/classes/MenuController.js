class MenuController
{
    saveMenuHeight(menu)
    {
        menu.style.visibility = "hidden";
        menu.style.display = "initial";

        menu.dataset.height = menu.scrollHeight.toString();

        menu.style.removeProperty("display");
        menu.style.removeProperty("visibility")
    }

    openCloseMenu(menu)
    {
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
}