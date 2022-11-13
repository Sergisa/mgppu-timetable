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
const breakPointsSequence = ['sm', 'md', 'lg', 'xl', 'xxl']

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

function getBreakPoint() {
    for (const key of Object.keys(breakpointsUp).reverse()) {
        if (breakPointEnabledDown(breakpointsUp[key])) {
            return key
        }
    }
}

function reportBreakPointDOWN() {
    return {
        xs: breakPointEnabledDown(breakpointsUp.xs),
        sm: breakPointEnabledDown(breakpointsUp.sm),
        md: breakPointEnabledDown(breakpointsUp.md),
        lg: breakPointEnabledDown(breakpointsUp.lg),
        xl: breakPointEnabledDown(breakpointsUp.xl),
        xxl: breakPointEnabledDown(breakpointsUp.xxl)
    }
}

function reportBreakPointUP() {
    return {
        xs: breakPointEnabledUp(breakpointsUp.xs),
        sm: breakPointEnabledUp(breakpointsUp.sm),
        md: breakPointEnabledUp(breakpointsUp.md),
        lg: breakPointEnabledUp(breakpointsUp.lg),
        xl: breakPointEnabledUp(breakpointsUp.xl),
        xxl: breakPointEnabledUp(breakpointsUp.xxl)
    }
}
