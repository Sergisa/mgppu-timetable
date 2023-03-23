const dateHeaderSelector = '.date-header'
$.fn.exists = function () {
    return this.length > 0;
}
$.fn.hasInside = function (selector) {
    return this.find(selector).exists()
}

/**
 *
 * @param daysList
 * @param accentDate {string|Date}
 * @param adaptive
 */
function scrollToDate(daysList, accentDate = new Date().toLocaleDateString(), adaptive = true) {
    if (accentDate instanceof Date) {
        accentDate = accentDate.toLocaleDateString();
    }
    console.log("I'm scrolling up to ", accentDate)
    const firstDayBlockInList = daysList.find('div.day:first')
    const dateHeaderHeight = 24;
    let anchorDayElement = daysList.find(`div.day[data-date='${accentDate}']`)
    const listAnchorElement = (anchorDayElement.prev().is(dateHeaderSelector)) ? anchorDayElement.prev(dateHeaderSelector) : anchorDayElement
    const animateFn = 'linear';
    const animateTime = 1000;
    if (anchorDayElement.exists()) {
        if (breakPointEnabledUp(breakpointsUp.md) && adaptive) {
            $('html,body').animate({
                scrollTop: listAnchorElement.position().top - $('.menu').outerHeight()
            }, animateTime, animateFn);
        } else {
            daysList.animate({
                scrollTop:
                    listAnchorElement.position().top -
                    firstDayBlockInList.position().top +
                    ((daysList.hasInside('.menu')) ? $('.menu').outerHeight() : 0) -
                    (($(dateHeaderSelector).exists()) ? dateHeaderHeight : 0)
            }, animateTime, animateFn);
        }
        daysList.find('.active').removeClass('active')
        anchorDayElement.addClass('active')
    }
}