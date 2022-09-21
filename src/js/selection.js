const $variantPattern = $(`<div class="variant"></div>`)

class Selector {

    constructor($rootElement) {
        this.$root = $rootElement;
    }

    onItemClicked(fn) {

    }

    onItemSelected(fn) {

    }

    addLine(key, value) {
        $variantPattern.clone().attr({
            "data-value": key ?? value
        }).html(value).click(this.onItemClicked).appendTo(this.$root.find('.variant-list'))
    }

    fillData(lines) {
        console.log(lines)
        if (lines instanceof Array) {
            for (const line of lines) {
                this.addLine(null, line)
            }
        } else if (lines instanceof Object) {
            for (const key in lines) {
                this.addLine(key, lines[key])
            }
        }

    }

    getSelection() {
        return this.$root.find('.variant-list').find('.active').get().dataset.value
    }

    static generate(selectTag) {
        return new this();
    }
}
