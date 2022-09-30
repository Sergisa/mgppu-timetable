const dayLinePattern = $(`<div class="dayLine"></div>`);
const dayPattern = $(`<div class="day list-group"></div>`)
const headerPattern = $(`<div class="header"></div>`);

/**
 *
 * @param date {Date}
 * @param lessons {Object}
 * @returns {*|jQuery|HTMLElement}
 */
function generateDay(date, lessons) {
    const $lessonPattern = $(`<div class="lesson list-group-item"></div>`)
    const dayView = dayPattern.clone()
    dayView.attr({
        "data-day": date.getDayName(),
        "data-day-number": date.getDate(),
        "data-date": date.toLocaleDateString(),
        "title": date.toLocaleDateString()
    })
    if (lessons !== undefined) {
        for (let i = 1; i <= 5; i++) {
            const lesson = lessons.find((lesson) => lesson.Number === `${i} пара`);
            if (lesson) {
                if (lesson.Coords.room.index.toLowerCase() === "спортивный зал") lesson.Coords.room.index = 'спорт. зал'
                $lessonPattern.clone().html(`<span>${lesson.Coords.room.index}</span>`).attr("data-lesson-index", i).appendTo(dayView)
            } else {
                dayView.append($lessonPattern.clone().addClass('empty'))
            }
        }
    }
    return $(`<div class="day-wrapper"></div>`).append(dayView);
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
    let currentDate = new Date(2022, month, 1)
    const $dayGrid = $(`<div class="day-grid"></div>`).appendTo(monthGrid)
    while (true) {
        $dayGrid.append(generateDaysLine(currentDate))
        if (currentDate.hasNextInMonth()) {
            currentDate.next()
        } else {
            break
        }
    }
}
