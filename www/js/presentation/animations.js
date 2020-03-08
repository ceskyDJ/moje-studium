class AnimationController {
    constructor() {
        this.initProperties();

        this.handleSmileAnimation();
        this.initFirstBlock();
        this.initSecondBlock();
    }

    initProperties()
    {
        this.smile = document.querySelector("#_smile-icon");
        this.firstBlocks = document.querySelectorAll("._first-animate-block");
        this.secondBlock = document.querySelectorAll("._second-animate-block");
    }

    handleSmileAnimation()
    {
        const registerButton = document.querySelector("#_register-button");

        if(registerButton === null) {
            return;
        }

        registerButton.addEventListener("mouseenter", _ => {
            this.animateSmile();
        });

        registerButton.addEventListener("mouseout", _ => {
            this.animateSmile(false);
        });
    }

    initFirstBlock()
    {
        this.firstBlocks.forEach(object => {
            // For browsers that load object with other HTML elements
            if(object.contentDocument !== undefined && object.contentDocument !== null && object.contentDocument.querySelector("svg") !== null) {
                this.handleFirstBlockAnimations(object);

                return;
            }

            // For browsers that load object after all
            object.addEventListener("load", _ => this.handleFirstBlockAnimations(object));
        });
    }

    initSecondBlock()
    {
        this.secondBlock.forEach(object => {
            // For browsers that load object with other HTML elements
            if(object.contentDocument !== undefined && object.contentDocument !== null && object.contentDocument.querySelector("svg") !== null) {
                this.handleSecondBlockAnimations(object);

                return;
            }

            // For browsers that load object after all
            object.addEventListener("load", _ => this.handleSecondBlockAnimations(object));
        });
    }

    handleFirstBlockAnimations(object)
    {
        const svg = object.contentDocument;

        const notebook = svg.querySelector("#_notebook");
        const bookshelf = svg.querySelector("#_bookshelf");
        const chandelier = svg.querySelector("#_chandelier");
        const lamp = svg.querySelector("#_lamp");

        const svgContainer = svg.querySelector("svg");
        const table = svg.querySelector("#_table");
        const pencilsCrucible = svg.querySelector("#_pencils-crucible");

        notebook.addEventListener("click", _ => this.animateNotebook(notebook));
        bookshelf.addEventListener("click", _ => this.animateBookshelf(bookshelf));
        chandelier.addEventListener("click", _ => this.animateChandelier(chandelier, svgContainer));
        lamp.addEventListener("click", _ => this.animateLamp(lamp, bookshelf, table, pencilsCrucible));
    }

    handleSecondBlockAnimations(object)
    {
        const svg = object.contentDocument;

        const papers = svg.querySelector("#_papers");
        const cup = svg.querySelector("#_cup");
        const phone = svg.querySelector("#_phone");

        const background = svg.querySelector("#_background");
        const shadows = svg.querySelectorAll("._shadow");

        papers.addEventListener("click", _ => this.animatePapers(papers));
        cup.addEventListener("click", _ => this.animateCup(cup));
        phone.addEventListener("click", _ => this.animatePhone(phone, cup, background, shadows));
    }

    animateSmile(enter = true)
    {
        if(enter) {
            this.smile.classList.replace("smile-i", "laugh-i");
        } else {
            this.smile.classList.replace("laugh-i", "smile-i");
        }
    }

    animateNotebook(notebook)
    {
        const firstNotebookWindow = notebook.querySelector("#_notebook-window-1");
        const secondNotebookWindow = notebook.querySelector("#_notebook-window-2");

        if(notebook.dataset.changed === "true") {
            // Revert change
            firstNotebookWindow.style.fill = "#7F9394";
            secondNotebookWindow.style.fill = "#D2D1CE";

            notebook.dataset.changed = "false";
        } else {
            // Change
            firstNotebookWindow.style.fill = "#D2D1CE";
            secondNotebookWindow.style.fill = "#7F9394";

            notebook.dataset.changed = "true";
        }
    }

    animateBookshelf(bookshelf)
    {
        const originalBooks = bookshelf.querySelectorAll("._book-original");
        const changedBooks = bookshelf.querySelectorAll("._book-changed");

        if(bookshelf.dataset.changed === "true") {
            // Revert change
            originalBooks.forEach(item => item.style.display = "block");
            changedBooks.forEach(item => item.style.display = "none");

            bookshelf.dataset.changed = "false";
        } else {
            // Change
            originalBooks.forEach(item => item.style.display = "none");
            changedBooks.forEach(item => item.style.display = "block");
            originalBooks.forEach(item => item.style.display = "none");

            bookshelf.dataset.changed = "true";
        }
    }

    animateChandelier(chandelier, container)
    {
        const chandelierLight = chandelier.querySelector("#_chandelier-light");
        const chandelierLightRadius = chandelier.querySelector("#_chandelier-light-radius");

        if (chandelier.dataset.off === "true") {
            // Turn on
            chandelierLight.style.fill = "#FFE252";
            chandelierLight.style.stroke = "#8A8A8C";
            chandelierLightRadius.style.visibility = "visible";
            container.style.background = "none";

            chandelier.dataset.off = "false";
        } else {
            // Turn off
            chandelierLight.style.fill = "#DEDA68";
            chandelierLight.style.stroke = "#000000";
            chandelierLightRadius.style.visibility = "hidden";
            container.style.background = "#84ADAA";

            chandelier.dataset.off = "true";
        }
    }

    animateLamp(lamp, bookshelf, table, pencilsCrucible)
    {
        const lampLightRadius = lamp.querySelector("#_lamp-light-radius");
        const shelf = bookshelf.querySelector("#_shelf");
        const tableTop = table.querySelector("#_table-top");
        const tableLegs = table.querySelectorAll("._table-leg");
        const crucibleParts = pencilsCrucible.querySelectorAll("#_crucible *");
        const pencilsParts = pencilsCrucible.querySelectorAll("#_pencils *");
        const crucibleBottom = pencilsCrucible.querySelector("#_crucible-bottom");

        if(lamp.dataset.off === "true") {
            // Turn on
            lampLightRadius.style.visibility = "visible";
            tableTop.style.fill = "#FFFFFF";
            tableTop.style.stroke = "#8A8A8C";
            tableLegs.forEach(item => {
                item.style.fill = "#D2D1CE";
                item.style.stroke = "#8A8A8C";
            });
            pencilsParts.forEach(item => item.style.fill = "#8A898B");
            crucibleParts.forEach(item => item.style.stroke = "#8A898B");
            crucibleBottom.style.fill = "#D2D1CE";
            shelf.style.stroke = "#8A8A8C";

            lamp.dataset.off = "false";
        } else {
            // Turn off
            lampLightRadius.style.visibility = "hidden";
            tableTop.style.fill = "#A7A5A6";
            tableTop.style.stroke = "#000000";
            tableLegs.forEach(item => {
                item.style.fill = "#A7A5A6";
                item.style.stroke = "#000000";
            });
            pencilsParts.forEach(item => item.style.fill = "#000000");
            crucibleParts.forEach(item => item.style.stroke = "#000000");
            crucibleBottom.style.fill = "#000000";
            shelf.style.stroke = "#000000";

            lamp.dataset.off = "true";
        }
    }

    animatePapers(papers)
    {
        const originalPapers = papers.querySelectorAll("._paper-original");
        const changedPapers = papers.querySelectorAll("._paper-changed");

        if(papers.dataset.changed === "true") {
            // Revert change
            originalPapers.forEach(item => item.style.display = "block");
            changedPapers.forEach(item => item.style.display = "none");

            papers.dataset.changed = "false";
        } else {
            // Change
            originalPapers.forEach(item => item.style.display = "none");
            changedPapers.forEach(item => item.style.display = "block");

            papers.dataset.changed = "true";
        }
    }

    animateCup(cup)
    {
        const originalSmoke = cup.querySelector("#_smoke");
        const changedSmoke = cup.querySelector("#_smoke-changed");

        if(cup.dataset.changed === "true") {
            // Revert change
            originalSmoke.style.display = "block";
            changedSmoke.style.display = "none";

            cup.dataset.changed = "false";
        } else {
            // Change
            originalSmoke.style.display = "none";
            changedSmoke.style.display = "block";

            cup.dataset.changed = "true";
        }
    }

    animatePhone(phone, cup, background, shadows)
    {
        const phoneLightRadius = phone.querySelector("#_phone-light-radius");
        const coffee = cup.querySelector("#_coffee");

        if(phone.dataset.off === "true") {
            // Turn on
            phoneLightRadius.style.visibility = "visible";
            shadows.forEach(item => item.style.fill = "#6A7979");
            background.style.fill = "#84ADA9";
            coffee.style.fill = "#6A7979";

            phone.dataset.off = "false";
        } else {
            // Turn off
            phoneLightRadius.style.visibility = "hidden";
            shadows.forEach(item => item.style.fill = "#84ADA9");
            background.style.fill = "#7F9394";
            coffee.style.fill = "#84ADA9";

            phone.dataset.off = "true";
        }
    }
}

new AnimationController();