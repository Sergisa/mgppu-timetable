function generateDay(dayName, lessons) {
    let dayPattern = $(`<div class="day" data-day="${dayName}"></div>`)
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

function generateDaysLine(shiftIndex) {
    let dayLinePattern = $(`<div class="dayLine"></div>`);
    for (let i = (shiftIndex === undefined) ? 0 : shiftIndex; i < calendarWeekDays.length; i++) {
        dayLinePattern.append(generateDay(calendarWeekDays[i], [312]))
    }
    return dayLinePattern
}

function generateGrid(month) {
    $(`<div class="month row"></div>`)
        .append(generateHeaderLine())
        .append(generateDaysLine(3))
        .append(generateDaysLine())
        .append(generateDaysLine())
        .append(generateDaysLine())
        .appendTo($('#monthGrid'))
}
