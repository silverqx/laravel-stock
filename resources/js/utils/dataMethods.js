import $ from 'jquery'
import bootbox from 'bootbox'

/**
 * Allows to define request method on <a> tag and show bootbox.confirm.
 *
 * Generates <from> with proper csrf and method on click.
 */
export default class DataMethods {
    /**
     * Bind onclick event.
     */
    static declarative() {
        $('a[data-method], button[data-method]').on('click', (ev) => {
            ev.preventDefault()
            ev.stopPropagation()

            DataMethods.ev = ev

            const $this = $(ev.currentTarget)
            const dataToggle = $this.data('toggle')
            const hasBootbox = dataToggle && dataToggle === 'bootbox'

            if (hasBootbox) {
                const message = $this.data('confirmMessage')

                return DataMethods.confirmCallback(message, $this)
            }

            return this.trigger($this)
        })
    }

    /**
     * Shows bootbox.confirm to confirm eg delete action.
     *
     * @param {string} message - Bootbox confirm message.
     * @param {jQuery} $elem - Link tag.
     */
    static confirmCallback(message, $elem) {
        bootbox.confirm({
            size: 'small',
            message,
            backdrop: true,
            buttons: {
                confirm: {
                    label: '<i class="fas fa-check mr-2"></i>Yes',
                    className: 'btn-primary'
                },
                cancel: {
                    label: '<i class="fas fa-ban mr-2"></i>No',
                    className: 'btn-light'
                }
            },
            callback: result => {
                if (result)
                    DataMethods.trigger($elem)
            }
        })
    }

    /**
     * Triggers <form> generation and submit.
     *
     * @param {jQuery} $elem - Link tag.
     */
    static trigger($elem) {
        const data = {}
        const $link = $elem

        for (let i = 0; i <= $link[0].attributes.length - 1; i++) {
            let attribute = $link[0].attributes[i]

            if (typeof attribute.name != 'string') {
                continue
            }

            let found = attribute.name.match(/data-values-(.+)/i)

            if (! found) {
                continue
            }

            data[found[1]] = attribute.value
        }

        let action = $link.attr('href')
        let method = $link.data('method')
        // let csrfToken = window.Laravel.csrfToken
        let csrfToken = document.head.querySelector('meta[name="csrf-token"]').content

        let $form = $(`<form method="post" action="${action}"></form>`)

        $form.append(`<input type="hidden" name="_method" value="${method}">`)
        $form.append(`<input type="hidden" name="_token" value="${csrfToken}">`)

        Object.keys(data).forEach((key) => {
            $form.append(`<input type="hidden" name="${key}" value="${data[key]}">`)
        })

        $('body').append($form)

        $form.submit()
    }
}
