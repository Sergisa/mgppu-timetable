$.fn.exists = function () {
    return this.length > 0;
}
$('#mark_nearest').on('click', function () {
    let mainContainer = $("#listDays").exists() ? $("#listDays") : $("#roomsGrid")
    const days = lessonsTimetable.map((lesson) => lesson.dayDate).unique()
    const {day, diff} = getNearestDate(days);
    const nearestDayBlock = findDayBlock(day.format('DD.MM.YYYY'), mainContainer).get(0)
    const markingBlock = $("#listDays").exists() ? nearestDayBlock.previousElementSibling : nearestDayBlock;
    if (diff === 0) {
        markingBlock.dataset.interval = `Сегодня`
    } else {
        markingBlock.dataset.interval = (
            diff === 1
                ? `Через ${diff} день.`
                : ((diff < 5) ? `Через ${diff} дня.` : `Через ${diff} дней.`)
        )
    }
    nearestDayBlock.classList.add('nearest')
    scrollToElement(
        mainContainer,
        findDayBlock(day.format('DD.MM.YYYY'), mainContainer),
        $("#listDays").exists()
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