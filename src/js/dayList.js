const $dayListUl = $("#listDays>ul")

function scrollToDate(date) {
    if ($dayListUl.find(`[data-date='${date}]'`).length > 0) {
        $dayListUl.animate({
            scrollTop: $dayListUl.find(`[data-date='${date}]'`).offset().top - 5
        }, 1000);
    }
}

function scrollToCurrentDate() {
    scrollToDate(new Date().toLocaleDateString())
}
