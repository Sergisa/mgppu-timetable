const defaultMonth = new Date().getMonth();
const dayLinePattern = $(`<div class="dayLine"></div>`);
const dayPattern = $(`<div class="day"></div>`)
const headerPattern = $(`<div class="header"></div>`);

/**
 *
 * @param date {Date}
 * @param lessons {Object}
 * @returns {*|jQuery|HTMLElement}
 */
function generateDay(date, lessons) {
    //console.log(date, lessons)
    const dayView = dayPattern.clone()
    dayView.attr({
        "data-day": date.getDayName(),
        "data-date": date.toLocaleDateString()
    })
    if (lessons !== undefined) {
        for (const lesson of lessons) {
            const $lessonPattern = $(`<div class="lesson">${lesson === null ? "" : lesson.Coords.room.index}</div>`)
            $lessonPattern.attr({
                "data-lesson-index": lesson.Number.matchAll(/(\d) *пара/gui).next().value[1],
            })
            dayView.append($lessonPattern)
        }
    }
    return dayView;
}

/**
 *
 * @returns {*|jQuery|HTMLElement}
 */
function generateHeaderLine() {
    let headerVew = headerPattern.clone()
    for (let day of calendarWeekDays) {
        headerVew.append(`<span>${day}</span>`)
    }
    return headerVew
}

/**
 *
 * @returns {*|jQuery|HTMLElement}
 * @param currentDate
 */
function generateDaysLine(currentDate) {
    const monthLineView = dayLinePattern.clone()

    while (true) {
        //console.log("DATE BY WEEK", currentDate.toLocaleDateString())
        if (currentDate.getDayName() !== 'Воскресенье') {
            monthLineView.append(generateDay(currentDate, lessonsTimetable[currentDate.toLocaleDateString()]))
        }
        if (currentDate.hasNextInMonth()) {
            if (currentDate.hasNextInWeek()) currentDate.next()
            else break
        } else {
            break
        }
    }
    return monthLineView
}

/**
 *
 * @param month{number}
 */
function generateGrid(month) {
    const monthGrid = $('#monthGrid').append(generateHeaderLine())
    let currentDate = new Date(2022, defaultMonth ?? month, 1)
    while (true) {
        monthGrid.append(generateDaysLine(currentDate))
        if (currentDate.hasNextInMonth()) {
            currentDate.next()
        } else {
            break
        }
    }
}
