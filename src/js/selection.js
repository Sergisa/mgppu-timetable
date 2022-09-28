const $arrow = `<i class="bi bi-chevron-down"></i>`;
const clear = `<i class="bi bi-x-lg"></i>`;
const $selectPattern = $(`<div class="select" id="groupSelect">
    <div class="selection">Все ${$arrow}</div>
    <div class="variant-list"></div>
</div>`)
const $variantPattern = $(`<div class="variant"></div>`)
const changeSelectedByContent = (element, value) => {
    changeSelected(element, value, 'text')
};
const changeSelectedByValue = (element, value) => {
    changeSelected(element, value, 'value')
};
const changeSelected = (element, value, prop) => {
    const $options = Array.from(element.options);
    const optionToSelect = $options.find(item => item[prop] === value);
    element.value = optionToSelect.value;
};

class Selector {
    constructor($rootElement, config) {
        this.relatedSelectTag = config.relatedSelectTag;
        this.synchronizeSelectors = config.synchronizeSelectors ?? false; //FIXME: true leads to endless cycle
        this.hideAssociatedLabel = config.hideAssociatedLabel ?? false;
        this.$root = $rootElement;
        this.$selection = $rootElement.find('.selection');
        this.$list = $rootElement.find('.variant-list');
        $(window).on('resize', () => {
            this.adaptSize()
        })
        this.$root.on('selectionchange', (event) => {
            event.preventDefault();
            event.stopPropagation()
            return false;
        });
        this.$root.on('click', () => {
            this.toggleList();
            this.adaptSize()
        })
    }

    adaptSize() {
        this.$list.css({
            "max-height": (
                $(window).height() - this.$list.offset().top - parseInt(this.$list.css('border-width'))
            ) + "px"
        })
    }

    /**
     *
     * @param event {Event}
     * @private
     */
    #clickHandler(event) {
        this.clearSelection()
        event.currentTarget.classList.add('active')
        if (this.externalHandler) this.externalHandler(event, this)
        let $clear = $(clear)
        $clear.on('click', () => {
            this.clearSelection()
            this.$selection.html($selectPattern.find('.selection').clone().html());
        })
        this.$selection.html(event.currentTarget.innerHTML).append($clear);
        changeSelectedByContent(this.relatedSelectTag, event.currentTarget.innerHTML)
        this.hideList()
    }

    toggleList() {
        this.$list.toggleClass('active')
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
        if (this.synchronizeSelectors) {//FIXME: Первым стоит старый объект Option
            let optionTag = document.createElement("option")
            optionTag.value = key;
            optionTag.innerHTML = value;
            this.relatedSelectTag.appendChild(optionTag)
        }
    }

    appendData(lines) {
        if (lines instanceof Array) {
            console.log('ARRAY', lines)
            for (const line of lines) {
                this.addLine(line.id ?? "null", line.name ?? "NULL")
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

    clearSelection() {
        this.$list.find('.active').removeClass('active')
        this.relatedSelectTag.selectedIndex = -1
    }

    fillData(lines) {
        this.clear()
        this.appendData(lines)
        this.clearSelection()
    }

    getSelection() {
        return this.$list.find('.active').get(0).dataset.value
    }

    /**
     *
     * @param event {Event}
     */
    static clearMenus(event) {
        document.querySelectorAll('.variant-list.active').forEach(function (listElement) {
            const selectorObject = Selector.getOrCreateInstance(listElement.parentElement)
            if (event.target !== selectorObject.$selection.get(0)) {
                selectorObject.hideList()
            }
        })
    }

    /**
     *
     * @param selectTag {HTMLSelectElement}
     * @param config {Object}
     * @returns {Selector}
     */
    static generate(selectTag, config = undefined) {
        if (selectTag instanceof jQuery) {
            selectTag = selectTag.get();
        }
        selectTag.style.display = 'none'
        if (config) {
            if (config.hideAssociatedLabel) document.querySelector(`label[for='${selectTag.id}']`).style.display = 'none';
        }
        const $selectTag = $selectPattern.clone()
        config.relatedSelectTag = selectTag
        const selectorObject = new this($selectTag.insertAfter(selectTag), config);
        $selectTag.data("selector", selectorObject)

        const opts = {...selectTag.options}
        selectorObject.fillData(opts)
        return selectorObject;
    }

    /**
     *
     * @param $tag {jQuery|HTMLDivElement}
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
