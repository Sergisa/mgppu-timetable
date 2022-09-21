const $selectPattern = $(`<div class="select" id="groupSelect">
    <div class="selection">Выберите вариант</div>
    <div class="variant-list"></div>
</div>`)
const $variantPattern = $(`<div class="variant"></div>`)

class Selector {
    constructor($rootElement, config) {
        this.relatedSelectTag = config.relatedSelectTag;
        this.synchronizeSelectors = config.sync ?? false;
        this.hideAssociatedLabel = config.hideAssociatedLabel ?? false;
        this.$root = $rootElement;
        this.$list = $rootElement.find('.variant-list');
        this.$root.on('selectionchange', (event) => {
            event.preventDefault();
            event.stopPropagation()
            return false;
        });
        this.$root.on('click', () => {
            this.toggleList();
            return false;
        })
    }

    /**
     *
     * @param event {Event}
     * @private
     */
    #clickHandler(event) {
        this.$root.find('.variant.active').removeClass('active')
        event.currentTarget.classList.add('active')
        if (this.externalHandler) this.externalHandler(event, this)
        this.$root.find('.selection').html(event.currentTarget.innerHTML);
        this.hideList()
    }

    isShown() {
        this.$list.hasClass('active')
    }

    toggleList() {
        this.$list.toggleClass('active')
    }

    showList() {
        this.$list.addClass('active')
    }

    hideList() {
        this.$list.removeClass('active')
    }

    setOnItemClicked(fn) {
        this.externalHandler = fn
    }

    addLine(key, value) {
        $variantPattern.clone().attr({
            "data-value": key ?? value
        }).html(value).on('click', this.#clickHandler.bind(this)).appendTo(this.$root.find('.variant-list'))
        if (this.synchronizeSelectors) {
            let optionTag = document.createElement("option")
            optionTag.value = key;
            optionTag.innerHTML = key;
            this.relatedSelectTag.add(optionTag)
        }
    }

    appendData(lines) {
        if (lines instanceof Array) {
            for (const line of lines) {
                this.addLine(null, line)
            }
        } else if (lines instanceof HTMLOptionsCollection) {
            for (const optionObject of lines) {
                this.addLine(optionObject.value, optionObject.innerHTML)
            }
        } else if (lines instanceof Object) {
            for (const key in lines) {
                this.addLine(key, lines[key])
            }
        }
    }

    clear() {
        this.$list.empty()
    }

    fillData(lines) {
        this.clear()
        this.appendData(lines)
    }

    getSelection() {
        return this.$list.find('.active').get(0).dataset.value
    }

    /**
     *
     * @param event {JQuery.Event}
     */
    static clearMenus(event) {
        //const openedLists = $('.variant-list.active').removeClass('active')
        //:not(.variant), :not(.select), :not(.selection)
        /*if (!$(e).is('.variant, .select')) {
            this.toggleList();
            e.preventDefault()
            return false;
        }*/
    }

    /**
     *
     * @param selectTag {HTMLSelectElement}
     * @param config {Object}
     * @returns {Selector}
     */
    static generate(selectTag, config) {
        if (selectTag instanceof jQuery) {
            selectTag = selectTag.get();
        }
        selectTag.style.display = 'none'
        if (config) {
            if (config.hideAssociatedLabel) document.querySelector(`label[for='${selectTag.id}']`).style.display = 'none';
        }
        const $selectTag = $selectPattern.clone()
        const selectorObject = new this($selectTag.insertAfter(selectTag), config ?? {
            relatedSelectTag: selectTag
        });
        $selectTag.data("selector", selectorObject)
        selectorObject.fillData(selectTag.options)
        return selectorObject;
    }

    /**
     *
     * @param $tag {jQuery}
     * @returns {*|Selector}
     */
    static getOrCreateInstance($tag) {
        if ($tag instanceof HTMLDivElement) {
            $tag = $($tag)
        }
        return $tag.data('selector') ?? new Selector($tag, {})
    }
}

$(document).on('click', Selector.clearMenus)
//TODO: create mechanism closing other dropdowns
