/**
 * Created by Jordan on 28/08/2015.
 */


function wushka_button_loading(e_item, s_text) {
    e_item.text(s_text);
    jQuery('body').css({'cursor': 'wait!important'});
}

function wushka_button_failed(e_item, s_error, s_normal) {
    e_item.addClass('failed');
    e_item.text(s_error);
    jQuery('body').css({'cursor': 'default'});
    setTimeout(function () {
        e_item.removeClass('failed');
        e_item.text(s_normal);
    }, 800);
}

function wushka_button_finished(e_item, s_success, s_normal) {
    e_item.addClass('success');
    e_item.text(s_success);
    jQuery('body').css({'cursor': 'default'});
    setTimeout(function () {
        e_item.removeClass('success');
        e_item.text(s_normal);
    }, 800);
}

