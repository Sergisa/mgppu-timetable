const breakpointsUp = {
    xs: 0,
    sm: 576,
    md: 768,
    lg: 992,
    xl: 1200,
    xxl: 1400
}
const breakpointsDown = {
    sm: 575.98,
    md: 767.98,
    lg: 991.98,
    xl: 1199.98,
    xxl: 1399.98
}


function changedBreakPoint(x) {
    if (x.matches) {
        console.log(x)
    } else {
        console.log(x)
    }
}

function breakPointEnabledUp(breakPoint) {
    return window.matchMedia(`(max-width: ${breakPoint}px)`).matches
}

function breakPointEnabledDown(breakPoint) {
    return window.matchMedia(`(min-width: ${breakPoint}px)`).matches
}

const x = window.matchMedia(`(max-width: ${breakpointsUp.md}px)`)
x.addEventListener('change', changedBreakPoint)
changedBreakPoint(x)
