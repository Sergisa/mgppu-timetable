let calendarWeekDays = [
    'Понедельник',
    'Вторник',
    'Среда',
    'Четверг',
    'Пятница',
    'Субота',
    //'Воскресенье'
]
Date.prototype.getDayIndex = function () {
    let weekDays = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ]
    return weekDays.indexOf(this.toString().split(' ')[0])
}
Date.prototype.getDayName = function () {
    let weekDays = [
        'Воскресенье',
        'Понедельник',
        'Вторник',
        'Среда',
        'Четверг',
        'Пятница',
        'Суббота',
    ]
    return weekDays[this.getDay()]
}

function parseDate(dateString) {
    const pattern = /(\d{2})\.(\d{2})\.(\d{4})/;
    return new Date(dateString.replace(pattern, '$3-$2-$1'));
}
