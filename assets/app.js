require('./styles/app.scss');

// require jQuery normally
const $ = require('jquery');

// create global $ and jQuery variables
global.$ = global.jQuery = $;

require('bootstrap');

$(document).ready(function() {
    $(".dropdown-toggle").dropdown();
});


