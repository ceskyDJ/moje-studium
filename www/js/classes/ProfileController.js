class ProfileController
{
    constructor(menuController)
    {
        this.menuController = menuController;

        this.quotaProgressBar = document.querySelector("#_quota-progress-bar");

        this.loadProgressBar();
        this.saveMenuHeight();

        this.handleSubpageChange();
        this.handleProfilePageChange();
        this.handleOpenCloseColorPickerWithButton();
        this.handleCloseColorPicker();
        this.handleProfileIconMouseOver();
        this.handleOpenCloseClassProfileMenu();
    }

    loadProgressBar()
    {
        const progressBar = new ProgressBar.Circle(`#${this.quotaProgressBar.id}`, {
            strokeWidth: 6,
            easing: "easeInOut",
            duration: 1400,
            color: "#F3D452",
            trailColor: "#EEE9E5",
            trailWidth: 6,
            svgStyle: null
        });

        progressBar.animate(parseInt(this.quotaProgressBar.dataset.value) / 100);
    }

    saveMenuHeight()
    {
        const classProfile = document.querySelector("._profile-page[data-page='class-profile']");

        classProfile.style.visibility = "hidden";
        classProfile.classList.remove("hide");

        this.menuController.saveMenuHeight(document.querySelector("#_class-profile-menu-container"))

        classProfile.classList.add("hide");
        classProfile.style.removeProperty("visibility");
    }

    handleSubpageChange()
    {
        document.querySelectorAll("._profile-menu-item").forEach(item => {
            item.addEventListener("click", this.changeSubpage);
        });
    }

    handleProfilePageChange()
    {
        document.querySelectorAll("._change-profile-page").forEach(item => {
            item.addEventListener("click", _ => this.changeProfilePage(item));
        });
    }

    handleOpenCloseColorPickerWithButton()
    {
        document.querySelectorAll("._open-color-picker").forEach(item => {
            item.addEventListener("click", _ => this.toggleColorPicker(item));
        });
    }

    handleCloseColorPicker()
    {
        window.addEventListener("click", event => {
            const item = event.target;

            if(item.classList.contains("_color-picker")) {
                return;
            }

            if(item.closest("._color-picker") !== null) {
                return;
            }

            if(item.classList.contains("_open-color-picker")) {
                return;
            }

            this.closeColorPicker();
        });
    }

    handleProfileIconMouseOver()
    {
        document.querySelectorAll("._profile-icon").forEach(item => {
            item.addEventListener("mouseenter", _ => this.activateProfileIconHover(item));
            item.addEventListener("mouseleave", _ => this.deactivateProfileIconHover(item));
        });
    }

    handleOpenCloseClassProfileMenu()
    {
        document.querySelector("#_class-profile-menu-control-b").addEventListener("click", _ => {
            this.menuController.openCloseMenu(document.querySelector("#_class-profile-menu-container"));
        });
    }

    changeSubpage(event)
    {
        const clickedMenuItem = event.target.closest("._profile-menu-item");
        const subpageId = clickedMenuItem.dataset.for;

        document.querySelectorAll("._profile-menu-item").forEach(item => {
            if(item !== clickedMenuItem) {
                item.classList.remove("active");
            }
            else {
                item.classList.add("active");
            }
        });

        document.querySelectorAll("._profile-subpage").forEach(subpage => {
            if(subpage.dataset.id !== subpageId) {
                subpage.classList.add("hide");
            }
            else {
                subpage.classList.remove("hide");
            }
        });
    }

    changeProfilePage(button)
    {
        const activePage = button.dataset.for;

        document.querySelectorAll("._change-profile-page").forEach(item => {
            if(item.dataset.for !== activePage) {
                item.classList.remove("active");
            }
            else {
                item.classList.add("active");
            }
        });

        document.querySelectorAll("._profile-page").forEach(item => {
            if(item.dataset.page !== activePage) {
                item.classList.add("hide");
            }
            else {
                item.classList.remove("hide");
            }
        })
    }

    toggleColorPicker(button)
    {
        const id = button.dataset.for;

        document.querySelectorAll("._color-picker").forEach(item => {
            if(item.dataset.id !== id) {
                item.classList.add("hide");
            }
            else {
                if(item.classList.contains("hide")) {
                    item.classList.remove("hide");
                } else {
                    item.classList.add("hide");
                }
            }
        });
    }

    closeColorPicker()
    {
        document.querySelectorAll("._color-picker").forEach(item => {
            item.classList.add("hide");
        });
    }

    activateProfileIconHover(profileIcon)
    {
        // Does not make sense activate hover on selected icon
        if(profileIcon.classList.contains("active")) {
            return;
        }

        // Get styles from selected profile icon
        const selectedIcon = document.querySelector("._profile-icon.active");
        const color = selectedIcon.dataset.color;
        const background = selectedIcon.dataset.background;

        const icon = profileIcon.querySelector("._icon");

        icon.style.color = color;
        icon.style.backgroundColor = background;
    }

    deactivateProfileIconHover(profileIcon)
    {
        // Does not make sense deactivate hover on selected icon
        if(profileIcon.classList.contains("active")) {
            return;
        }

        const icon = profileIcon.querySelector("._icon");

        icon.style.removeProperty("color");
        icon.style.removeProperty("background-color");
    }
}