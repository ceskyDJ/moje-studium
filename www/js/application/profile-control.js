class ProfileController {
    constructor()
    {
        this.quotaProgressBar = document.querySelector("#_quota-progress-bar");

        this.loadProgressBar();
    }

    loadProgressBar()
    {
        const progressBar = new ProgressBar.Circle("#_quota-progress-bar", {
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
}

new ProfileController();