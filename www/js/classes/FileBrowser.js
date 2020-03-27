class FileBrowser
{
    constructor(alertController)
    {
        this.filesContainer = document.querySelector("#_files-container");

        this.initForms();
        this.alertController = alertController;
        this.initDropzone();

        this.handleGoToFolder();
        this.handleOpenUploadFileForm();
        this.handleOpenCreateFolderForm();
        this.handleOpenMoveFileForm();
        this.handleOpenRenameFileForm();
        this.handleCreateFolder();
        this.handleMoveFile();
        this.handleDeleteFile();
    }

    initForms(element)
    {
        if(element === undefined) {
            document.querySelectorAll("._rename-file-form").forEach(form => {
                form.addEventListener("submit", event => event.preventDefault(), true);
            });

            document.querySelector("#_create-folder-form").addEventListener("submit", event => event.preventDefault(), true)
        } else {
            element.addEventListener("submit", event => event.preventDefault(), true);
        }
    }

    initDropzone()
    {
        Dropzone.autoDiscover = false;

        // Form alerts
        const formAlerts = document.querySelector("#_file-upload-form-alerts-container");
        formAlerts.innerHTML = "";

        // Show alert message
        const addFormAlert = message => {
            const alert = document.createElement("p");
            alert.textContent = message;
            alert.classList.add("form-alert");
            alert.classList.add("negative");
            formAlerts.appendChild(alert);
        };

        const dropPlace = document.querySelector("#_file-upload-form-file");

        this.dropzone = new Dropzone(`#${dropPlace.id}`, {
            url: "/application/files/upload",
            paramName: "file", // input name="{paramName}"
            maxFilesize: 20, // in MB
            parallelUploads: 4,
            headers: {'X-Requested-With': "XMLHttpRequest"},
            previewsContainer: "#_file-upload-form-progresses-container",
            dictFileTooBig : "Soubor \"{{filename}}\" je příliš velký ({{filesize}} MB). Maximální velikost je {{maxFilesize}} MB",
            dictFileSizeUnits: { tb: "TB", gb: "GB", mb: "MB", kb: "KB", b: "B" },
            addedfile: file => {
                const newProgress = document.querySelector("#_file_upload-form-progress-template").cloneNode(true);
                newProgress.id = "";
                newProgress.classList.remove("hide");

                file.previewElement = newProgress;
                file.previewTemplate = file.previewElement; // Backwards compatibility
                this.dropzone.previewsContainer.appendChild(file.previewElement);

                file.previewElement.querySelector("[data-dz-type] i").classList.add(`file-${this.getFileType(file.name)}-i`);
                file.previewElement.querySelector("[data-dz-name]").textContent = file.name;
            },
            sending: (file, xhr, formData) => {
                const parentFolder = document.querySelector("#_actual-folder").dataset.id;

                formData.append("parent", parentFolder);
            }
        });

        this.dropzone.on("dragenter", event => {
            dropPlace.classList.add("hover");
        });
        this.dropzone.on("dragleave", event => {
            dropPlace.classList.remove("hover");
        });
        this.dropzone.on("error", (file, message) => {
            addFormAlert(message.replace("{{filename}}", file.name));
        });
        this.dropzone.on("success", (file, response, e) => {
            const data = JSON.parse(response);

            if(data.success === true) {
                this.addFile(data.id, `file-${this.getFileType(file.name)}`, file.name);
            } else {
                addFormAlert(data.message);
            }
        });
        this.dropzone.on("queuecomplete", _ => {
            if(formAlerts.innerHTML === "") {
                this.alertController.hideAlert("upload-file");
            }
        });

        // Open browser's upload file dialog after click on text in the drop place, too
        document.querySelectorAll("._file-upload-form-description").forEach(item => {
            item.addEventListener("click", event => dropPlace.click(event));
        });
    }

    handleGoToFolder(element)
    {
        if(element === undefined) {
            document.querySelectorAll("._go-to-folder").forEach(item => {
                item.addEventListener("click", _ => this.goToFolder(item));
            });
        } else {
            element.addEventListener("click", _ => this.goToFolder(element));
        }
    }

    handleOpenUploadFileForm()
    {
        document.querySelector("#_upload-file").addEventListener("click", _ => this.openUploadFileForm());
    }

    handleOpenCreateFolderForm()
    {
        document.querySelector("#_create-folder").addEventListener("click", _ => this.openCreateFolderForm());
    }

    handleOpenMoveFileForm(element)
    {
        if(element === undefined) {
            document.querySelectorAll("._move-file").forEach(item => {
                item.addEventListener("click", _ => this.openMoveFileForm(item));
            });
        } else {
            element.addEventListener("click", _ => this.openMoveFileForm(element));
        }
    }

    handleOpenRenameFileForm(element)
    {
        if(element === undefined) {
            document.querySelectorAll("._rename-file").forEach(item => {
                item.addEventListener("click", _ => this.openRenameFileForm(item));
            });
        } else {
            element.addEventListener("click", _ => this.openRenameFileForm(element));
        }
    }

    handleCreateFolder()
    {
        document.querySelector("#_create-folder-form-button").addEventListener("click", _ => this.createFolder());
    }

    handleMoveFile()
    {
        document.querySelector("#_move-file-form-button").addEventListener("click", _ => this.moveFile());
    }

    handleRenameFile(fileRow)
    {
        const renameForm = fileRow.querySelector("._rename-file-form");

        renameForm.querySelector("._rename-file-form-button").addEventListener("click", _ => this.renameFile(renameForm));
    }

    handleDeleteFile(element)
    {
        if(element === undefined) {
            document.querySelectorAll("._delete-file").forEach(item => {
                item.addEventListener("click", _ => this.deleteFile(item));
            });
        } else {
            element.addEventListener("click", _ => this.deleteFile(element));
        }
    }

    getFileType(fileName)
    {
        const fileTypes = [
            'text-document', 'sheet-document', 'presentation-document', 'pdf', 'php', 'html', 'css', 'js',
            'source-code', 'executable', 'archive', 'database', 'font', 'image', 'audio', 'video'
        ];

        const fileExtensions = [
            ["txt", "doc", "docx", "rtf", "odt", "wpd", "wks", "wps"],
            ["ods", "xlr", "xls", "xlsx"],
            ["key", "odp", "pps", "ppt", "pptx"],
            ["pdf"],
            ["php", "phtml", "php5"],
            ["html", "htm", "xhtml"],
            ["css", "less", "scss", "sass", "styl"],
            ["js", "ts"],
            ["c", "class", "cpp", "cs", "h", "java", "sh", "bat", "swift", "vb", "py", "xml", "jsp", "cgi", "pl", "cfm", "asp", "aspx"],
            ["exe", "apk", "bin", "com", "gadget", "jar", "wsf"],
            ["7z", "arj", "deb", "pkg", "rar", "rpm", "gz", "z", "zip"],
            ["sql", "csv", "dat", "db", "dbf", "log", "mdb", "sav", "tar"],
            ["fnt", "fon", "otf", "ttf", "woff", "woff2", "eot"],
            ["ai", "bmp", "gif", "ico", "jpeg", "jpg", "png", "ps", "psd", "svg", "tif", "tiff"],
            ["aif", "cda", "mid", "midi", "mp3", "mpa", "ogg", "wav", "wma", "wpl"],
            ["3g2", "3gp", "avi", "flv", "h264", "mkv", "mov", "mp4", "mpg", "mpeg", "rm", "swf", "vob", "wmv"]
        ];

        const fileNameParts = fileName.split(".");
        const extension = fileNameParts[fileNameParts.length - 1].trim();

        let selectedFileType = "general";
        fileExtensions.forEach(extensionGroup => {
            if(extensionGroup.includes(extension)) {
                selectedFileType = fileTypes[fileExtensions.indexOf(extensionGroup)];
            }
        });

        return selectedFileType;
    }

    async goToFolder(folder)
    {
        let id = folder.dataset.id;

        // The root folder is displayed
        if(id === "null") {
            return;
        }

        if(folder.id === "_actual-folder") {
            id += "-parent";
        }

        const result = await axios.get(`/application/files/get-folder-content/${id}`);

        document.querySelector("#_actual-folder").dataset.id = result.data.id;

        this.clearFilesContainer();
        result.data.files.forEach(file => {
            this.addFile(file.id, file.type, file.name);
        });
    }

    clearFilesContainer()
    {
        this.filesContainer.querySelectorAll("._file").forEach(file => {
            if(file.id !== "_file-template") {
                file.parentNode.removeChild(file);
            }
        });
    }

    addFile(id, type, name)
    {
        const newFile = document.querySelector("#_file-template").cloneNode(true);
        newFile.classList.remove("hide");
        newFile.id = "";

        const nameElement = newFile.querySelector("._name");

        newFile.dataset.id = id;
        newFile.querySelector("._type i").classList.add(`${type}-i`);
        nameElement.textContent = name;
        newFile.querySelectorAll("._action").forEach(item => item.dataset.id = id);

        const form = newFile.querySelector("._rename-file-form");
        this.initForms(form);

        if(type === "folder") {
            nameElement.classList.add("._go-to-folder");
            this.handleGoToFolder(nameElement);

            // Files can't be downloaded
            newFile.querySelector("._download-container").classList.add("hide");
        }

        this.handleOpenMoveFileForm(newFile.querySelector("._move-file"));
        this.handleOpenRenameFileForm(newFile.querySelector("._rename-file"));
        this.handleDeleteFile(newFile.querySelector("._delete-file"));

        this.filesContainer.appendChild(newFile);
    }

    removeFile(id)
    {
        const file = this.filesContainer.querySelector(`._file[data-id="${id}"]`);

        this.filesContainer.removeChild(file);
    }

    openUploadFileForm()
    {
        const progressesContainer = document.querySelector("#_file-upload-form-progresses-container");

        progressesContainer.querySelectorAll("._progress").forEach(progress => {
            if(progress.id !== "_file_upload-form-progress-template") {
                progressesContainer.removeChild(progress);
            }
        });

        document.querySelector("#_file-upload-form-alerts-container").innerHTML = "";

        this.alertController.showAlert("upload-file");
    }

    openCreateFolderForm()
    {
        document.querySelector("#_create-folder-form-filename").value = "";

        document.querySelector("#_create-folder-form").classList.toggle("hide");
    }

    closeCreateFolderForm()
    {
        document.querySelector("#_create-folder-form").classList.add("hide");
    }

    openRenameFileForm(button)
    {
        const fileRow = button.closest("._file");

        fileRow.classList.add("file-name-typing");
        fileRow.querySelector("._rename-action").classList.remove("hide");

        fileRow.querySelector("._rename-file-form-name").value = fileRow.querySelector("._name").textContent.trim();

        this.handleRenameFile(fileRow);
    }

    closeRenameFileForm(form)
    {
        const fileRow = form.closest("._file");

        fileRow.classList.remove("file-name-typing");
        fileRow.querySelector("._rename-action").classList.add("hide");

        fileRow.querySelector("._name").textContent = form.querySelector("._rename-file-form-name").value;
    }

    openMoveFileForm(button)
    {
        document.querySelector("#_move-file-form-button").dataset.id = button.dataset.id;

        const folderStructureContainer = document.querySelector("#_folder-structure");
        const parentFolderId = document.querySelector("#_actual-folder").dataset.id;

        // Reset last active folder
        folderStructureContainer.querySelectorAll("._input").forEach(input => {
            input.disabled = false;
            input.checked = false;
        });
        folderStructureContainer.querySelectorAll("._label").forEach(label => label.classList.remove("previous-location"));

        // Set active folder
        const input = folderStructureContainer.querySelector(`._input[id="folder-${parentFolderId}"]`);
        const label = folderStructureContainer.querySelector(`._label[for="folder-${parentFolderId}"]`);

        input.disabled = true;
        input.checked = true;
        label.classList.add("previous-location");

        this.alertController.showAlert("move-file");
    }

    closeMoveFileForm()
    {
        this.alertController.hideAlert("move-file");
    }

    createFolder()
    {
        const name = document.querySelector("#_create-folder-form-filename").value;
        const parent = document.querySelector("#_actual-folder").dataset.id;

        const params = new URLSearchParams();
        params.append("name", name);
        params.append("parent", parent);

        axios.post(`/application/files/create-folder`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    this.addFile(data.id, "folder", name);
                    this.addFolderToFolderStructure(data.id, name);

                    this.closeCreateFolderForm();
                } else {
                    alert(data.message);
                }
            });
    }

    addFolderToFolderStructure(id, name)
    {
        const newFolder = document.querySelector("#_folder-structure-template").cloneNode(true);
        newFolder.id = "";
        newFolder.classList.remove("hide");

        newFolder.dataset.id = id;

        const input = newFolder.querySelector("._input");
        input.value = id;
        input.id = `folder-${id}`;

        const label = newFolder.querySelector("._label");
        label.setAttribute("for", `folder-${id}`);
        label.textContent = name;

        const folderStructureContainer = document.querySelector("#_folder-structure");
        const parentFolderId = document.querySelector("#_actual-folder").dataset.id;

        let parentStructureFolder;
        if(parentFolderId !== "null") {
            parentStructureFolder = folderStructureContainer.querySelector(`._folder[data-id="${parentFolderId}"]`);
        } else {
            // Folder is in root folder
            parentStructureFolder = folderStructureContainer;
        }

        parentStructureFolder.appendChild(newFolder);
    }

    removeFolderFromFolderStructure(id)
    {
        const folderStructureContainer = document.querySelector("#_folder-structure");
        const folderForRemoving = folderStructureContainer.querySelector(`._folder[data-id="${id}"]`);

        folderStructureContainer.removeChild(folderForRemoving);
    }

    deleteFile(button)
    {
        const id = button.dataset.id;

        const params = new URLSearchParams();
        params.append("file", id);

        axios.post(`/application/files/delete`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    this.removeFile(id);
                    this.removeFolderFromFolderStructure(id);
                } else {
                    alert(data.message);
                }
            });
    }

    moveFile()
    {
        const id = document.querySelector("#_move-file-form-button").dataset.id;
        const parent = document.querySelector("._move-file-form-parent:checked").value;

        const params = new URLSearchParams();
        params.append("file", id);
        params.append("parent", parent);

        axios.post(`/application/files/move`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    this.removeFile(id);
                    this.closeMoveFileForm();
                } else {
                    // Show alert message
                    const alert = document.createElement("p");
                    alert.textContent = data.message;
                    alert.classList.add("form-alert");
                    alert.classList.add("negative");

                    const formAlerts = document.querySelector("#_move-file-form-alerts");
                    formAlerts.innerHTML = "";
                    formAlerts.appendChild(alert);
                }
            });
    }

    renameFile(form)
    {
        const id = form.querySelector("._rename-file-form-button").dataset.id;
        const name = form.querySelector("._rename-file-form-name").value;

        const params = new URLSearchParams();
        params.append("file", id);
        params.append("name", name);

        axios.post(`/application/files/rename`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    this.closeRenameFileForm(form);
                } else {
                    alert(data.message);
                }
            });
    }
}