//import Index from "lodash.isequal";
//import {isEqual} from "lodash";
//console.log("EQUAL",isEqual({a:3},{b:4}));
function deepEqual(x, y) {
    const ok = Object.keys, tx = typeof x, ty = typeof y;
    if (x && y && tx === 'object' && tx === ty) {
        return ok(x).length === ok(y).length && ok(x).every(key => deepEqual(x[key], y[key]))
    } else {
        return (x === y)
    }
}

function containsObject(obj, list) {
    var i;
    for (i = 0; i < list.length; i++) {
        // if (list[i] === obj) {
        if (deepEqual(list[i], obj)) {
            return true;
        }
    }
    return false;
}

function createObjectSequence(sequenceArray, rootObject = {}) {
    if (sequenceArray.length > 1) {
        sequenceArray.pop()
        rootObject[sequenceArray.last()] = {}
        return createObjectSequence(sequenceArray, rootObject)
    }
    return rootObject;
}

class StorageDriver {
    constructor(key) {
        //lastSelectedItems.professor-select
        this.containerKey = key;
        this.containerCallSequence = this.containerKey.split('.')
        if (localStorage.getItem(key) === null) {
            this.container = [];
        }
        //if ()
        this.container = localStorage.getItem(key) !== null ? JSON.parse(localStorage.getItem(key)) : {}
    }

    _saveObject(object) {

    }

    save(value, key) {
        if (key === undefined) {
            this._saveObject(value)
        }
        if (Object.keys(this.container).length > 5) {

        }
        this.container[key] = value;
    }

    valueExists(value) {
        return Object.values(this.container).includes(value)
    }

    keyExists(key) {
        if (Array.isArray(this.container)) {

        }
        if (this.container instanceof Object) {
            return Object.keys(this.container).includes(key)
        }
    }

    getLast(amount) {

    }
}