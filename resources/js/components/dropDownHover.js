import $ from 'jquery'

$('.cs-main-navbar').on('click', 'a[data-toggle="dropdown"]', (ev) => {
    ev.preventDefault()
    window.location.href = ev.currentTarget.href
})
