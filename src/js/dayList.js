const $dayListUl = $("#listDays")

/**
 *
 * @param accentDate {string|Date}
 */
function scrollToDate(accentDate) {
    if (accentDate instanceof Date) {
        accentDate = accentDate.toLocaleDateString();
    }
    let $dayLiBlock = $dayListUl.find(`li[data-date='${accentDate}']`)
    const animateFn = 'easeOutCubic';
    const animateTime = 1000;
    if ($dayLiBlock.length > 0) {
        if (breakPointEnabledUp(breakpointsUp.sm)) {
            $('html,body').animate({
                scrollTop: $dayLiBlock.parent().prev('.date').position().top - $('.menu').outerHeight()
            }, animateTime, animateFn);
        } else {
            $dayListUl.animate({
                scrollTop:
                    $dayLiBlock.parent().prev('.date').position().top -
                    $dayListUl.find('li:first').position().top +
                    $('.menu').outerHeight() -
                    $('.date').outerHeight()
            }, animateTime, animateFn);
        }
        $dayListUl.find('.active').removeClass('active')
        $dayLiBlock.addClass('active')
    }
}

function scrollToCurrentDate() {
    scrollToDate(new Date().toLocaleDateString())
}
