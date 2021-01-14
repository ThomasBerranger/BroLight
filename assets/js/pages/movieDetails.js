import $ from "jquery";

$(document).ready(function(){
    $(`#rating-${$('#rateValue').data('rate-value')}`).click();
});