const $dayListUl = $("#listDays>ul")

/**
 *
 * @param accentDate {string|Date}
 */
function scrollToDate(accentDate) {
    if (accentDate instanceof Date) {
        accentDate = accentDate.toLocaleDateString();
    }
    let dayLiBlock = $dayListUl.find(`li[data-date='${accentDate}']`)
    if (dayLiBlock.length > 0) {
        if (dayLiBlock.position().top > 7) {
            $dayListUl.animate({
                scrollTop: dayLiBlock.position().top - 5
            }, 1000);
        }
        dayLiBlock.addClass('active')
    }
}

function scrollToCurrentDate() {
    scrollToDate(new Date().toLocaleDateString())
}
