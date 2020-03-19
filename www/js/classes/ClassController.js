class ClassController
{
    constructor()
    {
        this.chooseSchoolSelect = document.querySelector("#_choose-school-select");
        this.chooseClassSelect = document.querySelector("#_choose-class-select");

        this.handleSchoolChange();
    }

    handleSchoolChange()
    {
        if(this.chooseSchoolSelect === undefined) {
            return;
        }

        this.chooseSchoolSelect.addEventListener("change", _ => this.loadClasses());
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
}