const defaultMonth = 9;

/**
 *
 * @param date {Date}
 * @param lessons {Object}
 * @returns {*|jQuery|HTMLElement}
 */
function generateDay(date, lessons) {
    let dayPattern = $(`<div class="day" data-day="${calendarWeekDays[date.getDay() - 1]}" data-date="${date.toLocaleDateString()}"></div>`)
    for (const lesson of lessons) {
        dayPattern.append(`<div class="${lesson === null ? "" : "lesson"}">${lesson === null ? "" : lesson}</div>`)
    }
    return dayPattern;
}

/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
function generateHeaderLine() {
    const headerPattern = $(`<div class="header"></div>`);
    for (let day of calendarWeekDays) {
        headerPattern.append(`<span>${day}</span>`)
    }
    return headerPattern
}

/**
 *
 * @returns {*|jQuery|HTMLElement}
 * @param currentDate
 */
function generateDaysLine(currentDate) {
    let dayLinePattern = $(`<div class="dayLine"></div>`);
    while (true) {
        console.log("DATE BY WEEK", currentDate.toLocaleDateString())
        if (currentDate.getDayName() !== 'Воскресенье') dayLinePattern.append(generateDay(currentDate, [312]))//Проверяем что выводим не воскресенье
        if (currentDate.hasNextInMonth()) {
            if (currentDate.hasNextInWeek()) currentDate.next()
            else break
        } else {
            break
        }
    }
    return dayLinePattern
}

/**
 *
 * @param month{number}
 */
function generateGrid(month) {
    const monthGrid = $('#monthGrid').append(generateHeaderLine())
    let currentDate = new Date(2022, (defaultMonth ?? month) - 1, 1)
    while (true) {
        console.info("DATE BY MONTH", currentDate.toLocaleDateString())
        monthGrid.append(generateDaysLine(currentDate))
        if (currentDate.hasNextInMonth()) {
            currentDate.next()
        } else {
            break
        }
    }
}
