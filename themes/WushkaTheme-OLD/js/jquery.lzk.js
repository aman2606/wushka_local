/*
 * JQuery and javascript functions for wushka
 */
(function ($) {
    /*LZK front page function*/
    jQuery(document).ready(function ($) {
        (function ($, W, D) {
            var JQUERY4U = {};
            var form_action = $(".user-profile-form").attr('id');
            JQUERY4U.UTIL = {
                setupValidation: function () {
                    $(".user-profile-form").validate({
                        rules: {
                            first_name: "required",
                            last_name: "required",
                            show_user_pwd: {
                                required: true,
                                minlength: 3
                            }
                        },
                        messages: {
                            first_name: "Please provide a first name",
                            last_name: "Please provide a last name",
                            show_user_pwd: {
                                required: "Please provide a password",
                                minlength: "Your password must be at least 3 characters long"
                            }
                        },
                        submitHandler: function (form) {
                            if (form_action)
                                db_user_profile(form_action);
                        }
                    });
                }
            }
            //when the dom has loaded setup form validation rules
            $(D).ready(function ($) {
                JQUERY4U.UTIL.setupValidation();
            });
        })(jQuery, window, document);
        /*Show list of children*/

        /* Carousel Settings
         * Dynamically  bookshelf,books, colors and CSS
         * when user clicks on close bookshelf
         * This function hide vertical bookshelf and redraw horizontal bookshelf
         * */
        jQuery('.carousel').carousel("pause");

    });
    /*** End of LZK front page function ***/

    var modal = function (isShown) {
        $('.screen').html("<img src=" + template_directory + "/img/ajax-loader-2.gif>");
        $('.screen img').css({
            'top': '20%',
            'left': '50%',
            'position': 'relative'
        });
        $('.screen').css({
            'background-color': '#000000',
            opacity: 0.5,
            'width': $(document).width(),
            'height': $(document).height()
        });
        $('.screen').css({
            'overflow': 'hidden',
            'z-index': 10
        });
        $('.screen').css({
            'display': isShown,
            'position': 'absolute'
        });
    }

    /* Parents add a new child from parent view panel*/
    function db_user_profile(meta) {
        var data = $(".user-profile-form").serializeArray();
        data.push({
            name: 'meta',
            value: meta
        });

        $.ajax({
            url: db_user_profile_path,
            type: "POST",
            data: data,
            beforeSend: function () {
                modal('block');
            },
            completed: function () {

            },
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.error) {
                    $('#error-msg').modal('show');
                    $('#error-msg .btn-default').on('click', function () {
                        location.reload();
                    });
                } else {
                    window.location.href = '/manage-child-list';
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
    }

})(jQuery);
