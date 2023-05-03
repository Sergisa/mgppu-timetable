const dayLinePattern = $(`<div class="dayLine"></div>`);
const dayPattern = $(`<div class="day list-group"></div>`)
const headerPattern = $(`<div class="header"></div>`);
const $lessonPattern = $(`<div class="lesson list-group-item"></div>`)
const $lessonRoomsWrapper = $(`<div class="lesson-rooms-wrapper"></div>`)

function generateLesson(lessons, index, mode, splitLessons = true) {
    const lessonView = $lessonPattern.clone().attr("data-lesson-index", index)
    lessonView.append($lessonRoomsWrapper.clone())
    if (lessons.length > 0) {
        for (const lesson of lessons) {
            let sign = lesson.Coords.room.index;
            if (lesson.Coords.room.index.toLowerCase() === "спортивный зал") lesson.Coords.room.index = 'спорт. зал'

            if (mode === "professors") {
                sign = lesson.Teacher.name;
            } else if (mode === 'groups') {
                if (!lesson.Group) console.warn("UNDEFINED", lesson)
                sign = lesson.Group.name;
            } else {
                sign = lesson.Coords.room.index;
            }
            lessonView.find('.lesson-rooms-wrapper')
                .append(`<span class="room">${sign}
                <div class="info">
                    <p class="teacher-name">${lesson.Teacher.name}</p>
                    <p class="department-name"><b>Факультет:</b> ${lesson.Department.name}</p>
                    <p class="discipline-name lead">${lesson.Discipline}</p>
                </div>
                </span>`);
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
                        .sort(function (lesson1, lesson2) {
                            return (lesson1.Coords.room.index > lesson2.Coords.room.index) ? 1 : -1
                        })
                    dayView.append(generateLesson(disciplinesForCurrentLessonNum, i, mode, splitLessons));
                }
            } else {
                let disciplinesForCurrentLessonNum = lessons
                if (mode === "professors") {
                    disciplinesForCurrentLessonNum = _(disciplinesForCurrentLessonNum)
                        .uniqBy('Teacher.name')
                        .sortBy('Teacher.name')
                        .value()
                }
                else if (mode === 'rooms') {
                    disciplinesForCurrentLessonNum = _(disciplinesForCurrentLessonNum)
                        .uniqBy('Room')
                        .sortBy('Room')
                        .value()
                }
                else {
                    disciplinesForCurrentLessonNum = _(disciplinesForCurrentLessonNum.extrude('Group'))
                        .uniqBy('Group.name')
                        .sortBy('Group.name')
                        .value()
                }
                dayView.append(generateLesson(disciplinesForCurrentLessonNum, null, mode, splitLessons));
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
    while (true) {
        if (currentDate.getDayName() !== 'Воскресенье') {
            dayLines.append(generateDayRooms(
                currentDate,
                lessonsTimetable.filter((lesson) => lesson.dayDate === currentDate.toLocaleDateString()),
                lessonsTimetable.some((lesson) => {
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
