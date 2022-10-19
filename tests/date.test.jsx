require('../src/js/dateDriver')
const {test, expect} = require('@jest/globals')

let today = new Date();

test("Проверка наличия следующего в месяце", () => {
    today = new Date(2022, 9, 19);
    expect(today.hasNextInMonth()).toBeTruthy()
    today = new Date(2022, 9, 31);
    expect(today.hasNextInMonth()).toBeFalsy()
    today = new Date(2022, 9, 28);
    expect(today.hasNextInMonth()).toBeTruthy()
    today = new Date(2022, 9, 29);
    expect(today.hasNextInMonth()).toBeTruthy()
    today = new Date(2022, 9, 30);
    expect(today.hasNextInMonth()).toBeTruthy()
})

test("Проверка наличия следующего в Неделе", () => {
    today = new Date(2022, 9, 19);
    expect(today.hasNextInWeek()).toBeTruthy()
    today = new Date(2022, 9, 15);
    expect(today.hasNextInWeek()).toBeTruthy()
    today = new Date(2022, 9, 15);
    expect(today.hasNextInWeek('Суббота')).toBeFalsy()
    today = new Date(2022, 9, 16);
    expect(today.hasNextInWeek()).toBeFalsy()

    /*today = new Date(2022, 3, 30);
    expect(today.hasNextInWeek()).toBeFalsy()*/
})

test("Переход на следующий день", () => {
    today = new Date(2022, 9, 19);
    expect(today.next().toLocaleDateString()).toBe('2022-10-20')
    today = new Date(2022, 3, 30);
    expect(today.next()).toBe(null)
})
