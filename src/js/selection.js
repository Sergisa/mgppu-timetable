const $arrow = `<i class="bi bi-chevron-down"></i>`;
const clear = `<i class="bi bi-x-lg"></i>`;
const loaderSpinner = `<div class="lds-dual-ring active"></div>`;
const $selectPattern = $(`<div class="select" id="groupSelect">
    <div class="selection">
        <div class="leftSide">
            <input type="text" name="search" class="search d-none" placeholder="Найти">
        </div>
        <div class="rightSide"></div>
    </div>
    <div class="variant-list"></div>
</div>`)
const $variantPattern = $(`<div class="variant"></div>`)
const changeSelectedByContent = (element, value) => {
    changeSelected(element, value, 'text')
};
const changeSelected = (element, value, prop) => {
    const $options = Array.from(element.options);
    const optionToSelect = $options.find(item => item[prop] === value);
    element.value = optionToSelect.value;
};

class Selector {
    constructor($rootElement, config) {
        this.config = config;
        this.relatedSelectTag = config.relatedSelectTag;
        this.synchronizeSelectors = config.synchronizeSelectors ?? false; //FIXME: true leads to endless cycle
        this.hideAssociatedLabel = config.hideAssociatedLabel ?? false;
        this.$root = $rootElement;
        this.$selection = $rootElement.find('.selection');
        this.$searchInput = this.$selection.find('.search');
        this.$rightSide = this.$selection.find('.rightSide');
        this.$leftSide = this.$selection.find('.leftSide');
        this.$stateIndicator = $(loaderSpinner)
        this.$rightSide.append(this.$stateIndicator)
        this.$list = $rootElement.find('.variant-list');
        $(window).on('resize', () => {
            this.adaptSize()
        })
        this.attachListeners()
        this.$root.on('selectionchange', (event) => {
            event.preventDefault();
            event.stopPropagation()
            return false;
        });
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
            this.$leftSide.html(this.$searchInput)
            this.$rightSide.html($arrow)
            this.attachListeners()
        })
        this.$leftSide.html(event.currentTarget.innerHTML);
        this.$rightSide.html($clear)
        changeSelectedByContent(this.relatedSelectTag, event.currentTarget.innerHTML)
        this.hideList()
    }

    setEnabled() {
        this.$stateIndicator.removeClass('active')
        this.$stateIndicator.replaceWith($arrow)
        this.$searchInput.removeClass('d-none')
    }

    toggleList() {
        console.log("toggling ", this.$list.get(0))
        this.$list.toggleClass('active')
    }

    hideList() {
        this.$list.removeClass('active')
    }

    filterVariants(substring) {
        this.$list.children().each((index, element) => {
            if (!element.innerHTML.toLowerCase().includes(substring)) {
                element.classList.add('d-none')
            } else {
                element.classList.remove('d-none')
            }
        })
    }

    attachListeners() {
        this.$searchInput.on('input', (event) => {
            this.filterVariants(event.target.value.toLowerCase());
            this.showList();
            this.adaptSize();
        })
    }

    showList() {
        this.$list.addClass('active')
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
            if ((event.target !== selectorObject.$selection.find('.bi-chevron-down').get(0))) {
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
$(document).on('click', '.selection', function () {
    const context = Selector.getOrCreateInstance(this.parentElement);
    context.toggleList()
    context.adaptSize();
})
