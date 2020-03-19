class WeekChanger
{
    constructor(week, previousButton, nextButton, afterChange)
    {
        this.week = week;
        this.previousButton = previousButton;
        this.nextButton = nextButton;

        this.initWeek();
        this.handleWeekChange(afterChange);
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

    handleWeekChange(action)
    {
        this.previousButton.addEventListener("click", _ => {
            this.setPreviousWeek();

            action();
        });
        this.nextButton.addEventListener("click", _ => {
            this.setNextWeek();

            action();
        });
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
}