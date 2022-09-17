let calendarWeekDays = [
    'Понедельник',
    'Вторник',
    'Среда',
    'Четверг',
    'Пятница',
    'Суббота',
    //'Воскресенье'
]

Date.prototype.getMonthName = function () {
    const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    return monthNames[this.getMonth()];
}
Date.prototype.hasNextInMonth = function () {
    return this.getDate() !== this.lastDay();
}
Date.prototype.lastDay = function () {
    return new Date(this.getFullYear(), this.getMonth() + 1, 0).getDate();
}
Date.prototype.hasNextInWeek = function () {
    return this.getDayName() !== "Воскресенье"
}
Date.prototype.next = function () {
    if (this.hasNextInMonth()) {
        this.setDate(this.getDate() + 1)
    }
    return this;
}
Date.prototype.getDayIndex = function () {
    let weekDays = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
    return weekDays.indexOf(this.toString().split(' ')[0]) + 1
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
