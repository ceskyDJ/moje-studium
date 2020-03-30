class ClassController
{
    constructor()
    {
        this.chooseSchoolSelect = document.querySelector("#_choose-school-select");
        this.chooseClassSelect = document.querySelector("#_choose-class-select");
        this.classesContainer = document.querySelector("#_classes-container");

        this.handleSchoolChange();
        this.handleDeleteClass();
    }

    handleSchoolChange()
    {
        if(this.chooseSchoolSelect === null) {
            return;
        }

        this.chooseSchoolSelect.addEventListener("change", _ => this.loadClasses());
    }

    handleDeleteClass()
    {
        document.querySelectorAll("._delete-class").forEach(item => {
            item.addEventListener("click", _ => this.deleteClass(item));
        });
    }

    loadClasses()
    {
        // Remove old options
        this.chooseClassSelect.querySelectorAll("._added-option").forEach(item => {
            this.chooseClassSelect.removeChild(item);
        });

        // Download new options
        axios.get("/application/class/get-classes/" + this.chooseSchoolSelect.value)
            .then(response => {
                response.data.forEach(item => {
                    const option = document.createElement("option");
                    option.text = item.displayName;
                    option.setAttribute("value", item.id);
                    option.classList.add("_added-option");

                    this.chooseClassSelect.appendChild(option);
                });
            })
            .catch()
            .then(_ => {
                // Change text in class chooser
                this.chooseClassSelect.querySelectorAll("option").forEach(item => {
                    item.removeAttribute("selected");
                });

                this.chooseClassSelect.querySelector("#_choose-class-option").setAttribute("selected", "selected");
            });
    }

    deleteClass(button)
    {
        const id = button.dataset.id;

        const params = new URLSearchParams();
        params.append("class", id);

        axios.post(`/admin/home/delete-class`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    // User's data rows
                    this.classesContainer.querySelectorAll(`._class[data-id="${id}"]`).forEach(classDataRow => {
                        this.classesContainer.removeChild(classDataRow);
                    });

                    // Table line
                    const line = this.classesContainer.querySelector(`._line[data-id="${button.dataset.line}"]`);
                    this.classesContainer.removeChild(line);
                } else {
                    alert(data.message);
                }
            });
    }
}