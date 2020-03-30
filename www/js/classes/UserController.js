class UserController
{
    constructor()
    {
        this.usersContainer = document.querySelector("#_users-container");

        this.handleChangeRank();
        this.handleDeleteFiles();
        this.handleDeleteUser();
    }

    handleChangeRank()
    {
        document.querySelectorAll("._change-rank").forEach(item => {
            item.addEventListener("click", _ => this.changeRank(item));
        });
    }

    handleDeleteFiles()
    {
        document.querySelectorAll("._delete-files").forEach(item => {
            item.addEventListener("click", _ => this.deleteFiles(item));
        });
    }

    handleDeleteUser()
    {
        document.querySelectorAll("._delete-user").forEach(item => {
            item.addEventListener("click", _ => this.deleteUser(item));
        });
    }

    changeRank(button)
    {
        const id = button.dataset.id;
        const rank = button.classList.contains("change-to-user-i") ? "Uzivatel" : "Admin";

        const params = new URLSearchParams();
        params.append("user", id);
        params.append("rank", rank);

        axios.post(`/admin/home/change-rank`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const user = this.usersContainer.querySelector(`._user[data-id="${id}"]`);
                    const infoIcon = user.querySelector("._rank");

                    if(button.classList.contains("change-to-user-i")) {
                        // Action button
                        button.classList.replace("change-to-user-i", "change-to-admin-i");

                        // Info icon
                        infoIcon.classList.replace("admin-i", "user-i");
                    } else {
                        // Action button
                        button.classList.replace("change-to-admin-i", "change-to-user-i");

                        // Info icon
                        infoIcon.classList.replace("user-i", "admin-i");
                    }
                } else {
                    alert(data.message);
                }
            });
    }

    deleteFiles(button)
    {
        const id = button.dataset.id;

        const params = new URLSearchParams();
        params.append("user", id);

        axios.post(`/admin/home/delete-user-files`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    const user = this.usersContainer.querySelector(`._user[data-id="${id}"]`);

                    user.querySelector("._used-quota").textContent = "0";
                    user.querySelectorAll("._quota-percentage").forEach(item => {
                        item.textContent = "0";
                    });
                } else {
                    alert(data.message);
                }
            });
    }

    deleteUser(button)
    {
        const id = button.dataset.id;

        const params = new URLSearchParams();
        params.append("user", id);

        axios.post(`/admin/home/delete-user`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    // User's data rows
                    this.usersContainer.querySelectorAll(`._user[data-id="${id}"]`).forEach(userDataRow => {
                        this.usersContainer.removeChild(userDataRow);
                    });

                    // Table line
                    const line = this.usersContainer.querySelector(`._line[data-id="${button.dataset.line}"]`);
                    this.usersContainer.removeChild(line);
                } else {
                    alert(data.message);
                }
            });
    }
}