import $ from 'jquery';

$(document).ready(function () {
    $('.btn-header-sidebar').click(function () {
        const sidebar = $('#sidebar');
        if(sidebar.css('display') === 'none') {
            sidebar.css({"margin-left": 0, "display": "block"});
        } else {
            sidebar.css({"margin-left": '100vw', "display": "none"});
        }
    });
});