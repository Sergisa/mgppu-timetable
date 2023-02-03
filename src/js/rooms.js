const dayLinePattern = $(`<div class="dayLine"></div>`);
const dayPattern = $(`<div class="day list-group"></div>`)
const headerPattern = $(`<div class="header"></div>`);

/**
 *
 * @param date {Date}
 * @param lessons {Object}
 * @param isMagistracy
 * @returns {*|jQuery|HTMLElement}
 */
function generateDayRooms(date, lessons, isMagistracy = false) {
    const $lessonPattern = $(`<div class="lesson list-group-item"></div>`)
    const dayView = dayPattern.clone()
    dayView.attr({
        "data-day": date.getDayName(),
        "data-day-number": date.getDate(),
        "data-date": date.toLocaleDateString(),
        "title": date.toLocaleDateString()
    })
    if (lessons !== undefined) {
        for (let i = 1; i <= (isMagistracy ? 7 : 5); i++) {
            const disciplinesForCurrentLessonNum = lessons
                .filter((lesson) => lesson.Number === `${i} пара`)
                .sort(function (lesson1, lesson2) {
                    return (lesson1.Coords.room.index > lesson2.Coords.room.index) ? 1 : -1
                })
            // console.log(`FILTERED ${i} ${date.toLocaleDateString()}`, disciplinesForCurrentLessonNum)
            if (disciplinesForCurrentLessonNum) {
                const currentLesson = $lessonPattern.clone().attr("data-lesson-index", i)

                for (const lesson of _(disciplinesForCurrentLessonNum).uniqBy('Coords.room.index')) {
                    if (lesson.Coords.room.index.toLowerCase() === "спортивный зал") lesson.Coords.room.index = 'спорт. зал'
                    currentLesson.append(`<span>${lesson.Coords.room.index}</span>`).appendTo(dayView)
                }
            } else {
                dayView.append($lessonPattern.clone().attr("data-lesson-index", i).addClass('empty'))
            }
        }
    }
    return $(`<div class="day-wrapper"></div>`).append(dayView);
}

/**
 *
 * @returns {*|jQuery|HTMLElement}
 * @param currentDate
 */
function generateDayLines(currentDate) {
    const dayLines = $(`<div class="day-lines-container"></div>`)
    while (true) {
        if (currentDate.getDayName() !== 'Воскресенье') {
            dayLines.append(generateDayRooms(
                currentDate,
                lessonsTimetable.filter((lesson) => lesson.dayDate === currentDate.toLocaleDateString()),
                lessonsTimetable.filter((lesson) => {
                    return (lesson.Number === "6 пара") || (lesson.Number === "7 пара")
                }).length > 0,
            ))
        }
        if (currentDate.hasNextInMonth()) {
            currentDate.next()
        } else {
            break
        }
    }
    return dayLines
}

/**
 *
 * @param container
 * @param month{number}
 * @param year
 */
function generateLines(container, month, year) {
    if (lessonsTimetable.length === 0) {
        container.append(`<h2 class="text-primary text-center mt-4">Нет пар</h2>`)
    } else {
        let currentDate = new Date(year, month, 1)
        container.append(generateDayLines(currentDate))
    }
}
