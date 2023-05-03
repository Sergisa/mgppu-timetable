Array.prototype.pluck = function (extractingKey) {
    let array = []
    this.forEach(function (value, key) {
        array.push(this[extractingKey]);
    })
    return array;
}

Array.prototype.unique = function () {
    return this.filter(function (value, index, array) {
            return array.indexOf(value) === index;
        }
    )
};
Array.prototype.extrude = function () {
    return this.map((element, key, array) => {
        if (element.Group.length > 1) {
            element.Group.forEach((value, key) => {
                if (key > 0) {
                    let newObject = {...element};
                    newObject.Group = value
                    this.push(newObject)
                }
            })
        }
        if (Array.isArray(element.Group)) element.Group = element.Group[0]
        return element;
    })
}