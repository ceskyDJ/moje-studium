class NotesAndRemindersController
{
    constructor(alertController, formController)
    {
        this.week = document.querySelector("#_actual-week");
        this.remindersContainer = document.querySelector("#_reminders-container");
        this.notesContainer = document.querySelector("#_notes-container");
        this.editReminderId = document.querySelector("#_edit-reminder-id");
        this.editNoteId = document.querySelector("#_edit-note-id");
        this.reminderFormHeadings = document.querySelectorAll("._reminder-form-heading");
        this.noteFormHeadings = document.querySelectorAll("._note-form-heading");
        this.reminderFormButtons = document.querySelectorAll("._reminder-form-button");
        this.noteFormButtons = document.querySelectorAll("._note-form-button");
        this.reminderFormAlertsContainer = document.querySelector("#_reminder-form-alerts");
        this.noteFormAlertsContainer = document.querySelector("#_note-form-alerts");

        this.initWeekChanger();
        this.alertController = alertController;
        this.formController = formController;
        this.updateCalendars();

        this.handleOpenAddReminderForm();
        this.handleOpenEditReminderForm();
        this.handleDeleteReminder();
        this.handleOpenAddNoteForm();
        this.handleOpenEditNoteForm();
        this.handleDeleteNote();
        this.handleAddReminder();
        this.handleEditReminder();
        this.handleAddNote();
        this.handleEditNote();
    }

    initWeekChanger()
    {
        const previousButton = document.querySelector("#_previous-week-b");
        const nextButton = document.querySelector("#_next-week-b");

        this.weekChanger = new WeekChanger(this.week, previousButton, nextButton, _ => {
            this.clearReminders();
            this.updateReminders()
                .then(_ => this.updateCalendars());
        });
    }

    handleOpenAddReminderForm()
    {
        document.querySelector("#_add-reminder").addEventListener("click", _ => {
            this.clearRemindersForm();
            this.alertController.showAlert("add-edit-reminder");
            this.setActiveReminderAction("add");
        });
    }

    handleAddReminder()
    {
        document.querySelector("#_reminder-form-add").addEventListener("click", _ => this.addOrEditReminder("add"));
    }

    handleOpenEditReminderForm(element)
    {
        if(element === undefined) {
            document.querySelectorAll("._edit-reminder").forEach(item => {
                item.addEventListener("click", _ => this.openEditReminderForm(item));
            });
        } else {
            element.addEventListener("click", _ => this.openEditReminderForm(element));
        }
    }

    handleEditReminder()
    {
        document.querySelector("#_reminder-form-edit").addEventListener("click", _ => this.addOrEditReminder("edit"));
    }

    handleDeleteReminder(element)
    {
        if(element === undefined) {
            document.querySelectorAll("._delete-reminder").forEach(item => {
                item.addEventListener("click", _ => {
                    const content = item.closest("._reminder").querySelector("._content");

                    this.deleteReminder(item.dataset.id, content.textContent);
                });
            });
        } else {
            element.addEventListener("click", _ => {
                const content = element.closest("._reminder").querySelector("._content");

                this.deleteReminder(element.dataset.id, content.textContent);
            });
        }
    }

    handleOpenAddNoteForm()
    {
        document.querySelector("#_add-note").addEventListener("click", _ => {
            this.clearNotesForm();
            this.alertController.showAlert("add-edit-note");
            this.setActiveNoteAction("add");
        });
    }

    handleAddNote()
    {
        document.querySelector("#_note-form-add").addEventListener("click", _ => this.addOrEditNote("add"));
    }

    handleOpenEditNoteForm(element)
    {
        if(element === undefined) {
            document.querySelectorAll("._edit-note").forEach(item => {
                item.addEventListener("click", _ => this.openEditNoteForm(item));
            });
        } else {
            element.addEventListener("click", _ => this.openEditNoteForm(element));
        }
    }

    handleEditNote()
    {
        document.querySelector("#_note-form-edit").addEventListener("click", _ => this.addOrEditNote("edit"));
    }

    handleDeleteNote(element)
    {
        if(element === undefined) {
            document.querySelectorAll("._delete-note").forEach(item => {
                item.addEventListener("click", _ => {
                    const content = item.closest("._note").querySelector("._content");

                    this.deleteNote(item.dataset.id, content.textContent);
                });
            });
        } else {
            element.addEventListener("click", _ => {
                const content = element.closest("._note").querySelector("._content");

                this.deleteNote(element.dataset.id, content.textContent);
            });
        }
    }

    openEditReminderForm(item)
    {
        const reminder = item.closest("._reminder");

        // Date as object
        const date = reminder.closest("._day").querySelector("._date").textContent;
        const dateParts = this.getDatePartsFromDate(date);
        const year = this.week.dataset.year;
        const dateObject = Date.parse(`${year}-${dateParts.month}-${dateParts.day}`);

        // Calendar's container
        const dateInput = document.querySelector("#_reminder-form-date");
        const calendarContainer = dateInput.closest("._calendar-container");

        // Subject index
        const subject = reminder.querySelector("._subject").textContent.trim().toUpperCase();
        const subjectSelect = document.querySelector("#_reminder-form-subject");
        let subjectIndex = 0;
        subjectSelect.querySelectorAll("option").forEach(option => {
            if(option.textContent.trim().toUpperCase() === subject) {
                 subjectIndex = option.index;
            }
        });

        // Type index
        const type = reminder.querySelector("._type").className.replace("_type", "").replace("-i", "").trim();
        const typeSelect = document.querySelector("#_reminder-form-type");
        let typeIndex = 0;
        typeSelect.querySelectorAll("option").forEach(option => {
            if(option.value === type) {
                typeIndex = option.index;
            }
        });

        this.formController.setActiveDateInDatepicker(calendarContainer, dateObject);
        dateInput.value = date;
        document.querySelector("#_reminder-form-year").value = year;
        subjectSelect.selectedIndex = subjectIndex;
        typeSelect.selectedIndex = typeIndex;
        this.formController.updateIconChooser(typeSelect);
        document.querySelector("#_reminder-form-content").value = reminder.querySelector("._content").textContent;

        this.alertController.showAlert("add-edit-reminder");
        this.editReminderId.dataset.id = item.dataset.id;
        this.setActiveReminderAction("edit");
    }

    openEditNoteForm(item)
    {
        const note = item.closest("._note");

        document.querySelector("#_note-form-content").value = note.querySelector("._content").textContent;

        this.alertController.showAlert("add-edit-note");
        this.editNoteId.dataset.id = item.dataset.id;
        this.setActiveNoteAction("edit");
    }

    clearReminders()
    {
        this.remindersContainer.querySelectorAll("._reminder").forEach(reminder => reminder.parentNode.removeChild(reminder));
    }

    async updateReminders()
    {
        try {
            const weekNumber = this.week.dataset.weekNumber;
            const year = this.week.dataset.year;

            const result = await axios.get(`/application/reminders-and-notes/get-reminders-in-days/${year}/${weekNumber}`);

            const numberOfUseDays = result.data.useDays;

            let i = 0;
            const dayElements = this.remindersContainer.querySelectorAll("._day");
            result.data.days.forEach(day => {
                const dayElement = dayElements[i++];
                dayElement.dataset.date = day.shortDate;
                dayElement.querySelector("._date").textContent = day.date;

                if(i < numberOfUseDays) {
                    dayElement.classList.remove("hide");
                } else {
                    // Here's no sense to display days without use
                    dayElement.classList.add("hide");

                    return;
                }

                if(day.reminders === undefined) {
                    return;
                }

                day.reminders.forEach(reminder => {
                    this.addReminder(dayElement, reminder.id, reminder.subject, reminder.type, reminder.content);
                });
            });
        } catch(error) {
        }
    }

    addReminder(day, id, subject, type, content)
    {
        const newReminder = document.querySelector("#_reminder-record-template").cloneNode(true);
        newReminder.classList.remove("hide");
        newReminder.id = "";

        newReminder.dataset.id = id;
        newReminder.querySelector("._subject").textContent = (type !== "school-event" ? subject : "");
        newReminder.querySelector("._type").classList.add(`${type}-i`);
        newReminder.querySelector("._content").textContent = content;
        newReminder.querySelectorAll("._action-id").forEach(item => {
            item.dataset.id = id;
        });

        this.handleOpenEditReminderForm(newReminder.querySelector("._edit-reminder"));
        this.handleDeleteReminder(newReminder.querySelector("._delete-reminder"));

        day.querySelector("._reminders").appendChild(newReminder);
    }

    addNote(id, content)
    {
        const newNote = document.querySelector("#_note-template").cloneNode(true);
        newNote.classList.remove("hide");
        newNote.id = "";

        newNote.dataset.id = id;
        newNote.querySelector("._content").textContent = content;
        newNote.querySelectorAll("._action-id").forEach(item => {
            item.dataset.id = id;
        });

        this.handleOpenEditNoteForm(newNote.querySelector("._edit-note"));
        this.handleDeleteNote(newNote.querySelector("._delete-note"));

        this.notesContainer.appendChild(newNote);
    }

    editReminder(id, newSubject, newType, newContent)
    {
        const oldReminder = document.querySelector(`._reminder[data-id="${id}"]`);

        oldReminder.querySelector("._type").className = "";

        oldReminder.querySelector("._subject").textContent = (newType !== "school-event" ? newSubject : "");
        oldReminder.querySelector("._type").classList.add(`${newType}-i`);
        oldReminder.querySelector("._content").textContent = newContent;
    }

    editNote(id, newContent)
    {
        const oldNote = document.querySelector(`._note[data-id="${id}"]`);

        oldNote.querySelector("._content").textContent = newContent;
    }

    showOrHideFormItemByAction(item, action)
    {
        if(item.dataset.action === action) {
            item.classList.remove("hide");
        } else {
            item.classList.add("hide")
        }
    }

    setActiveReminderAction(action)
    {
        this.reminderFormHeadings.forEach(heading => this.showOrHideFormItemByAction(heading, action));
        this.reminderFormButtons.forEach(button => this.showOrHideFormItemByAction(button, action));
    }

    setActiveNoteAction(action)
    {
        this.noteFormHeadings.forEach(heading => this.showOrHideFormItemByAction(heading, action));
        this.noteFormButtons.forEach(button => this.showOrHideFormItemByAction(button, action));
    }

    async deleteReminder(id, content)
    {
        if(confirm(`Opravdu si přeješ odstranit toto upozornění?\n${content}`)) {
            const reminder = document.querySelector(`._reminder[data-id="${id}"]`);
            const parent = reminder.parentNode;
            parent.removeChild(reminder);

            await axios.head(`/application/reminders-and-notes/delete-reminder/${id}`);
        }
    }

    addOrEditReminder(action)
    {
        // Get data
        const date = document.querySelector("#_reminder-form-date").value;
        const year = document.querySelector("#_reminder-form-year").value;
        const subject = document.querySelector("#_reminder-form-subject").value;
        const type = document.querySelector("#_reminder-form-type").value;
        const content = document.querySelector("#_reminder-form-content").value;
        const id = this.editReminderId.dataset.id;

        // Send to server
        const params = new URLSearchParams();
        params.append("date", date);
        params.append("year", year);
        params.append("subject", subject);
        params.append("type", type);
        params.append("content", content);

        if(action === "edit") {
            params.append("id", id);
        }

        axios.post(`/application/reminders-and-notes/${action}-reminder`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    // Hide alert
                    this.alertController.hideAlert("add-edit-reminder");

                    // Add new record to DOM
                    if(action === "add") {
                        const subjectSelect = document.querySelector("#_reminder-form-subject");
                        const subjectShortcut = subjectSelect.querySelectorAll("option")[subjectSelect.selectedIndex].textContent;

                        // Add reminder only when the date is from selected week
                        let day;
                        if((day = this.getDayByDate(date)) !== null) {
                            this.addReminder(day, data.id, subjectShortcut, type, content);
                        }
                    } else {
                        this.editReminder(id, subject, type, content);
                    }
                } else {
                    // Show alert message
                    const alert = document.createElement("p");
                    alert.textContent = data.message;
                    alert.classList.add("negative");

                    this.reminderFormAlertsContainer.innerHTML = "";
                    this.reminderFormAlertsContainer.appendChild(alert);
                }
            });
    }

    addOrEditNote(action)
    {
        // Get data
        const content = document.querySelector("#_note-form-content").value;
        const id = this.editNoteId.dataset.id;

        // Send to server
        const params = new URLSearchParams();
        params.append("content", content);

        if(action === "edit") {
            params.append("id", id);
        }

        axios.post(`/application/reminders-and-notes/${action}-note`, params)
            .then(response => {
                const data = response.data;

                if(data.success === true) {
                    // Hide alert
                    this.alertController.hideAlert("add-edit-note");

                    // Add new record to DOM
                    if(action === "add") {
                        this.addNote(data.id, content);
                    } else {
                        this.editNote(id, content);
                    }
                } else {
                    // Show alert message
                    const alert = document.createElement("p");
                    alert.textContent = data.message;
                    alert.classList.add("negative");

                    this.noteFormAlertsContainer.innerHTML = "";
                    this.noteFormAlertsContainer.appendChild(alert);
                }
            });
    }

    async deleteNote(id, content)
    {
        if(confirm(`Opravdu si přeješ odstranit tuto poznámku?\n${content}`)) {
            const note = document.querySelector(`._note[data-id="${id}"]`);
            const parent = note.parentNode;
            parent.removeChild(note);

            await axios.head(`/application/reminders-and-notes/delete-note/${id}`);
        }
    }

    clearRemindersForm()
    {
        // Date
        const dateInput = document.querySelector("#_reminder-form-date");
        const calendarContainer = dateInput.closest("._calendar-container");

        // Type
        const typeSelect = document.querySelector("#_reminder-form-type");

        dateInput.value = "";
        this.formController.unselectValueInDatepicker(calendarContainer);
        document.querySelector("#_reminder-form-subject").selectedIndex = 0;
        typeSelect.selectedIndex = 0;
        this.formController.updateIconChooser(typeSelect);
        document.querySelector("#_reminder-form-content").value = "";
    }

    clearNotesForm()
    {
        document.querySelector("#_note-form-content").value = "";
    }

    getDayByDate(date)
    {
        const paramDateAsParts = this.getDatePartsFromDate(date);

        const shortDate = paramDateAsParts.day + "." + paramDateAsParts.month + ".";

        return this.remindersContainer.querySelector(`._day[data-date="${shortDate}"]`);
    }

    getDatePartsFromDate(date)
    {
        const cleanDate = date.substr(3);
        const dateParts = cleanDate.split(" ");

        return {
            day: parseInt(dateParts[0].replace(".", "")),
            month: parseInt(dateParts[1].replace(".", ""))
        };
    }

    updateCalendars()
    {
        const date = document.querySelector("._day ._date").textContent;
        const dateParts = this.getDatePartsFromDate(date);
        const year = this.week.dataset.year;
        const dateObject = new Date(Date.parse(`${year}-${dateParts.month}-${dateParts.day}`));

        document.querySelectorAll("._calendar-container").forEach(calendarContainer => {
            this.formController.setMinDateInDatepicker(calendarContainer, dateObject);
        })
    }
}