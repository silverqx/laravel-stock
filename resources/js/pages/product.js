import bootbox from 'bootbox'
import bsCustomFileInput from 'bs-custom-file-input'

$(document).ready(() => {
    bsCustomFileInput.init()
})

// Show confirm dialog on delete product
$('.cs-product-delete').on('click', function(ev) {
    ev.preventDefault()
    ev.stopPropagation()

    const productName = $(this).closest('.cs-product-data').data('productName');

    ev.currentTarget.confirmCallback = (DataMethods) => {
        bootbox.confirm({
            size: 'small',
            message: `Do you want to delete <strong>${productName}</strong>?`,
            backdrop: true,
            buttons: {
                confirm: {
                    label: 'Yes',
                    className: 'btn-primary'
                },
                cancel: {
                    label: 'No',
                    className: 'btn-light'
                }
            },
            callback: result => {
                if (result)
                    DataMethods.trigger(ev)
            }
        })
    }
})

// Show Product detail on table row click
$('.cs-tbl-products tbody tr').on('click', function (ev) {
    if (false === $(ev.target).closest('td').hasClass('cs-actions'))
        window.location.href = $('.cs-product-show', this).attr('href')
})
