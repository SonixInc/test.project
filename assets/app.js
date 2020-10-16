require('./styles/app.scss');

// require jQuery normally
const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

require('bootstrap');

$(document).ready(function() {
    $(".dropdown-toggle").dropdown();
});

$(document).on('click', '.summaries-button', function () {
    let button = $(this);
    let xhr = new XMLHttpRequest();
    let responseBlock = $('#response');

    xhr.open('POST', )
});
