class SummaryController
{
    constructor()
    {
        this.week = document.querySelector("#_actual-week");
        this.privateReminderContainer = document.querySelector("#_private-reminders-container");

        this.initWeekChanger();

        this.handleTakeUp();
    }

    initWeekChanger()
    {
        const previousButton = document.querySelector("#_previous-week-b");
        const nextButton = document.querySelector("#_next-week-b");

        new WeekChanger(this.week, previousButton, nextButton, _ => {
            this.clearReminders();
            this.updatePrivateReminders();
        });
    }

    handleTakeUp()
    {
        document.querySelectorAll("._take-up").forEach(takeUpButton => {
            takeUpButton.addEventListener("click", _ => {
                const type = takeUpButton.dataset.type;
                const id = takeUpButton.dataset.for;

                this.sendTakeUpAjaxToServer(type, id);

                if(type === "reminder") {
                    this.takeUpReminder(id);
                } else if(type === "note") {
                    this.takeUpNote(id);
                }

                this.setAsTakenUp(takeUpButton);
            });
        });
    }

    takeUpReminder(id)
    {
        const sharedReminderContainer = document.querySelector("#_shared-reminders-container");
        const reminder = sharedReminderContainer.querySelector(`._take-up-item[data-id="${id}"]`);

        const when = reminder.querySelector("._when").textContent;
        const subject = reminder.querySelector("._subject").textContent;
        const type = reminder.querySelector("._type i").classList.item(0).replace("-i", "");
        const content = reminder.querySelector("._content").textContent;

        this.addPrivateReminder(when, subject, type, content);
    }

    addPrivateReminder(when, subject, type, content)
    {
        const newReminder = document.querySelector("#_private-reminder-template").cloneNode(true);
        newReminder.classList.remove("hide");
        newReminder.id = "";

        const typeElement = document.createElement("i");
        typeElement.classList.add(`${type}-i`);

        newReminder.querySelector("._when").textContent = when;
        newReminder.querySelector("._subject").textContent = (type !== "school-event" ? subject : "");
        newReminder.querySelector("._type").appendChild(typeElement);
        newReminder.querySelector("._content").textContent = content;

        this.privateReminderContainer.appendChild(newReminder);
    }

    takeUpNote(id)
    {
        const sharedNoteContainer = document.querySelector("#_shared-notes-container");
        const note = sharedNoteContainer.querySelector(`._take-up-item[data-id="${id}"]`);

        const content = note.querySelector("._content").textContent;

        this.addPrivateNote(content)
    }

    addPrivateNote(content)
    {
        const privateNoteContainer = document.querySelector("#_private-notes-container");
        const newNote = document.querySelector("#_private-note-template").cloneNode(true);
        newNote.classList.remove("hide");
        newNote.id = "";

        newNote.querySelector("._content").textContent = content;

        privateNoteContainer.appendChild(newNote);
    }

    setAsTakenUp(takeUpButton)
    {
        const tookUpInfo = document.createElement("i");
        tookUpInfo.classList.add("icon");
        tookUpInfo.classList.add("done-i");

        takeUpButton.parentNode.parentNode.classList.add("done");
        takeUpButton.parentNode.querySelectorAll("._take-up").forEach(item => item.classList.add("hide"));
        takeUpButton.parentNode.appendChild(tookUpInfo);
    }

    async sendTakeUpAjaxToServer(type, id)
    {
        try {
            await axios.head(`/application/reminders-and-notes/take-up/${type}/${id}`);
        } catch(error) {
        }
    }

    async updatePrivateReminders()
    {
        try {
            const weekNumber = this.week.dataset.weekNumber;
            const year = this.week.dataset.year;

            const reminders = await axios.get(`/application/reminders-and-notes/get-reminders/${year}/${weekNumber}`);

            reminders.data.forEach(reminder => {
                this.addPrivateReminder(reminder.when, reminder.subject, reminder.type, reminder.content);
            });
        } catch(error) {
        }
    }

    clearReminders() {
        this.privateReminderContainer.querySelectorAll("div").forEach(item => {
            if(item.id === "_private-reminder-template") {
                return;
            }

            this.privateReminderContainer.removeChild(item);
        });
    }
}