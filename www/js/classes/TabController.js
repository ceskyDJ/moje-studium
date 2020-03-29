class TabController
{
    constructor()
    {
        this.handleChangeActivePage();
    }

    handleChangeActivePage()
    {
        document.querySelectorAll("._tab-page-changer").forEach(button => {
            button.addEventListener("click", _ => this.changeActivePage(button))
        });
    }

    changeActivePage(selectedButton)
    {
        const selectedTab = selectedButton.dataset.page;

        document.querySelectorAll("._tab-page-changer").forEach(button => {
            if(button.dataset.page === selectedTab) {
                button.classList.add("active");
            } else {
                button.classList.remove("active");
            }
        });

        document.querySelectorAll("._tab-page").forEach(page => {
            if(page.dataset.id === selectedTab) {
                page.classList.remove("hide");
            } else {
                page.classList.add("hide");
            }
        });
    }
}