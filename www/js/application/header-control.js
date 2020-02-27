document.querySelector("#_show-notifications-b").addEventListener("click", event => {
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
});