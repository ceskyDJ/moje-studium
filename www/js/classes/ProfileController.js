class ProfileController
{
    constructor()
    {
        this.quotaProgressBar = document.querySelector("#_quota-progress-bar");

        this.loadProgressBar();

        this.handleSubpageChange();
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

    handleSubpageChange()
    {
        document.querySelectorAll("._profile-menu-item").forEach(item => {
            item.addEventListener("click", this.changeSubpage);
        });
    }

    changeSubpage(event)
    {
        const clickedMenuItem = event.target.closest("._profile-menu-item");
        const subpageId = clickedMenuItem.dataset.for;

        document.querySelectorAll("._profile-menu-item").forEach(item => {
            if(item !== clickedMenuItem) {
                item.classList.remove("active");
            } else {
                item.classList.add("active");
            }
        });

        document.querySelectorAll("._profile-subpage").forEach(subpage => {
            if(subpage.dataset.id !== subpageId) {
                subpage.classList.add("hide");
            } else {
                subpage.classList.remove("hide");
            }
        });
    }
}