let calendarWeekDays = [
    'Понедельник',
    'Вторник',
    'Среда',
    'Четверг',
    'Пятница',
    'Суббота',
    //'Воскресенье'
]
Date.prototype.hasNextInMonth = function () {
    return this.getDate() !== this.lastDay();
}
Date.prototype.lastDay = function () {
    return new Date(this.getFullYear(), this.getMonth() + 1, 0).getDate();
}
Date.prototype.hasNextInWeek = function (finalWeekDay) {
    return this.getDayName() !== (finalWeekDay ?? "Воскресенье")
}
Date.prototype.next = function () {
    if (this.hasNextInMonth()) {
        this.setDate(this.getDate() + 1)
        return this;
    } else {
        return null;
    }
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
