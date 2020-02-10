const rgbToHex = rgb => {
    const rgbParts = rgb.replace("rgb(", "").replace(")", "").split(", ");

    for(let i = 0; i <= 2; i++) {
        rgbParts[i] = parseInt(rgbParts[i]).toString(16);
    }

    return "#" + rgbParts.join("");
};

const setUpSmileAnimation = _ => {
    const registerButton = document.querySelector("#_register-button");

    registerButton.addEventListener("mouseenter", _ => {
        document.querySelector("#_smile-icon").classList.replace("smile-i", "laugh-i");
    });

    registerButton.addEventListener("mouseout", _ => {
        document.querySelector("#_smile-icon").classList.replace("laugh-i", "smile-i");
    });
};

const setUpLightAnimations = _ => {
    const lightOnColor = "#ffe252";
    const lightOffColor = "#deda68";
    const lightOffBackground = "#84ADAA";

    document.querySelectorAll("._first-animate-block").forEach(object => {
        object.addEventListener("load", _ => {
            const svg = object.contentDocument;

            const svgContainer = svg.querySelector("svg");
            const chandelier = svg.querySelector("#_chandelier");
            const chandelierLight = svg.querySelector("#_chandelier-light");
            const chandelierLightRadius = svg.querySelector("#_chandelier-light-radius");
            const lamp = svg.querySelector("#_lamp");
            const lampLightRadius = svg.querySelector("#_lamp-light-radius");
            const tableTop = svg.querySelector("#_table-top");
            const tableLegs = svg.querySelectorAll("._table-leg");
            const crucibleParts = svg.querySelectorAll("#_crucible *");
            const pencilsParts = svg.querySelectorAll("#_pencils *");
            const crucibleBottom = svg.querySelector("#_crucible-bottom");
            const bookshelf = svg.querySelector("#_bookshelf");
            const shelf = svg.querySelector("#_shelf");
            const originalBooks = svg.querySelectorAll("._book-original");
            const changedBooks = svg.querySelectorAll("._book-changed");
            const notebook = svg.querySelector("#_notebook");
            const firstNotebookWindow = svg.querySelector("#_notebook-window-1");
            const secondNotebookWindow = svg.querySelector("#_notebook-window-2");

            // Notebook
            notebook.addEventListener("click", _ => {
                if(notebook.dataset.changed === "true") {
                    // Vrátit změnu
                    firstNotebookWindow.style.fill = "#7F9394";
                    secondNotebookWindow.style.fill = "#D2D1CE";

                    notebook.dataset.changed = "false";
                } else {
                    // Změnit
                    firstNotebookWindow.style.fill = "#D2D1CE";
                    secondNotebookWindow.style.fill = "#7F9394";

                    notebook.dataset.changed = "true";
                }
            });

            // Knihy
            bookshelf.addEventListener("click", _ => {
                if(bookshelf.dataset.changed === "true") {
                    // Vrátit změnu
                    originalBooks.forEach(item => item.style.display = "block");
                    changedBooks.forEach(item => item.style.display = "none");

                    bookshelf.dataset.changed = "false";
                } else {
                    // Změnit
                    originalBooks.forEach(item => item.style.display = "none");
                    changedBooks.forEach(item => item.style.display = "block");                   originalBooks.forEach(item => item.style.display = "none");

                    bookshelf.dataset.changed = "true";
                }
            });

            // Lustr
            chandelier.addEventListener("click", _ => {
                if (chandelier.dataset.off === "true") {
                    // Zapnout
                    chandelierLight.style.fill = lightOnColor;
                    chandelierLight.style.stroke = "#8A8A8C";
                    chandelierLightRadius.style.visibility = "visible";
                    svgContainer.style.background = "none";

                    chandelier.dataset.off = "false";
                } else {
                    // Vypnout
                    chandelierLight.style.fill = lightOffColor;
                    chandelierLight.style.stroke = "#000000";
                    chandelierLightRadius.style.visibility = "hidden";
                    svgContainer.style.background = "#84ADAA";

                    chandelier.dataset.off = "true";
                }
            });

            // Lampa
            lamp.addEventListener("click", _ => {
                if(lamp.dataset.off === "true") {
                    // Zapnout
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
                    // Vypnout
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
            });
        });
    });

    document.querySelectorAll("._second-animate-block").forEach(object => {
        object.addEventListener("load", _ => {
            const svg = object.contentDocument;

            const phoneLightRadius = svg.querySelector("#_phone-light-radius");
            const phone = svg.querySelector("#_phone");
            const shadows = svg.querySelectorAll("._shadow");
            const background = svg.querySelector("#_background");
            const coffee = svg.querySelector("#_coffee");
            const cup = svg.querySelector("#_cup");
            const originalSmoke = svg.querySelector("#_smoke");
            const changedSmoke = svg.querySelector("#_smoke-changed");
            const papers = svg.querySelector("#_papers");
            const originalPapers = svg.querySelectorAll("._paper-original");
            const changedPapers = svg.querySelectorAll("._paper-changed");

            // Papíry
            papers.addEventListener("click", _ => {
                if(papers.dataset.changed === "true") {
                    // Vrátit změnu
                    originalPapers.forEach(item => item.style.display = "block");
                    changedPapers.forEach(item => item.style.display = "none");

                    papers.dataset.changed = "false";
                } else {
                    // Změnit
                    originalPapers.forEach(item => item.style.display = "none");
                    changedPapers.forEach(item => item.style.display = "block");

                    papers.dataset.changed = "true";
                }
            });

            // Šálek kávy
            cup.addEventListener("click", _ => {
                if(cup.dataset.changed === "true") {
                    // Vrátit změnu
                    originalSmoke.style.display = "block";
                    changedSmoke.style.display = "none";

                    cup.dataset.changed = "false";
                } else {
                    // Změnit
                    originalSmoke.style.display = "none";
                    changedSmoke.style.display = "block";

                    cup.dataset.changed = "true";
                }
            });

            // Telefon
            phone.addEventListener("click", _ => {
                if(phone.dataset.off === "true") {
                    // Zapnout
                    phoneLightRadius.style.visibility = "visible";
                    shadows.forEach(item => item.style.fill = "#6A7979");
                    background.style.fill = "#84ADA9";
                    coffee.style.fill = "#6A7979";

                    phone.dataset.off = "false";
                } else {
                    // Vypnout
                    phoneLightRadius.style.visibility = "hidden";
                    shadows.forEach(item => item.style.fill = "#84ADA9");
                    background.style.fill = "#7F9394";
                    coffee.style.fill = "#84ADA9";

                    phone.dataset.off = "true";
                }
            });
        });
    });
};

const init = _ => {
    setUpSmileAnimation();
    setUpLightAnimations();
};

init();