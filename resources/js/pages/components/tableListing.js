import $ from 'jquery'

// Show Model detail on table row click
$(document).ready(() => {
    $('.cs-tbl-listing tbody').on('click', 'tr', function (ev) {
        if (!$(ev.target).closest('a.btn', this).length)
            window.location.href = $(ev.currentTarget).data('modelUrl')
    })
})
