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
        if (breakPointEnabledDown(breakpointsUp.xs) && !breakPointEnabledDown(breakpointsUp.md)) {
            $('html,body').animate({
                scrollTop: dayLiBlock.position().top
            }, 1000);
        } else {
            $dayListUl.animate({
                scrollTop: dayLiBlock.position().top - $dayListUl.find('li:first').position().top
            }, 1000);
        }
        $dayListUl.find('.active').removeClass('active')
        dayLiBlock.addClass('active')
    }
}

function scrollToCurrentDate() {
    scrollToDate(new Date().toLocaleDateString())
}
