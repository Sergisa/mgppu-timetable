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

function generateHeaderLine() {
    const headerPattern = $(`<div class="header"></div>`);
    for (let day of calendarWeekDays) {
        headerPattern.append(`<span>${day}</span>`)
    }
    return headerPattern
}

/**
 *
 * @param startDate {Date}
 * @returns {*|jQuery|HTMLElement}
 */
function generateDaysLine(startDate) {
    let currentDate = startDate
    let dayLinePattern = $(`<div class="dayLine"></div>`);
    while (true) {
        console.log("DATE BY WEEK", currentDate.toLocaleDateString()) // FIXME: 30.09 в цикле
        if (currentDate.getDayName() !== 'Воскресенье') { //Проверяем что выводим не воскресенье
            dayLinePattern.append(generateDay(currentDate, [312]))
            if (currentDate !== null && currentDate.hasNextInWeek("Суббота")) {
                currentDate = currentDate.next()
            } else {
                break
            }
        }else{
            currentDate = currentDate.next()
            break
        }

    }
    return dayLinePattern
}

function generateGrid(month) {
    const monthGrid = $('#monthGrid').append(generateHeaderLine())
    let currentDate = new Date(2022, (defaultMonth ?? month) - 1, 1)
    while (true) {//FIXME: пропадает последнее чило месяца
        console.info("DATE BY MONTH", currentDate.toLocaleDateString())
        monthGrid.append(generateDaysLine(currentDate))
        if (currentDate.hasNextInMonth()) {
            currentDate.next()
        } else {
            break
        }
    }
}
