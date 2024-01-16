const dayLinePattern = $(`<div class="dayLine"></div>`);
const dayPattern = $(`<div class="day list-group"></div>`)
const headerPattern = $(`<div class="header"></div>`);

function isString(value) {
    return typeof value === 'string';
}

String.isString = isString;

/**
 *
 * @param date {Date}
 * @param lessons {Object}
 * @param isMagistracy
 * @returns {*|jQuery|HTMLElement}
 */
function generateDay(date, lessons, isMagistracy = false) {
    const $lessonPattern = $(`<div class="lesson list-group-item"></div>`)
    const dayView = dayPattern.clone()
    dayView.attr({
        "data-day": date.getDayName(),
        "data-day-number": date.getDate(),
        "data-date": date.toLocaleDateString(),
        "title": date.toLocaleDateString()
    })
    if (lessons !== undefined) {
        const sessionLessons = lessons.filter(lesson => lesson.isSession).sort(function (lesson1, lesson2) {
            var time1 = new Date();
            var time2 = new Date();
            time1.setHours(lesson1.TimeStart.split(":")[0], lesson1.TimeStart.split(":")[1], 0);
            time2.setHours(lesson2.TimeStart.split(":")[0], lesson2.TimeStart.split(":")[1], 0);
            return time1 > time2;
        })
        for (const sessionLesson of sessionLessons) {
            console.log(sessionLesson)
            $lessonPattern.clone().addClass('session-part')
                .attr("data-lesson-index", "")
                .html(`<span>${sessionLesson.Coords.room.index}</span>`)
                .attr('title', sessionLesson.Type)
                .appendTo(dayView)
        }
        for (let i = 1; i <= (isMagistracy ? 7 : 5); i++) {
            const lesson = lessons.find((lesson) => lesson.Number === `${i} пара`);
            if (lesson) {
                if (isString(lesson.Coords.room.index)) {
                    if (lesson.Coords.room.index.toLowerCase() === "спортивный зал") lesson.Coords.room.index = 'спорт. зал'
                }
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
            monthLineView.append(generateDay(
                currentDate,
                lessonsTimetable.filter((lesson) => lesson.dayDate === currentDate.toLocaleDateString()),
                lessonsTimetable.filter((lesson) => {
                    return (lesson.Number === "6 пара") || (lesson.Number === "7 пара")
                }).length > 0,
            ))
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
 * @param container
 * @param month{number}
 * @param defaultYear
 */
function generateGrid(container, month, defaultYear = undefined) {
    const currentYear = (new Date()).getFullYear()
    if (lessonsTimetable.length === 0) {
        container.append(`<h2 class="text-primary text-center mt-4">Нет пар</h2>`)
    } else {
        const monthGrid = container.append(generateHeaderLine())
        let currentDate = new Date(defaultYear ?? currentYear, month, 1)
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
}
