bootstrap.Dropdown.prototype.getSelectedItemId = function () {
    return this._menu.querySelector('.active').id
}

bootstrap.Dropdown.prototype.clearActive = function () {
    this._menu.querySelectorAll('.active').forEach(function (element) {
        element.classList.remove('active');
    })
}

/**
 *
 * @param element String|Array
 */
bootstrap.Dropdown.prototype.addToList = function (element) {
    this._menu.innerHTML += (`<li><a class="dropdown-item" href="#">${element}</a></li>`)
}

bootstrap.Dropdown.prototype.updateList = function (elements) {
    this._menu.innerHTML = '';
    elements.forEach((arrayElement) => {
        this.addToList(arrayElement)
    })
}

