const dateHeaderSelector = '.date-header'
$.fn.exists = function () {
    return this.length > 0;
}
$.fn.hasInside = function (selector) {
    return this.find(selector).exists()
}

function getNearestDate(days, day = moment()) {
    if ((typeof day) == "string") {
        day = moment(day, 'DD.MM.YYYY')
    }
    for (let iteratedDayOfList of days) {
        let iteratedMomentObjectOfList = moment(iteratedDayOfList, 'DD.MM.YYYY');
        if (day.isSame(iteratedMomentObjectOfList, 'day')) {
            return {
                day: iteratedMomentObjectOfList,
                diff: 0
            }
        }
        if (day.isBefore(iteratedMomentObjectOfList, 'day')) {
            return {
                day: iteratedMomentObjectOfList,
                diff: iteratedMomentObjectOfList.diff(day.startOf('day'), 'days')
            }
        }
    }
    return {};
}

function findDayBlock(day, container) {
    return container.find(`div.day[data-date='${day}']`)
}

/**
 *
 * @param parentElement
 * @param element
 * @param adaptive
 */
function scrollToElement(parentElement, element, adaptive = true) {
    const firstDayBlockInList = parentElement.find('div.day:first')
    const dateHeaderHeight = 24;
    const listAnchorElement = (element.prev().is(dateHeaderSelector)) ? element.prev(dateHeaderSelector) : element
    const animateFn = 'linear';
    const animateTime = 1000;
    if (element.exists()) {
        if (breakPointEnabledUp(breakpointsUp.md) && adaptive) {
            console.log("SCROLL BODY")
            $('html,body').animate({
                scrollTop: listAnchorElement.position().top - $('.menu').outerHeight()
            }, animateTime, animateFn);
        } else {
            console.log("SCROLL LIST")
            parentElement.animate({
                scrollTop:
                    listAnchorElement.position().top -
                    firstDayBlockInList.position().top +
                    ((parentElement.hasInside('.menu')) ? $('.menu').outerHeight() : 0) -
                    (($(dateHeaderSelector).exists()) ? dateHeaderHeight : 0)
            }, animateTime, animateFn);
        }
        parentElement.find('.active').removeClass('active')
        element.addClass('active')
    }
}