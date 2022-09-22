const $selectPattern = $(`<div class="select" id="groupSelect">
    <div class="selection">Выберите вариант</div>
    <div class="variant-list"></div>
</div>`)
const $variantPattern = $(`<div class="variant"></div>`)

class Selector {
    constructor($rootElement, config) {
        this.relatedSelectTag = config.relatedSelectTag;
        this.synchronizeSelectors = config.sync ?? false; //FIXME: true leads to endless cycle
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
        this.$root.find('.variant.active').removeClass('active')
        event.currentTarget.classList.add('active')
        if (this.externalHandler) this.externalHandler(event, this)
        this.$root.find('.selection').html(event.currentTarget.innerHTML);
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
        if (this.synchronizeSelectors) {
            let optionTag = document.createElement("option")
            optionTag.value = key;
            optionTag.innerHTML = key;
            this.relatedSelectTag.appendChild(optionTag)
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
