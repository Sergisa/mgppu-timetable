const dayLinePattern = $(`<div class="dayLine"></div>`);
const dayPattern = $(`<div class="day list-group"></div>`)
const headerPattern = $(`<div class="header"></div>`);
const $lessonPattern = $(`<div class="lesson list-group-item"></div>`)
const $lessonRoomsWrapper = $(`<div class="lesson-rooms-wrapper"></div>`)

function generateLesson(lessons, index, mode, splitLesson) {
    const lessonView = $lessonPattern.clone().attr("data-lesson-index", index)
    lessonView.append($lessonRoomsWrapper.clone())
    if (lessons.length > 0) {

        let currentLessons = lessons
        if (mode === "professors") {
            currentLessons = _(currentLessons).uniqBy('Teacher.name').sortBy('Teacher.name').value()
        } else if (mode === 'groups') {
            currentLessons = _(currentLessons.extrude('Group')).uniqBy('Group.name').sortBy('Group.name').value()
        } else {
            currentLessons = _(currentLessons)
                .uniqWith([ 'Teacher.name', 'Group.name' ])
                .sortBy('Room')
                .value()
                .map(function (lessonLookElement, index, lessons) {
                    lessonLookElement.error = (lessons.filter(lessonElement => {
                        return lessonLookElement.Room === lessonElement.Room;
                    }).length > 1);
                    return lessonLookElement
                })
        }
        for (const lesson of currentLessons) {
            let sign = "";
            if (lesson.Coords.room.index.toLowerCase() === "спортивный зал") lesson.Coords.room.index = 'спорт. зал'
            if (mode === "professors") sign = lesson.Teacher.name;
            else if (mode === 'groups') sign = lesson.Group.name;
            else sign = lesson.Coords.room.index;
            lessonView.find('.lesson-rooms-wrapper')
                .append(`<span class="room ${(lesson.error && splitLesson && (mode === "rooms")) ? "error" : ""}">${sign}
                <div class="info">
                    <p class="teacher-name">${lesson.Teacher.name}</p>
                    <p class="department-name"><b>Факультет:</b> ${lesson.Department.name}</p>
                    <p class="discipline-name lead">${lesson.Discipline}</p>
                </div>
                </span>`)
        }
    } else {
        lessonView.attr("data-lesson-index", index).addClass('empty')
    }
    return lessonView
}

/**
 *
 * @param date {Date}
 * @param lessons {Object}
 * @param isMagistracy
 * @param mode
 * @param splitLessons
 * @returns {*|jQuery|HTMLElement}
 */
function generateDayRooms(date, lessons, isMagistracy = false, mode, splitLessons = true) {
    const dayView = dayPattern.clone()
    dayView.attr({
        "data-day": date.getDayName(),
        "data-day-number": date.getDate(),
        "data-date": date.toLocaleDateString(),
        "title": date.toLocaleDateString()
    })
    if (lessons !== undefined) {
        if (lessons.length > 0) {
            if (splitLessons) {
                for (let i = 1; i <= (isMagistracy ? 7 : 5); i++) {
                    const disciplinesForCurrentLessonNum = lessons
                        .filter((lesson) => lesson.Number === `${i} пара`)
                        .extrude('Group')
                    dayView.append(generateLesson(disciplinesForCurrentLessonNum, i, mode, splitLessons));
                }
            } else {
                dayView.append(generateLesson(lessons, null, mode, splitLessons));
            }
        }
    }
    return dayView
}

/**
 *
 * @returns {*|jQuery|HTMLElement}
 * @param currentDate
 * @param mode
 * @param splitLessons
 */
function generateDayLines(currentDate, mode, splitLessons = true) {
    const dayLines = $(`<div class="day-lines-container"></div>`)
    const clonedLessons = lessonsTimetable
    while (true) {
        if (currentDate.getDayName() !== 'Воскресенье') {
            dayLines.append(generateDayRooms(
                currentDate,
                clonedLessons.filter((lesson) => lesson.dayDate === currentDate.toLocaleDateString()),
                clonedLessons.some((lesson) => {
                    return (lesson.Number === "6 пара") || (lesson.Number === "7 пара")
                }),
                mode,
                splitLessons
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
 * @param mode
 * @param splitLessons
 */
function generateLines(container, month, year, mode, splitLessons = true) {
    if (lessonsTimetable.length === 0) {
        container.append(`<h2 class="text-primary text-center mt-4">Нет пар</h2>`)
    } else {
        let currentDate = new Date(year, month, 1)
        container.append(generateDayLines(currentDate, mode, splitLessons))
    }
}
