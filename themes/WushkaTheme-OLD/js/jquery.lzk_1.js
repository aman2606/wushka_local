/* 
 * JQuery and javascript functions for wushka
 */
(function ($) {
    "use strict";
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
        var random_number;

        random_number = getRandomInt(1000, 99999);

        $('.new-child input').on('focus', function () {
            console.log($('#add-new-child #first_name').val());
            var fname = $('#add-new-child #first_name').val().charAt(0);
            var lname = $('#add-new-child #last_name').val().charAt(0);
            if (fname || lname)
                $('#username').val(fname + lname + '-' + random_number);
        });

        /*SET session for Carousel Indicators*/
        function session_set(current, indicator) {
            $.ajax({
                url: temp_directory + '/session.php',
                type: "POST",
                data: {
                    'id': current,
                    'value': indicator
                },
                async: false,
                success: function (data) {
                    console.log(data);
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    console.log('failed');
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                }
            });
        }

        /*Check the requested element is visible on screen
         * This is used to check if carousel is visible on screen
         * */
        function isElementVisible(elementToBeChecked) {

            var scrollTop = $(window).scrollTop(),
                    elementOffset = $(elementToBeChecked).offset().top,
                    max = elementOffset + $(elementToBeChecked).outerHeight() - $(window).height();

            var distance = ((elementOffset - scrollTop) > 0) ? (elementOffset - scrollTop) : 0;
            // 250 = starting point of collapse button
            if (scrollTop >= (elementOffset - $(window).height() + 250) && (scrollTop <= (elementOffset + $(elementToBeChecked).height() - ($(window).height() + 250)))) {
                return true;
            } else
                return false;
        }

        /*Call session_set function when user clicks on 
         * carousel indicator */
        $('.accordion-shelf-book').on('click', function () {
            var current = $(this).parents('.carousel').attr('id');
            var indicator = $('#' + current).find('.active').index();
            session_set(current, indicator);
        });

        /*Popover license limit message*/
        $('.license-limit').popover('click');

        /* Carousel Settings 
         * Dynamically  bookshelf,books, colors and CSS 
         * when user clicks on close bookshelf
         * This function hide vertical bookshelf and redraw horizontal bookshelf
         * */
        jQuery('.carousel').carousel("pause");
        $(document).on('mouseover', '.panel-collapse', function () {
            var $element = '#' + $(this).attr('id');
            var isOnView = isElementVisible($element);

            if (isOnView) {
                $($element + ' .panel-body').css({
                    //  "background-image" : "url(//cdn1.lessonzone.com.au/Resources/shelf-gold.png)",
                    // "padding": "0 0 45px",
                    // "margin-bottom": "40px",
                    // "background-repeat": "no-repeat",
                    // "background-position": "center bottom",
                    // "background-size": "contain",
                    // "background-color": "transparent",
                    // "box-shadow": "inset 0px 0px 20px rgba(0, 0, 0, 0.2)",
                    // "border-radius": "10px"
                });
                $($element + ' .panel-body .row').css({
                    "padding": "10px"
                });
                $($element + ' .panel-footer').fadeIn();

                $($element + ' .panel-footer').css({
                    /*"position": "fixed",
                     "display": "block",
                     "top": "86%",
                     "width": "500px",
                     "margin-left": "320px"*/
                });

                $($element + ' .panel-footer .btn-shelf-close-bottom').css({
                    //"background-color": "#66CC99"
                });
            } else {
                var $imgSource = $($element + ' .panel-body').attr('class').split(' ')[1];
                if ($imgSource) {
                    $($element + ' .panel-body').css({
                        // "background-image": "url(//cdn1.lessonzone.com.au/Resources/shelf-" + $imgSource + ".png)"
                    });
                }
                $($element + ' .panel-body').css({
                    // "padding": "0 0 45px",
                    // "margin-bottom": "40px",
                    // "background-repeat": "no-repeat",
                    // "background-position": "center bottom",
                    // "background-size": "contain",
                    // "background-color": "transparent",
                    // // "box-shadow": "inset 0px 0px 20px rgba(0, 0, 0, 0.2)",
                    // "border-radius": "10px"
                });
                $($element + ' .panel-body .row').css({
                    "padding": "10px"
                });
                $($element + ' .panel-footer').css({
                    // "border-top": "0",
                    /*"margin": "-44px 0 0 0",
                     "min-height": "0",
                     "padding-top": "10px",
                     "position": "static",
                     "width": "100%",
                     "display": "block"*/
                });
                $($element + ' .panel-footer .btn-shelf-close-bottom').css({
                    "background-color": "transparent"
                });
            }
        });

        /*Hide Vertical shelf when user clicks on close book shelf button*/
        $(document).on('click', '.btn-shelf-close-bottom', function () {
            var $id = $(this).attr('href').replace('#collapse-', '');
            $("#collapse-" + $id).fadeOut('slow');
            $("#carousel-taxo-" + $id).children().fadeIn("slow");
            $(".panel-" + $id + " .btn-shelf-expand").fadeIn("slow");
            $(".panel-" + $id + " .panel-footer").fadeIn();
        });

        /* Carousel Settings 
         * Dynamically  bookshelf,books, colors and CSS 
         * when user clicks on expand bookshelf
         * This function hide horizontal bookshelf and redraw vertical bookshelf
         * */
        $(document).on('click', '.btn-shelf-expand', function () {
            $(window).unbind('scroll');
            var $container = $(this).attr('href'),
                    $id = $container.replace('#collapse-', '');

            $("#carousel-taxo-" + $id).children().fadeOut();
            $(".panel-" + $id + " .panel-footer").fadeOut();
            $(".panel-" + $id + " .btn-shelf-expand").fadeOut();
            $("#collapse-" + $id).fadeIn('slow');
            $($container + ' .panel-footer').css({
                /*"position": "fixed",
                 "display": "block",
                 "top": "86%",
                 "width": "500px",
                 "margin-left": "320px"*/
            });
            $(window).scroll(function () {
                var isOnView = isElementVisible($container);

                if (isOnView) {
                    $($container + ' .panel-body').css({
                        //  "background-image" : "url(//cdn1.lessonzone.com.au/Resources/shelf-gold.png)",
                        // "padding": "0 0 45px",
                        // "margin-bottom": "40px",
                        // "background-repeat": "no-repeat",
                        // "background-position": "center bottom",
                        // "background-size": "contain",
                        // "background-color": "transparent",
                        // // "box-shadow": "inset 0px 0px 20px rgba(0, 0, 0, 0.2)",
                        // "border-radius": "10px"
                    });
                    $($container + ' .panel-body .row').css({
                        "padding": "10px"
                    });
                    $($container + ' .panel-footer').fadeIn();

                    $($container + ' .panel-footer').css({
                        /*"position": "fixed",
                         "display": "block",
                         "top": "86%",
                         "width": "500px",
                         "margin-left": "320px"*/
                    });
                    $($container + ' .panel-footer .btn-shelf-close-bottom').css({
                        //"background-color": "#66CC99"
                    });
                } else {
                    var $imgSource = $($container + ' .panel-body').attr('class').split(' ')[1];
                    if ($imgSource) {
                        $($container + ' .panel-body').css({
                            // "background-image": "url(//cdn1.lessonzone.com.au/Resources/shelf-" + $imgSource + ".png)"
                        });
                    }
                    $($container + ' .panel-body').css({
                        //  "background-image" : "url(//cdn1.lessonzone.com.au/Resources/shelf-gold.png)",
                        // "padding": "0 0 45px",
                        // "margin-bottom": "40px",
                        // "background-repeat": "no-repeat",
                        // "background-position": "center bottom",
                        // "background-size": "contain",
                        // "background-color": "#FFF",
                        // // "box-shadow": "inset 0px 0px 20px rgba(0, 0, 0, 0.2)",
                        // "border-radius": "10px"
                    });
                    $($container + ' .panel-body .row').css({
                        "padding": "10px"
                    });
                    // $($container + ' .panel-footer').fadeOut();
                    $($container + ' .panel-footer').css({
                        // "border-top": "0",
                        /*"margin": "-44px 0 0 0",
                         "min-height": "0",
                         "padding-top": "10px",
                         "position": "static",
                         "width": "100%",
                         "display": "block"*/
                    });
                    $($container + ' .panel-footer .btn-shelf-close-bottom').css({
                        "background-color": "transparent"
                    });
                }
            });
        });
        //Select or deselect to books from assinged bookshelf
        $(document).on('click', '.level-books', function () {
            var bookId = $(this).attr('id').replace('book-', '');
            var userId = $('.reading-groups').attr('id');
            var group = $('.assigned-work').attr('id');
            var level = $('.books-lists').attr('id');
            console.log('LEVEL ' + level);

            if (userId != undefined && group != undefined && bookId != undefined) {
                //Select or deselect box which add or remove books from a reading group
                if ($(this).hasClass('added')) {
                    $(this).removeClass('added');
                    edit_assign_work(userId, group, bookId, 'delete', '');
                } else {
                    $(this).addClass('added');
                    edit_assign_work(userId, group, bookId, 'submit', level);
                }

            } else {
                $('.error-msg').children().remove();
                $(".error-msg").append("<div class='error-msg-container'><div class='error-msg-container-inner'> <p>You haven't added any books to " + value + " yet. Select reading level to start adding books.</p></div></div>");
            }
        });

        //Select or deselect to books from level bookshelf
        $(document).on('click', '.checkbox-container', function () {
            var bookId = $(this).find('span').attr('id').replace('post-', '');
            var userId = $('.reading-groups').attr('id');
            var group = $('.assigned-work').attr('id');
            var level = $('.books-lists').attr('id');
            //console.log('bookId ' + bookId);
            if (userId != undefined && group != undefined && bookId != undefined) {
                $(".books-lists div#book-" + bookId).removeClass('added');

                edit_assign_work(userId, group, bookId, 'delete', '');
                // Delete before roll out
                //Select or deselect box which add or remove books from a reading group
                $(this).children('input[type=checkbox]').each(function () {
                    this.checked = !this.checked;

                    if (this.checked) {
                        edit_assign_work(userId, group, bookId, 'submit', level);
                        $(".books-lists div#book-" + bookId).addClass('added');
                    } else {
                        $(".books-lists div#book-" + bookId).removeClass('added');
                        if ($(".books-lists .checkbox-container .post-" + bookId).is(':checked')) {
                            $(".books-lists .checkbox-container .post-" + bookId).prop('checked', false);
                        }
                        edit_assign_work(userId, group, bookId, 'delete', '');
                    }
                });
            } else {
                $('.error-msg').children().remove();
                $(".error-msg").append("<div class='error-msg-container'><div class='error-msg-container-inner'><p>You haven't added any books to " + value + " yet. Select reading level to start.</p></div></div>");
            }
        });

        /* Show books from the selected reading group*/
        $('.reading-groups span').on('click', function () {
            $('.reading-groups,.reading-level').children().removeClass('selected');
            $('.books-lists').removeAttr('id');
            $('.books-lists').children().remove();

            $('.books-lists input[type=checkbox]').each(function () {
                $('input[type="checkbox"]').prop('checked', false);
            });

            $(this).addClass('selected');
            var groupId = $(this).attr('id');
            var groupName = $(this).text();
            var id = $(this).parent().attr('id');

            if (groupId && id) {
                $('.assigned-work').removeAttr('id').attr('id', groupId);
                $('.assigned-work').children().fadeOut(100, function () {
                    $(this).remove();
                });
                //console.log('Clicked reading group = ' + groupId);
                show_work(id, groupId, groupName, 'show');
            } else {
                $('.error-msg').children().remove();
                $(".error-msg").append("<div class=error-msg-container'><div class=error-msg-container-inner'><p>Select a reading group before you assign books.</p></div></div>").fadeIn(500).delay(100).fadeOut(500);
            }
        });

        //Remove all books from a selected reading group
        $(document).on('click', '.remove-all', function () {
            var groupId = $(this).parents('.assigned-work').attr('id');
            if (confirm("Are you sure you want to remove All books ?")) {
                edit_assign_work('', groupId, '', 'deleteAll', '');
            }
        });

        //Assign work into reading group
        $('.submit-books').on('click', function () {
            var userId = $(this).attr('id');
            var bookIds = [];
            var group;
            $(".books-lists input[type=checkbox]:checked").each(function (e) {
                bookIds.push($(this).val());
            });
            group = $(this).prev().find('li.selected').attr('value');
            console.log(bookIds);
            if (bookIds.length > 0)
                edit_assign_work(userId, group, bookIds, 'submit');
        });

        //Delete a group
        $(document).on('click', '.delete-group', function () {
            var groupId = $(this).parents('.manage-reading-groups-row').attr('id');
            var groupName = $(this).parents('.manage-reading-groups-row').text().capitalize();
            //  console.log(groupId);
            $('#confirm-delete-' + groupId).show();
            $('#confirm-delete-' + groupId + ' .delete-group-yes').on('click', function () {
                manage_reading_group(groupId, groupName, 'delete_reading_group');
            });

            $('#confirm-delete-' + groupId + ' .delete-group-no').on('click', function () {
                $('#confirm-delete-' + groupId).hide();
            });
            /*if (confirm("Are you sure you want to delete " + groupName + " group ?")) {
             }*/
        });

        //Edit work for reading group
        $('.show-books, .delete-books').on('click', function () {
            var action = $(this).attr('class').split(' ')[2].replace('-books', '');
            var id = $(this).attr('id');
            var bookIds = [];
            var group = $('.btn-students-dropdown .dropdown-menu').find('.selected').attr('value');
            if (group != 'undefined' && action == 'show') {
                show_work(id, group, '', action);
            } else
            if (action == 'delete') {
                $("input[type=checkbox]:checked").each(function (e) {
                    bookIds.push($(this).val());
                });
                if (bookIds.length > 0)
                    edit_assign_work(id, group, bookIds, action);
            }
        });
        /*Preselect reading group after page load*/
        $(window).bind("load", function () {
            var groupId = $('.reading-groups').children('span.selected').attr('id');
            //console.log('preselected ' + groupId);
            var groupName = $('.reading-groups').children('span.selected').text();

            if (groupId) {
                var id = $('.reading-groups').children('span#' + groupId).parent().attr('id');
                if (groupId && id) {
                    $('.assigned-work').removeAttr('id').attr('id', groupId);
                    $('.assigned-work').children().fadeOut(100, function () {
                        $(this).remove();
                    });

                    show_work(id, groupId, groupName, 'show');
                }
            }
        });

        //Close the manage reading group dialog
        $('.close-btn-modal').on('click', function () {
            $('#manage-reading-groups').dialog('close');
        });

        //Show or hide related books
        $('.reading-level .level').on('click', function () {
            var $current = $(this).attr('id');
            var $selected_reading_group = $('.reading-groups').find('span.selected').attr('id');
            // console.log($selected_reading_group);
            var $selected_reading_groupName = $('#' + $selected_reading_group).text();

            $('.reading-level').children().removeClass('selected');
            $(this).addClass('selected');
            $('.books-lists').removeAttr('id').attr('id', $current);
            reload_books_query($current, $selected_reading_group, $selected_reading_groupName);
            $(window).scroll(function () {
                if ($(window).scrollTop() + window.innerHeight == $(document).height()) {
                    //reload_books_query($current, $paged++);
                }
            });

            $('html, body').animate({
                scrollTop: ($('.assigned-work').outerHeight() + 40)
            }, 500);
        });

        /*Trigger editable box for manage reading group*/
        $(document).on('click', '.edit-group', function () {
            var id = $(this).parents('.manage-reading-groups-row').attr('id');
            //  console.log( $('#' +id).find('reading-group-name'));
            $('#' + id).find('.reading-group-name').trigger('click');
        });
        //Add a new student
        $('#new-child').on('click', function () {
            // $('div#class-view_filter').focus();
            var max_num = ($(this).data('max-number')) - 1;
            var parentId = $(this).data('id');

            // add_new_user(parentId);

        });
        /* Edit reading group name*/
        load_jeditable_readingGroups();
    });
    /*** End of LZK front page function ***/

    /* Get random number*/
    function getRandomInt(min, max) {
        return min + Math.floor(Math.random() * (max - min + 1));
    }

    function add_new_row(id) {
        var rowCount = $('#parent-view').dataTable().fnGetData().length;
        var rowIndex = $('#parent-view').dataTable().fnAddData([
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '<td></td>'
        ]);
        // console.log(rowIndex);

        var row = $('#parent-view').dataTable().fnGetNodes(rowIndex);
        $(row).find('td:eq(0)').addClass('focus_fname').attr('data-id', 'first_name');
        $(row).find('td:eq(1)').addClass('focus_lname').attr('data-id', 'last_name');
        $(row).find('td:eq(2)').addClass('username');
        $(row).find('td:eq(3)').addClass('show_user_pwd');
        $(row).find('td:eq(4)').addClass('age');
        $(row).find('td:eq(5)').addClass('narration').addClass('yesorno');
        $(row).find('td:eq(6)').addClass('last_access');
        $(row).find('td:eq(7)').addClass('avg_access_time');
        $(row).find('td:eq(8)').addClass('uneditable');
        editable();
        oTable = $('#parent-view').dataTable({
            "iDisplayLength": 15
        });
        return row;

    }
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
                    window.location.href = '/my-account';
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
    }

    /*To load jeditable reading groups*/
    function load_jeditable_readingGroups() {
        $('#manage-reading-groups .reading-group-name').editable(function (value, settings) {
            console.log('Ready ' + value);
            id = $(this).parents('.manage-reading-groups-row').attr('id');
            manage_reading_group(id, value, 'edit_group_name');
            return (value);
        }, {
            event: 'click',
            type: 'text',
            placeholder: '',
            submit: 'OK',
            tooltip: 'Edit a reading group'
        });
    }

    function manage_reading_group(id, value, action) {

        $.ajax({
            url: edit_group_data_path,
            type: "POST",
            data: {
                'id': id,
                'value': value,
                'action': action
            },
            success: function (data) {
                var obj = JSON.parse(data);
                if (action == 'edit_group_name') {
                    //   console.log(obj.name);
                    $('.my_reading_group').each(function () {
                        if ($(this).text().toLowerCase() === obj.name.toLowerCase()) {
                            $(this).text(value);
                        }
                    });
                }
                if (action == 'add_reading_group') {
                    if (obj.id && obj.value && obj.value != 'Duplicate') {
                        $('.manage-reading-groups-wrapper').remove('.error-msg');
                        $('.manage-reading-groups-wrapper').append('<div id="' + obj.id + '" class="manage-reading-groups-row"> <div class="manage-reading-groups-edit">' +
                                ' <span class="reading-group-name" title="Edit a reading group">' + obj.value.capitalize() + '</span>' +
                                '<div class="edit-delete-group-options"><a class="edit-group btn btn-rg btn-light-grey"><span class="glyphicon glyphicon-pencil"></span> Edit</a>' +
                                '<a class="delete-group btn btn-rg btn-light-grey"><span class="glyphicon glyphicon-remove"></span> Delete</a>' +
                                '</div></div><div class="alert-confirm-delete" id="confirm-delete-' + obj.id + '">' +
                                '<span style="margin-right:5px;">Delete ' + obj.value + ' ?</span>' +
                                '<div class="delete-group-options"><a class="delete-group-yes btn btn-rg btn-rg-confirm btn-light-grey"><span class="glyphicon glyphicon-ok"></span> Yes</a>' +
                                '<a class="delete-group-no btn btn-rg btn-rg-cancel btn-light-grey"><span class="glyphicon glyphicon-remove"></span> No</a>' +
                                '</div></div></div>');
                    }
                }

                if (action == 'delete_reading_group') {
                    $("div#" + id).remove();
                }
                //To load jeditable box
                load_jeditable_readingGroups();

                //To Dynamically update dropdown list in the table
                $('tbody td.my_reading_group').editable('destroy');
                get_reading_groups_list('get_reading_groups_list', 'my_reading_group');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }

        });
    }

    String.prototype.capitalize = function () {
        return this.replace(/(?:^|\s)\S/g, function (a) {
            return a.toUpperCase();
        });
    };

    /*Get reading groups list dynamically from database*/
    function get_reading_groups_list(action, value) {
        $.ajax({
            url: edit_group_data_path,
            type: "POST",
            data: {
                'action': action,
                'value': value
            },
            success: function (data) {
                var obj = JSON.parse(data);
                $('tbody td.my_reading_group').editable(function (value, settings) {
                    var id = $(this).parent().attr('id').replace('user-', '');
                    var meta = $(this).attr('class');

                    edit_user_data(id, meta, value);
                    return (value);
                }, {
                    data: data,
                    //  loadurl : '<?php echo get_template_directory_uri() . '/reading_groups_list.php'; ?>',
                    'placeholder': 'Select reading group',
                    type: 'select',
                    callback: function (value, settings) {
                        console.log(value);
                    }
                }, {
                    "onblur": 'submit'
                });

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
    }
    /* Add or delete assign work for individual group
     * 
     * @param {type} id - teacher ID
     * @param {type} value - array of assign work 
     * @param {type} group - group ID
     */
    function show_work(id, group, value, action) {
        $.ajax({
            url: edit_group_data_path,
            type: "POST",
            data: {
                'id': id,
                'group': group,
                'value': value,
                'action': action
            },
            success: function (data) {

                //console.log(value);
                var obj = JSON.parse(data);
                /*when user clicks on show btn*/
                //console.log(obj.length);
                if (action == 'show') {
                    if (obj.length) {
                        $('.assigned-work, .error-msg').children().remove();
                        if ($('.assigned-work').hasClass('hide')) {
                            $('.assigned-work').toggleClass("hide");
                        }
                        ;

                        if (!$('.assigned-work').children().hasClass('assigned-work-inner')) {
                            $('.assigned-work').wrapInner('<div class="assigned-work-inner"><div class="assigned-work-thumbs-wrapper"></div></div>');
                            $('.assigned-work-thumbs-wrapper').append('<span class="remove-all"><span class="glyphicon glyphicon-trash"></span> Remove All books</span>');

                        }
                        for (i = 0; i < obj.length; i++) {
                            $('.assigned-work-thumbs-wrapper').append(
                                    '<div class="thumb accordion-shelf-book col-xs-4 col-sm-2" data-level="level-' + obj[i]['level'] + '" data-id="' + obj[i]['id'] + '"> ' +
                                    /* '<div class="checkbox-container"><input type="checkbox" name="vehicle" class="post-'+ obj[i]['id'] + '" checked> <br/></div>' + */
                                    '<div class="checkbox-container"><span class="glyphicon glyphicon-remove assigned-book-remove" id="post-' + obj[i]['id'] + '"></span></div>' +
                                    '<img class="img-responsive img-rounded" alt="' + obj[i]['title'] + '" src="' + obj[i]['imgsrc'] + '" style="width:200px; height:267px;"/>' +
                                    '<svg width="50" height="50" class="sash-readinglevel"><polygon points="0,50 50,50 50,0 " class="triangle" />' +
                                    '</div>');
                        }

                        if (!$('.assigned-work').children().hasClass('.bg-shelf-bottom')) {
                            $('.assigned-work-thumbs-wrapper').append('<div class="bg-shelf-bottom bg-shelf-bottom-default"></div>');
                        }
                    } else {
                        $('.assigned-work').removeClass('hide').addClass("hide");
                        $('.error-msg').children().remove();
                        $(".error-msg").append("<div class='error-msg-container'><div class='error-msg-container-inner'><p>You haven't added any books to " + value + " yet. Select reading level to start adding books.</p></div></div>");
                    }
                }


            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
    }

    function edit_assign_work(id, group, value, action, level) {
        $.ajax({
            url: edit_group_data_path,
            type: "POST",
            data: {
                'id': id,
                'group': group,
                'value': value,
                'action': action
            },
            success: function (data) {
                console.log('delete' + group + action);
                var obj = JSON.parse(data);
                if (action == 'submit') {
                    if ($('.assigned-work').hasClass('hide')) {
                        $('.assigned-work').removeClass('hide');
                    }
                    $('.assigned-work').children('span').remove();
                    $('.error-msg').children().remove();

                    if (!$('.assigned-work').children().hasClass('assigned-work-inner')) {
                        $('.assigned-work').wrapInner('<div class="assigned-work-inner"><div class="assigned-work-thumbs-wrapper"></div></div>');
                        $('.assigned-work-thumbs-wrapper').append('<span class="remove-all"><span class="glyphicon glyphicon-trash"></span> Remove All books</span>');
                    }

                    for (i = 0; i < obj.length; i++) {
                        $('.assigned-work-thumbs-wrapper').append(
                                '<div class="thumb accordion-shelf-book col-xs-4 col-sm-2" data-level="level-' + level + '" data-id="' + obj[i]['id'] + '"> ' +
                                '<div class="checkbox-container"><span class="glyphicon glyphicon-remove assigned-book-remove" id="post-' + obj[i]['id'] + '"></span></div>' +
                                '<img class="img-responsive img-rounded" alt="' + obj[i]['title'] + '" src="' + obj[i]['imgsrc'] + '" style="width:200px; height:267px;"/>' +
                                '<svg width="50" height="50" class="sash-readinglevel"><polygon points="0,50 50,50 50,0 " class="triangle" />' +
                                '</div>');
                    }
                    $('.assigned-work').children().find('div.bg-shelf-bottom').remove();

                    if (!$('.assigned-work').children().hasClass('.bg-shelf-bottom')) {
                        $('.assigned-work-thumbs-wrapper').append('<div class="bg-shelf-bottom bg-shelf-bottom-default"></div>');
                    }

                } else if (action == 'delete') {
                    if (value) {
                        $('div.assigned-work div[data-id=' + value + ']').fadeOut(500, function () {
                            $(this).remove();
                        });
                    }
                    if (obj.empty == true) {
                        console.log('Last book' + obj.empty);
                        $('.assigned-work').children().remove();
                        $('.assigned-work').addClass('hide');

                        $(".error-msg").children().remove();
                        $(".error-msg").append("<div class='error-msg-container'><div class='error-msg-container-inner'><p>You haven't added any books to " + value + " yet. Select reading level to start adding books.</p></div></div>");
                    }
                } else
                if (action == 'deleteAll') {
                    if (obj.value == 'deleted') {
                        $('.assigned-work').addClass('hide').children().remove();
                        $('.reading-level').children().removeClass('selected');
                        $('.books-lists').children().remove();
                        $(".error-msg").children().remove();
                        $(".error-msg").append("<div class='error-msg-container'><div class='error-msg-container-inner'> <p>You haven't added any books to " + value + " yet. Select reading level to start adding books.</p></div></div>");
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            }
        });
    }

    /*
     * Retrieve query by reading level
     *@param {type} current - reading level slug
     *@param {type} $paged - Next page link
     *
     */
    function reload_books_query($current, $selected_group, $selected_groupName) {
        $.ajax({
            url: show_books_from_shelf,
            type: "POST",
            //dataType: 'json',
            data: {
                'id': $current,
                'selected_reading_group': $selected_group
            },
            success: function (data) {
                //  var obj = JSON.parse(data); 
                $('.books-lists').children().remove();
                if (!data)
                    console.log('You have reached the bottom');

                $('.books-lists').append('<p class="book-list-msg">Click or tap to add books to ' + $selected_groupName + '</p>');
                $('.books-lists').append(data);
                $('.books-lists').append('<div class="clearfix"></div>');
                $('.books-lists .accordion-shelf-book').equalHeights();
                //console.log(data);
            },
            complete: function () {

            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.ajaxOptions);
                console.log(thrownError);
            }
        });
    }
})(jQuery);
