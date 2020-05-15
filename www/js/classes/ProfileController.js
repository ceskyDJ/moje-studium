class ProfileController
{
    constructor(menuController)
    {
        this.menuController = menuController;

        this.quotaProgressBar = document.querySelector("#_quota-progress-bar");

        this.loadProgressBar();

        this.handleProfilePageChange();
        this.handleOpenCloseColorPickerWithButton();
        this.handleCloseColorPicker();
        this.handleProfileIconMouseOver();
        this.handleProfileIconChange();
        this.handleColorInputChange();
        this.handleStartUserDataEditing();
        this.handleSaveUserData();

        // Only for user with class
        if(document.querySelector("#_user-has-class") === null) {
            return;
        }

        this.saveMenuHeight();

        this.handleSubpageChange();
        this.handleOpenCloseClassProfileMenu();
        this.handleProcessAccessRequest();
        this.handleLeaveClass();
        this.handleEditClassName();
        this.handleSaveClassName();
        this.handleOpenCreateGroupForm();
        this.handleCreateGroup();
        this.handleDeleteGroup();
        this.handleAddStudentToGroup();
        this.handleDeleteStudentFromGroup();
        this.handleOpenAddTaughtGroupForm();
        this.handleAddTaughtGroup();
        this.handleDeleteTaughtGroup();
        this.handleOpenAddClassroomForm();
        this.handleAddClassroom();
        this.handleDeleteClassroom();
        this.handleOpenAddTeacherForm();
        this.handleAddTeacher();
        this.handleDeleteTeacher();
        this.handleOpenAddSubjectForm();
        this.handleAddSubject();
        this.handleDeleteSubject();
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

    handleProcessAccessRequest()
    {
        document.querySelectorAll("._process-access-request").forEach(item => {
            item.addEventListener("click", _ => this.processAccessRequest(item));
        });
    }

    handleLeaveClass()
    {
        document.querySelector("#_leave-class").addEventListener("click", _ => this.leaveClass());
    }

    handleEditClassName()
    {
        document.querySelector("#_edit-class-name").addEventListener("click", _ => this.editClassName());
    }

    handleSaveClassName()
    {
        document.querySelector("#_save-class-name").addEventListener("click", _ => this.saveClassName());

        document.querySelector("#_edit-class-form").addEventListener("submit", event => {
            event.preventDefault();
            this.saveClassName();
        })
    }

    handleOpenCreateGroupForm()
    {
        const button = document.querySelector("#_open-create-group-form");

        button.addEventListener("click", _ => {
            this.openCreateGroupForm(button);
        });
    }

    handleCreateGroup()
    {
        document.querySelector("#_create-group").addEventListener("click", _ => this.createGroup());

        document.querySelector("#_create-group-form").addEventListener("submit", event => {
            event.preventDefault();

            this.createGroup();
        });
    }

    handleDeleteGroup(button)
    {
        if(button === undefined) {
            document.querySelectorAll("._delete-class-group").forEach(item => {
                item.addEventListener("click", _ => this.deleteGroup(item));
            });
        } else {
            button.addEventListener("click", _ => this.deleteGroup(button));
        }
    }

    handleAddStudentToGroup(select)
    {
        if(select === undefined) {
            document.querySelectorAll("._add-student-to-class-group").forEach(item => {
                item.addEventListener("change", _ => this.addStudentToGroup(item));
            });
        } else {
            select.addEventListener("change", _ => this.addStudentToGroup(select));
        }
    }

    handleDeleteStudentFromGroup(button)
    {
        if(button === undefined) {
            document.querySelectorAll("._delete-student-from-class-group").forEach(item => {
                item.addEventListener("click", _ => this.deleteStudentFromGroup(item));
            });
        } else {
            button.addEventListener("click", _ => this.deleteStudentFromGroup(button));
        }
    }

    handleOpenAddTaughtGroupForm(button)
    {
        if(button === undefined) {
            document.querySelectorAll("._open-add-taught-group-form").forEach(item => {
                item.addEventListener("click", _ => this.openAddTaughtGroupForm(item));
            });
        } else {
            button.addEventListener("click", _ => this.openAddTaughtGroupForm(button));
        }
    }

    handleAddTaughtGroup(button)
    {
        if(button === undefined) {
            document.querySelectorAll("._add-taught-group").forEach(item => {
                item.addEventListener("click", _ => this.addTaughtGroup(item));
            });
        } else {
            button.addEventListener("click", _ => this.addTaughtGroup(button));
        }
    }

    handleDeleteTaughtGroup(button)
    {
        if(button === undefined) {
            document.querySelectorAll("._delete-taught-group").forEach(item => {
                item.addEventListener("click", _ => this.deleteTaughtGroup(item));
            });
        } else {
            button.addEventListener("click", _ => this.deleteTaughtGroup(button));
        }
    }

    handleOpenAddClassroomForm()
    {
        document.querySelector("#_open-add-classroom-form").addEventListener("click", _ => {
            this.openAddClassroomForm();
        });
    }

    handleAddClassroom()
    {
        document.querySelector("#_add-classroom").addEventListener("click", _ => this.addClassroom());
    }

    handleDeleteClassroom(button)
    {
        if(button === undefined) {
            document.querySelectorAll("._delete-classroom").forEach(item => {
                item.addEventListener("click", _ => this.deleteClassroom(item));
            });
        } else {
            button.addEventListener("click", _ => this.deleteClassroom(button));
        }
    }

    handleOpenAddTeacherForm()
    {
        document.querySelector("#_open-add-teacher-form").addEventListener("click", _ => {
            this.openAddTeacherForm();
        });
    }

    handleAddTeacher()
    {
        document.querySelector("#_add-teacher").addEventListener("click", _ => this.addTeacher());
    }

    handleDeleteTeacher(button)
    {
        if(button === undefined) {
            document.querySelectorAll("._delete-teacher").forEach(item => {
                item.addEventListener("click", _ => this.deleteTeacher(item))
            });
        } else {
            button.addEventListener("click", _ => this.deleteTeacher(button));
        }
    }

    handleOpenAddSubjectForm()
    {
        document.querySelector("#_open-add-subject-form").addEventListener("click", _ => {
            this.openAddSubjectForm();
        });
    }

    handleAddSubject()
    {
        document.querySelector("#_add-subject").addEventListener("click", _ => this.addSubject());

    }

    handleDeleteSubject(button)
    {
        if(button === undefined) {
            document.querySelectorAll("._delete-subject").forEach(item => {
                item.addEventListener("click", _ => this.deleteSubject(item))
            });
        } else {
            button.addEventListener("click", _ => this.deleteSubject(button));
        }
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

    processAccessRequest(button)
    {
        const request = button.dataset.for;
        const decision = button.dataset.decision;

        const params = new URLSearchParams();
        params.append("request", request);
        params.append("decision", decision);

        axios.post(`/application/profiles/process-class-access-request`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = document.querySelector("#_access-requests-container");
                    const lineId = button.dataset.line;

                    container.querySelectorAll("._request[data-id='" + request + "']").forEach(item => {
                        container.removeChild(item);
                    });

                    const line = container.querySelector("._line[data-id='" + lineId + "']");

                    if(line !== null) {
                        container.removeChild(line);
                    }

                    if(container.querySelectorAll("._request").length === 0) {
                        document.querySelector("#_class-profile-footer").classList.add("hide");
                    }
                } else {
                    alert(data.message);
                }
            });
    }

    leaveClass()
    {
        if(!confirm("Opravdu si přeješ opustit svou třídu?")) {
            return;
        }

        axios.post(`/application/profiles/leave-class`)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    location.href = "/application";
                } else {
                    alert(data.message);
                }
            });
    }

    editClassName()
    {
        const input = document.querySelector("#_edit-class-form-name");

        input.removeAttribute("disabled");
        input.focus();
        input.select();

        document.querySelector("#_edit-class-name").classList.add("hide");
        document.querySelector("#_save-class-name").classList.remove("hide");
    }

    saveClassName()
    {
        const name = document.querySelector("#_edit-class-form-name").value;

        const params = new URLSearchParams();
        params.append("name", name);

        axios.post(`/application/profiles/change-class-name`, params)
            .then(response => {
                const data = response.data;
                const alertContainer = document.querySelector("#_edit-class-form-alerts");

                const alert = document.createElement("p");
                alert.classList.add("form-alert");

                alertContainer.innerHTML = "";
                alertContainer.appendChild(alert);

                if(data.success === true) {
                    document.querySelector("#_edit-class-name").classList.remove("hide");
                    document.querySelector("#_save-class-name").classList.add("hide");

                    document.querySelector("#_edit-class-form-name").setAttribute("disabled", "disabled");

                    alert.classList.add("positive")
                    alert.textContent = "Název třídy byl úspěšně změněn";
                } else {
                    alert.classList.add("negative");
                    alert.textContent = data.message;
                }
            });
    }

    openCreateGroupForm(button)
    {
        document.querySelector("#_create-group-form-container").classList.remove("hide");
        button.classList.add("hide");
    }

    createGroup()
    {
        const name = document.querySelector("#_create-group-form-name").value;

        const params = new URLSearchParams();
        params.append("name", name);

        axios.post(`/application/profiles/create-class-group`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = document.querySelector("#_class-groups-container");
                    const formContainer = document.querySelector("#_create-group-form-container");

                    const newGroup = document.querySelector("#_class-group-template").cloneNode(true);
                    newGroup.classList.remove("hide");
                    newGroup.removeAttribute("id");
                    newGroup.dataset.id = data.id;
                    newGroup.querySelector("._group-name").textContent = name;

                    const deleteButton = newGroup.querySelector("._delete-class-group");
                    deleteButton.dataset.id = data.id;
                    this.handleDeleteGroup(deleteButton);

                    const addStudentSelect = newGroup.querySelector("._add-student-to-class-group");
                    addStudentSelect.dataset.id = data.id;
                    this.handleAddStudentToGroup(addStudentSelect);

                    const openTaughtGroupFormButton = newGroup.querySelector("._open-add-taught-group-form");
                    this.handleOpenAddTaughtGroupForm(openTaughtGroupFormButton);

                    const addTaughtGroupButton = newGroup.querySelector("._add-taught-group");
                    addTaughtGroupButton.dataset.id = data.id;
                    this.handleAddTaughtGroup(addTaughtGroupButton);

                    container.insertBefore(newGroup, formContainer);

                    formContainer.classList.add("hide");
                    document.querySelector("#_open-create-group-form").classList.remove("hide");
                } else {
                    alert(data.message);
                }
            });
    }

    deleteGroup(button)
    {
        const group = button.dataset.id;

        const params = new URLSearchParams();
        params.append("group", group);

        axios.post(`/application/profiles/delete-class-group`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = document.querySelector("#_class-groups-container");

                    container.removeChild(button.closest("._class-group"));
                } else {
                    alert(data.message);
                }
            });
    }

    addStudentToGroup(select)
    {
        const user = select.value;
        const group = select.dataset.id;

        const params = new URLSearchParams();
        params.append("user", user);
        params.append("group", group);

        axios.post(`/application/profiles/add-student-to-class-group`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const groupElement = select.closest("._class-group");
                    const container = groupElement.querySelector("._students-container");

                    const newStudent = document.querySelector("#_student-template").cloneNode(true);
                    newStudent.classList.remove("hide");
                    newStudent.removeAttribute("id");
                    newStudent.querySelector("._student-name").textContent = select.options[select.selectedIndex].textContent;

                    const deleteButton = newStudent.querySelector("._delete-student-from-class-group");
                    deleteButton.dataset.id = user;
                    this.handleDeleteStudentFromGroup(deleteButton);

                    container.appendChild(newStudent);

                    select.remove(select.selectedIndex);
                    select.selectedIndex = 0;
                    const defaultOption = select.options[select.selectedIndex];
                    if(defaultOption.textContent === "Vytvoř seznam žáků/studentů") {
                        defaultOption.textContent = "Žák/student";
                    }
                } else {
                    alert(data.message);
                }
            });
    }

    deleteStudentFromGroup(button)
    {
        const groupElement = button.closest("._class-group");

        const user = button.dataset.id;
        const group = groupElement.dataset.id;

        const params = new URLSearchParams();
        params.append("user", user);
        params.append("group", group);

        axios.post(`/application/profiles/delete-student-from-class-group`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = groupElement.querySelector("._students-container");
                    const studentElement = button.closest("._student");
                    const studentFullName = studentElement.querySelector("._student-name").textContent;

                    container.removeChild(studentElement);

                    const select = groupElement.querySelector("._add-student-to-class-group");
                    const newOption = document.createElement("option");
                    newOption.value = user;
                    newOption.textContent = studentFullName;
                    select.add(newOption);

                    if(container.querySelector("._student") === null) {
                        select.options[select.selectedIndex].textContent = "Vytvoř seznam žáků/studentů";
                    }
                } else {
                    alert(data.message);
                }
            });
    }

    openAddTaughtGroupForm(button)
    {
        const form = button.closest("._taught-group-container").querySelector("._add-taught-group-form");

        form.classList.remove("hide");
        button.classList.add("hide");
    }

    addTaughtGroup(button)
    {
        const container = button.closest("._taught-group-container");
        const form = container.querySelector("._add-taught-group-form");
        const subjectSelect = form.querySelector("._subject");
        const teacherSelect = form.querySelector("._teacher");

        const group = button.dataset.id;
        const subject = subjectSelect.value;
        const teacher = teacherSelect.value;

        const params = new URLSearchParams();
        params.append("group", group);
        params.append("subject", subject);
        params.append("teacher", teacher);

        axios.post(`/application/profiles/add-taught-group-to-class-group`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const newTaughtGroup = document.querySelector("#_taught-group-template").cloneNode(true);
                    newTaughtGroup.classList.remove("hide");
                    newTaughtGroup.removeAttribute("id");
                    newTaughtGroup.querySelector("._subject").textContent = subjectSelect.options[subjectSelect.selectedIndex].textContent;
                    newTaughtGroup.querySelector("._teacher").textContent = teacherSelect.options[teacherSelect.selectedIndex].textContent;

                    container.appendChild(newTaughtGroup);

                    const deleteButton = newTaughtGroup.querySelector("._delete-taught-group");
                    deleteButton.dataset.id = data.id;
                    this.handleDeleteTaughtGroup(deleteButton);

                    subjectSelect.selectedIndex = 0;
                    teacherSelect.selectedIndex = 0;
                } else {
                    alert(data.message);
                }
            });
    }

    deleteTaughtGroup(button)
    {
        const classGroup = button.closest("._class-group").dataset.id;
        const taughtGroup = button.dataset.id;

        const params = new URLSearchParams();
        params.append("class-group", classGroup);
        params.append("taught-group", taughtGroup);

        axios.post(`/application/profiles/delete-taught-group-from-class-group`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = button.closest("._taught-group-container");
                    const taughtGroupElement = button.closest("._taught-group");

                    container.removeChild(taughtGroupElement);
                } else {
                    alert(data.message);
                }
            });
    }

    openAddClassroomForm()
    {
        document.querySelector("#_add-classroom-form").classList.remove("hide");
        document.querySelector("#_open-add-classroom-form").classList.add("hide");
    }

    addClassroom()
    {
        const nameInput = document.querySelector("#_add-classroom-form-name");
        const descriptionInput = document.querySelector("#_add-classroom-form-description");

        const name = nameInput.value;
        const description = descriptionInput.value;

        const params = new URLSearchParams();
        params.append("name", name);
        params.append("description", description);

        axios.post(`/application/profiles/add-classroom`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = document.querySelector("#_classrooms-container")

                    const newClassroom = document.querySelector("#_classroom-template").cloneNode(true);
                    newClassroom.classList.remove("hide");
                    newClassroom.removeAttribute("id");
                    newClassroom.querySelector("._name").textContent = name;
                    newClassroom.querySelector("._description").textContent = description;

                    container.appendChild(newClassroom);

                    const deleteButton = newClassroom.querySelector("._delete-classroom");
                    deleteButton.dataset.id = data.id;
                    this.handleDeleteClassroom(deleteButton);

                    nameInput.value = "";
                    descriptionInput.value = "";
                } else {
                    alert(data.message);
                }
            });
    }

    deleteClassroom(button)
    {
        const classroom = button.dataset.id;

        const params = new URLSearchParams();
        params.append("classroom", classroom);

        axios.post(`/application/profiles/delete-classroom`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = document.querySelector("#_classrooms-container")
                    const classroomElement = button.closest("._classroom");

                    container.removeChild(classroomElement);
                } else {
                    alert(data.message);
                }
            });
    }

    openAddTeacherForm()
    {
        document.querySelector("#_add-teacher-form").classList.remove("hide");
        document.querySelector("#_open-add-teacher-form").classList.add("hide");
    }

    addTeacher()
    {
        const shortcutInput = document.querySelector("#_add-teacher-form-shortcut");
        const degreeBeforeInput = document.querySelector("#_add-teacher-form-degree-before");
        const fullNameInput = document.querySelector("#_add-teacher-form-name");
        const degreeAfterInput = document.querySelector("#_add-teacher-form-degree-after");

        const shortcut = shortcutInput.value;
        let degreeBefore = degreeBeforeInput.value;
        const fullName = fullNameInput.value;
        let degreeAfter = degreeAfterInput.value;

        const params = new URLSearchParams();
        params.append("shortcut", shortcut);
        params.append("degree-before", degreeBefore);
        params.append("full-name", fullName);
        params.append("degree-after", degreeAfter);

        axios.post(`/application/profiles/add-teacher`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = document.querySelector("#_teachers-container")

                    degreeBefore = (degreeBefore !== "" ? degreeBefore + " " : "");
                    degreeAfter = (degreeAfter !== "" ? ", " + degreeAfter : "");

                    const newTeacher = document.querySelector("#_teacher-template").cloneNode(true);
                    newTeacher.classList.remove("hide");
                    newTeacher.removeAttribute("id");
                    newTeacher.querySelector("._shortcut").textContent = shortcut;
                    newTeacher.querySelector("._name").textContent = degreeBefore + fullName + degreeAfter;

                    container.appendChild(newTeacher);

                    const deleteButton = newTeacher.querySelector("._delete-teacher");
                    deleteButton.dataset.id = data.id;
                    this.handleDeleteTeacher(deleteButton);

                    shortcutInput.value = "";
                    degreeBeforeInput.value = "";
                    fullNameInput.value = "";
                    degreeAfterInput.value = "";
                } else {
                    alert(data.message);
                }
            });
    }

    deleteTeacher(button)
    {
        const teacher = button.dataset.id;

        const params = new URLSearchParams();
        params.append("teacher", teacher);

        axios.post(`/application/profiles/delete-teacher`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = document.querySelector("#_teachers-container")
                    const teacherElement = button.closest("._teacher");

                    container.removeChild(teacherElement);
                } else {
                    alert(data.message);
                }
            });
    }

    openAddSubjectForm()
    {
        document.querySelector("#_add-subject-form").classList.remove("hide");
        document.querySelector("#_open-add-subject-form").classList.add("hide");
    }

    addSubject()
    {
        const shortcutInput = document.querySelector("#_add-subject-form-shortcut");
        const nameInput = document.querySelector("#_add-subject-form-name");

        const shortcut = shortcutInput.value;
        const name = nameInput.value;

        const params = new URLSearchParams();
        params.append("shortcut", shortcut);
        params.append("name", name);

        axios.post(`/application/profiles/add-subject`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = document.querySelector("#_subjects-container")

                    const newSubject = document.querySelector("#_subject-template").cloneNode(true);
                    newSubject.classList.remove("hide");
                    newSubject.removeAttribute("id");
                    newSubject.querySelector("._shortcut").textContent = shortcut;
                    newSubject.querySelector("._name").textContent = name;

                    container.appendChild(newSubject);

                    const deleteButton = newSubject.querySelector("._delete-subject");
                    deleteButton.dataset.id = data.id;
                    //this.handleDeleteTeacher(deleteButton);

                    shortcutInput.value = "";
                    nameInput.value = "";
                } else {
                    alert(data.message);
                }
            });
    }

    deleteSubject(button)
    {
        const subject = button.dataset.id;

        const params = new URLSearchParams();
        params.append("subject", subject);

        axios.post(`/application/profiles/delete-subject`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const container = document.querySelector("#_subjects-container")
                    const subjectElement = button.closest("._subject");

                    container.removeChild(subjectElement);
                } else {
                    alert(data.message);
                }
            });
    }
}