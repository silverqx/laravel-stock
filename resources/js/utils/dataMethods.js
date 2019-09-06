import $ from 'jquery'

/**
 * Allows to define request method on <a> tag.
 *
 * Generates <from> with proper csrf and method on click.
 */
export default class DataMethods {
    /**
     * Bind onclick event.
     */
    static declarative() {
        $('a[data-method], button[data-method]').on('click', (ev) => {
            if (typeof ev.currentTarget.confirmCallback !== 'function')
                return this.trigger(ev);

            // You can bind another on click event and define confirmCallback there
            ev.currentTarget.confirmCallback(this);
        });
    }

    /**
     * Trigger <form> generation and submit.
     *
     * Call trigger from confirmCallback, can be used eg to show bootbox
     * confirm dialog.
     *
     * @param {jQuery.Event} ev - The jQuery event.
     */
    static trigger(ev) {
        ev.preventDefault();
        ev.stopPropagation();

        let data = {};
        let $link = $(ev.currentTarget);

        for (var i = 0; i <= $link[0].attributes.length - 1; i++) {
            let attribute = $link[0].attributes[i];

            if (typeof attribute.name != 'string') {
                continue;
            }

            let found = attribute.name.match(/data-values-(.+)/i);

            if (! found) {
                continue;
            }

            data[found[1]] = attribute.value;
        }

        let action = $link.attr('href');
        let method = $link.data('method');
        // let csrfToken = window.Laravel.csrfToken;
        let csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        let $form = $(`<form method="post" action="${action}"></form>`);

        $form.append(`<input type="hidden" name="_method" value="${method}">`);
        $form.append(`<input type="hidden" name="_token" value="${csrfToken}">`);

        Object.keys(data).forEach((key) => {
            $form.append(`<input type="hidden" name="${key}" value="${data[key]}">`);
        });

        $('body').append($form);

        $form.submit();
    }
}
