class SummaryController
{
    constructor()
    {
        this.week = document.querySelector("#_actual-week");
        this.privateReminderContainer = document.querySelector("#_private-reminders-container");

        this.initWeek();

        this.handleTakeUp();
        this.handleWeekChange();
    }

    initWeek()
    {
        const now = new Date();

        if(this.week.dataset.weekNumber === undefined) {
            this.week.dataset.weekNumber = now.getWeek().toString();
        }

        if(this.week.dataset.year === undefined) {
            this.week.dataset.year = now.getFullYear().toString();
        }
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

    handleWeekChange()
    {
        document.querySelector("#_previous-week-b").addEventListener("click", _ => {
            this.setPreviousWeek();

            this.clearReminders();
            this.updatePrivateReminders();
        });
        document.querySelector("#_next-week-b").addEventListener("click", _ => {
            this.setNextWeek();

            this.clearReminders();
            this.updatePrivateReminders();
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
        newReminder.querySelector("._subject").textContent = subject;
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

    getWeekName(weekNumber)
    {
        // For today
        let now = new Date();
        const day = (now.getDay() !== 0 ? now.getDay() : 7) - 1;
        const weekNumberDifference = weekNumber - now.getWeek(); // next week -> +1, previous week -> -1

        // For weekNumber parameter
        now.setDate(now.getDate() - day); // Move day to monday
        now.setDate(now.getDate() + (7 * weekNumberDifference)); // Move date by week
        const mondayDate = now.getDate();
        const mondayMoth = now.getMonth() + 1;

        now.setDate(now.getDate() + 6);
        const sundayDate = now.getDate();
        const sundayMonth = now.getMonth() + 1;

        now = new Date(); // move back to today
        if(weekNumber === now.getWeek()) {
            return "Tento týden";
        } else if(weekNumber === (now.getWeek() + 1)) {
            return "Příští týden";
        } else if(weekNumber === (now.getWeek() - 1)) {
            return "Minulý týden";
        } else {
            return `${mondayDate}. ${mondayMoth}.-${sundayDate}. ${sundayMonth}.`;
        }
    }

    setPreviousWeek()
    {
        let movedWeekNumber = parseInt(this.week.dataset.weekNumber) - 1;

        if(movedWeekNumber < 1) {
            movedWeekNumber = 53; // Last in previous year
            this.week.dataset.year = (parseInt(this.week.dataset.year) - 1).toString();
        }

        this.week.textContent = this.getWeekName(movedWeekNumber);
        this.week.dataset.weekNumber = movedWeekNumber.toString();
    }

    setNextWeek()
    {
        let movedWeekNumber = parseInt(this.week.dataset.weekNumber) + 1;

        if(movedWeekNumber > 53) {
            movedWeekNumber = 1; // First in next year
            this.week.dataset.year = (parseInt(this.week.dataset.year) + 1).toString();
        }

        this.week.textContent = this.getWeekName(movedWeekNumber);
        this.week.dataset.weekNumber = movedWeekNumber.toString();
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

new SummaryController();