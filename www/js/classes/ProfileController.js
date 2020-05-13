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
        this.handleProfileIconChange();
        this.handleColorInputChange();
        this.handleStartUserDataEditing();
        this.handleSaveUserData();
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

    handleProfileIconChange()
    {
        document.querySelectorAll("._profile-icon").forEach(item => {
            item.addEventListener("click", _ => this.changeProfileIcon(item));
        });
    }

    handleColorInputChange()
    {
        document.querySelectorAll("._color-input").forEach(item => {
            item.addEventListener("change", _ => {
                this.updateColorInputLabel(item);
                this.changeProfileImageColors(item);
            });
        });
    }

    handleStartUserDataEditing()
    {
        document.querySelectorAll("._edit-user-data").forEach(item => {
            item.addEventListener("click", _ => this.enableUserDataFormInput(item));
        });
    }

    handleSaveUserData()
    {
        document.querySelectorAll("._save-user-data").forEach(item => {
            item.addEventListener("click", _ => this.saveUserData());
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

    changeProfileIcon(iconContainer)
    {
        if(iconContainer.classList.contains("active")) {
            return;
        }

        const iconId = iconContainer.dataset.id;

        const params = new URLSearchParams();
        params.append("icon", iconId);

        axios.post(`/application/profiles/change-profile-image-icon`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const oldIconContainer = document.querySelector("._profile-icon.active");
                    const oldIconId = oldIconContainer.dataset.id;
                    const oldIcon = oldIconContainer.querySelector("._icon");
                    const newIcon = iconContainer.querySelector("._icon");

                    // Activate new icon
                    iconContainer.classList.add("active");
                    iconContainer.dataset.color = oldIconContainer.dataset.color;
                    iconContainer.dataset.background = oldIconContainer.dataset.background;

                    newIcon.style.color = oldIcon.style.color;
                    newIcon.style.backgroundColor = oldIcon.style.backgroundColor;

                    document.querySelector("#icon-" + iconId).value = document.querySelector("#icon-" + oldIconId).value;
                    document.querySelector("#background-" + iconId).value = document.querySelector("#background-" + oldIconId).value;

                    // Deactivate old icon
                    oldIconContainer.classList.remove("active");
                    delete oldIconContainer.dataset.color;
                    delete oldIconContainer.dataset.background;

                    oldIcon.style.removeProperty("color");
                    oldIcon.style.removeProperty("background-color");
                } else {
                    alert(data.message);
                }
            });
    }

    updateColorInputLabel(input)
    {
        const label = document.querySelector("._color-input-label[for='" + input.id + "']");

        label.querySelector("._color").style.backgroundColor = input.value;
    }

    changeProfileImageColors(input)
    {
        const iconId = input.closest("._color-picker").dataset.id;

        const iconColor = document.querySelector("#icon-" + iconId).value;
        const backgroundColor = document.querySelector("#background-" + iconId).value;

        const params = new URLSearchParams();
        params.append("icon-color", iconColor);
        params.append("background-color", backgroundColor);

        axios.post(`/application/profiles/change-profile-image-colors`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const iconContainer = input.closest("._profile-icon");
                    const icon = iconContainer.querySelector("._icon");

                    iconContainer.dataset.color = iconColor;
                    iconContainer.dataset.background = backgroundColor;

                    icon.style.color = iconColor;
                    icon.style.backgroundColor = backgroundColor;
                } else {
                    alert(data.message);
                }
            });
    }

    enableUserDataFormInput(button)
    {
        const input = document.querySelector("#" + button.dataset.for);

        input.removeAttribute("disabled");
        input.select();
        input.focus();

        button.classList.add("hide");
        button.closest("._input-container").querySelector("._save-user-data").classList.remove("hide");
    }

    saveUserData()
    {
        const firstName = document.querySelector("#_user-data-form-first-name").value;
        const lastName = document.querySelector("#_user-data-form-last-name").value;
        const nickname = document.querySelector("#_user-data-form-login-name").value;

        const params = new URLSearchParams();
        params.append("first-name", firstName);
        params.append("last-name", lastName);
        params.append("nickname", nickname);

        axios.post(`/application/profiles/change-user-data`, params)
            .then(response => {
                const data = response.data;
                const alertContainer = document.querySelector("#_user-data-form-alerts");

                const alert = document.createElement("p");
                alert.classList.add("form-alert");

                alertContainer.innerHTML = "";
                alertContainer.appendChild(alert);

                if(data.success === true) {
                    document.querySelectorAll("._save-user-data").forEach(item => item.classList.add("hide"));
                    document.querySelectorAll("._edit-user-data").forEach(item => item.classList.remove("hide"));

                    document.querySelector("#_user-data-form-first-name").setAttribute("disabled", "disabled");
                    document.querySelector("#_user-data-form-last-name").setAttribute("disabled", "disabled");
                    document.querySelector("#_user-data-form-login-name").setAttribute("disabled", "disabled");

                    alert.classList.add("positive")
                    alert.textContent = "Data byla úspěšně změněna";
                } else {
                    alert.classList.add("negative");
                    alert.textContent = data.message;
                }
            });
    }
}