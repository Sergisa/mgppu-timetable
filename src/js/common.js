const daysListSelector = $("#listDays");
$.fn.exists = function () {
    return this.length > 0;
}
$('#mark_nearest').on('click', function () {
    let mainContainer = daysListSelector.exists() ? daysListSelector : $("#roomsGrid")
    const days = lessonsTimetable.map((lesson) => lesson.dayDate).unique()
    const {day, diff} = getNearestDate(days);
    const nearestDayBlock = findDayBlock(day.format('DD.MM.YYYY'), mainContainer).get(0)
    const markingBlock = daysListSelector.exists() ? nearestDayBlock.previousElementSibling : nearestDayBlock;

    if (diff === 0) {
        $(markingBlock).find('.interval').html(`Сегодня`);
    } else {
        $(markingBlock).find('.interval').html(
            diff === 1
                ? `Через ${diff} день.`
                : ((diff < 5) ? `Через ${diff} дня.` : `Через ${diff} дней.`)
        )
    }
    nearestDayBlock.classList.add('nearest')
    scrollToElement(
        mainContainer,
        findDayBlock(day.format('DD.MM.YYYY'), mainContainer),
        daysListSelector.exists()
    )
})

function responseFail(data) {
    console.info(data.responseText)
}

function getUrlParamsObject() {
    const rqObject = {};
    let URLParams = new URLSearchParams(window.location.search);
    URLParams.forEach((value, key) => {
        rqObject[key] = value;
    })
    return rqObject;
}