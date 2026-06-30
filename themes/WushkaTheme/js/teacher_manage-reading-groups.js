jQuery(document).ready(function ($) {

    //console.log = function() {}

    /* -------------------------------
     * 		Manage Reading groups JS
     * ------------------------------- */
    //console.log = function() {}
    //Global Variables
    var o_teacher = {
        id_hash: int_teacher_hash,
        wp_hash: $('#_wp_rdm_hash').attr('value'),
        class_id: null
    };
    var book_item = {
        id: null,
        title: null,
        elem: null,
        state: true
    };
    var reading_group = {id: null, name: null, el: null, new_id: null, new_name: null, new_el: null, empty: null};
    var reading_level = {id: null, name: null, el: null, new_id: null, new_name: null, new_el: null, page: null, sound: null};
    var window_contents = {
        type: null,
        heading: null,
        sub: null,
        content: null,
        submit: null,
        close: null,
        loading: null
    };

    /* ---------- Identifiers ---------- */
    //Menu Identifiers
    var group_menu_wrap = $('.list-group.group-menu-list');
    var level_menu_wrap = $('.list-group.level-menu-list');

    //Content Wrap Identifiers
    var group_content_wrap = $('.list-group.group-content-list');
    var level_content_wrap = $('.level-content-wrap');
    var users_content_wrap = $('.users-content-wrap');

    var page_section_window = $(document);
    var btn_timer = null;

    /* ---------- Click Events ---------- */
    //Group Settings Click Event
    $('.btn.btn-edit-group').on('click', function () {
        var e_item = $('.group-menu-item.active');
        var e_btn = $('#group-edit-modal');

        if (e_item.length <= 0) {
            e_btn.modal('hide');
            $('#no-group-modal').modal('show');
            return false;
        }
        //Add Group Name to Settings Window
        var group_name = e_item.clone().children().remove().end().text().trim();
        e_btn.find('.modal-header h3').empty().append(group_name);
    });

    /* --- Menu Click Events --- */
    //Group Menu Click
    group_menu_wrap.on('click', 'a', function () {
        var e_this = $(this);
        load_group_new(e_this);
    });

    //Group Menu Select
    $(document).on('change', '.group-menu-list select', function () {
        var e_this = $('.group-menu-list').find('select').find('option:selected');
        if (e_this.attr('id') == 'reading-group-new') {
            $('#reading-group-modal').modal('toggle');
            open_reading_group_window('new_group');
        } else {
            load_group_new(e_this);
        }
    });

    //Level Menu Click — phonics phase header: toggle sounds accordion
    level_menu_wrap.on('click', 'a.phonics-phase-header', function (e) {
        e.preventDefault();
        $(this).closest('.phonics-phase-wrap').find('.phonics-sounds-list').slideToggle(200);
        $(this).find('.phonics-chevron').toggleClass('glyphicon-chevron-right glyphicon-chevron-down');
    });

    //Level Menu Click — phonics sound item: load books filtered by sound
    level_menu_wrap.on('click', 'a.phonics-sound-item', function (e) {
        e.preventDefault();
        load_sound_new($(this));
    });

    //Level Menu Click — levelled books items: existing behavior, clear any sound filter
    level_menu_wrap.on('click', 'a:not(.phonics-phase-header):not(.phonics-sound-item)', function () {
        reading_level.sound = null;
        load_level_new($(this));
    });


    //Level Menu Select
    $(document).on('change', '.level-menu-list select', function () {
        var e_this = $('.level-menu-list').find('select').find('option:selected');
        load_level_new(e_this);
    });

    // RUN ON PAGE LOADED
    //Switch Class
    $('li.class-list.class-switch').on('click', load_class_new);
    //Load Draggable UI
    load_drag();
    //If mobile/tablet format: convert menu lists to selection inputs
    convert_lists_to_selections('all');


    //Content Section Buttons
    $(document).on('click', '.book-item button[data-id="book-add"]', add_group_book);
    $(document).on('click', '.book-item button[data-id="book-delete"]', delete_group_book);
    $(document).on('click', '.book-item button[data-id="book-archive"]', archive_book);
    $(document).on('click', '.book-item button[data-id="book-view"]', view_book_details);

    $(document).on('click', '.group-wrap.student-wrap .btn-assign', button_window_view_students);

    //Level Pagination
    $(document).on('click', '.level-wrap.buttons-wrap .level-pages', load_level_page);
    $(document).on('click', '#navigation-next, #navigation-previous', load_level_paginate);

    //Group Edit Options
    //Create Group
    group_menu_wrap.on('click', '#reading-group-new', function () {
        open_reading_group_window('new_group');
    });
    //Rename Group
    $(document).on('click', '.modal #rename-group', function () {
        open_reading_group_window('rename_group');
    });
    //Archive Books
    $(document).on('click', '.modal #archive-books', archive_group);
    //Delete Group
    $(document).on('click', '.modal #delete-group', function () {
        open_reading_group_window('delete_group');
    });

    //Window Section
    var window_wrap = $('#reading-group-modal');
    var window_error = window_wrap.find('label.error-msg');

    //Window Events
    window_wrap.on('click', '.student-wrap p.student-name', open_student_window_menu);
    window_wrap.on('click', '.student-wrap input.menu-close', close_student_window_menu);
    window_wrap.on('mouseleave', close_student_window_menus);
    //Open Move to Menu
    window_wrap.on('click', '.window-student-wrap .student-wrap input.move', open_move_student_window_menu);
    window_wrap.on('click', '.window-student-wrap .student-wrap input.add', add_student_window_menu);
    window_wrap.on('click', '.window-student-wrap .student-wrap input.remove', remove_student_window_menu);
    window_wrap.on('click', '.window-student-wrap .student-wrap input.group', move_student_window_menu);
    //Window Functions Submit Button
    window_wrap.find('#submit-modal').on('click', page_window_button_submit);

    //View Students
    var student_current_hash = null;
    var a_students_data = [];

    //Load Functions
    //menu_group_empty_check();
    load_default_window_content();

    /* -----------------------------------------------------------------
     *
     *					----- MENU SECTION EVENTS -----
     *
     * -----------------------------------------------------------------*/
    function load_group_new(e_group) {
        var this_item = e_group;
        if (this_item.hasClass('loading')) {
            return false;
        }
        if (this_item.attr('id') == 'reading-group-new') {
            return true;
        }

        reset_reading_group();

        if (store_current_group() === false) {
            console.log('Could Not Store Group Data: abort action');
//    		return false;
        }

        if (store_clicked_group(this_item) === false) {
            console.log('Could Not Store New Group Data: abort action');
            return false;
        }

        //No New Group Functionality Yet, Stop Process Here
        if (reading_group.id == reading_group.new_id) {
            console.log('Double new, abort');
            return false;
        }

        reading_group.id = reading_group.new_id;
        reading_group.new_el.addClass('loading');

        console.log('Load New Group: ' + reading_group.id);
        $.ajax(ajax_data('load_group'));

    }

    function load_level_new(e_level) {
        var this_item = e_level;
        if (this_item.hasClass('loading')) {
            return false;
        }

        reset_reading_level();

        if (store_current_group() === false) {
            console.log('Could Not Store Group Data: abort action');
//    		return false;
        }

        if (store_current_level() === false) {
            console.log('Could Not Store Current Level Data: abort action');
            return false;
        }

        if (store_clicked_level(this_item) === false) {
            console.log('Could Not Store New Level Data: abort action');
            return false;
        }


        //No New Group Functionality Yet, Stop Process Here
        if (reading_level.id == reading_level.new_id) {
            return false;
        }

        reading_level.id = reading_level.new_id;
        reading_level.page = 1;
        reading_level.new_el.addClass('loading');

        load_level();
    }

    function load_sound_new(e_sound) {
        if (e_sound.hasClass('loading')) return false;

        reset_reading_level();
        store_current_group();
        store_current_level();

        var phase_id  = e_sound.data('level-id');
        var sound_val = e_sound.data('sound');
        if (!phase_id || !sound_val) return false;

        if (reading_level.id == phase_id && reading_level.sound == sound_val) return false;

        reading_level.id     = phase_id;
        reading_level.sound  = sound_val;
        reading_level.page   = 1;
        reading_level.new_el = e_sound;
        e_sound.addClass('loading');
        level_content_wrap.empty().append(
            '<div class="level-content-loading">' +
            '<img src="' + thm_tmp_fnc_pth + '/img/wushka-load-4.GIF" width="60" height="60" alt="Loading..." />' +
            '</div>'
        );
        load_level();
    }

    function load_class_new() {
        var this_class = $(this);
        o_teacher.class_id = null;
        if (this_class.hasClass('loading') || this_class.hasClass('active')) {
            return false;
        }

        this_class.addClass('loading');

        var class_id = $(this_class).find('a').attr('href').replace('#', '').replace('-class', '').trim();
        console.log('Loading Class ' + class_id);

        o_teacher.class_id = class_id;


        $.ajax(ajax_data('load_class_groups'));
    }

    function load_level_paginate() {
        reset_reading_level();

        if (store_current_level() === false) {
            console.log('Could Not Store Level Data: abort action');
            return false;
        }


        var s_page = null;
        //Determine if Going to Next or Previous Page
        if ($(this).attr('id') == 'navigation-next') {
            s_page = 'next';
        } else if ($(this).attr('id') == 'navigation-previous') {
            s_page = 'prev';
        }
        //If not set, abort
        if (s_page == null) {
            return false;
        }

        //Get Current Page.
        var page_no = $(document).find('.level-wrap.buttons-wrap .level-pages.active').attr('id').replace('page-', '').trim();
        console.log('Current Page:' + page_no);
        //Don't allow previous on page 1.
        if (page_no == 1 && s_page == 'prev') {
            console.log('Cant go back from page 1');
            return false;
        }
        var last_page = $(document).find('.level-wrap.buttons-wrap .level-pages.last').attr('id').replace('page-', '').trim();
        //Dont allow next on last page.
        if (page_no == last_page && s_page == 'next') {
            console.log('Cant go passed last page');
            return false;
        }

        //Change Page No
        if (s_page == 'next') {
            ++page_no;
        } else if (s_page == 'prev') {
            --page_no;
        }

        //console.log('Changing To This Page :' + page_no);

        reading_level.page = 1;

        load_level();
    }

    function load_level_page(e) {
        reset_reading_level();
        e.preventDefault();
        var this_btn = $(this);

        if (this_btn.hasClass('active')) {
            return false;
        }

        if (this_btn.hasClass('loading')) {
            return false;
        }

        this_btn.addClass('loading');

        if (store_current_level() === false) {
            console.log('Could Not Store Level Data: abort action');
            return false;
        }


        //Get Current Page.
        //var page_no = this_btn.attr('id').replace('page-', '').trim();
        //console.log('Clicked Page:' + page_no);
        reading_level.page = 1;

        load_level();
    }

    function load_level_reload() {
        clearTimeout(btn_timer);
        btn_timer = setTimeout(function () {

            reset_reading_level();
            if (store_current_level() === false) {
                console.log('Could Not Store Level Data: abort action');
                return false;
            }


            if (reading_level.id == 'new') {
                console.log('No Level selected, no need to reload.');
                return false;
            }

            //Get Current Page
            //var page_no = $(document).find('.level-wrap.buttons-wrap .level-pages.active').attr('id').replace('page-', '').trim();
            //page_no = 1;
            //console.log('Current Page:' + page_no);
            reading_level.page = 1;

            load_level();
        }, 1000);
    }

    function load_level() {
        if (store_current_group() === false) {
            console.log('Could Not Store Group Data: abort action');
//    		return false;
        }

        $.ajax(ajax_data('load_level'));
    }

    /* -----------------------------------------------------------------
     *
     * 				 	----- BOOK SPECIFIC EVENTS -----
     *
     * ----------------------------------------------------------------- */
    function add_group_book(e) {
        e.stopPropagation();
        e.preventDefault();

        var this_btn = $(this);
        var book_wrap = this_btn.parents('.book-item');

        if (store_current_book(book_wrap) === false) {
            console.log('Could Not Find Current Book');
            return false;
        }

        if (store_current_group() === false || reading_group.id == 'new') {
            console.log('No Group selected, abort.');
            return false;
        }

        if (store_current_class() === false) {
            console.log('No Class selected, abort.');
            return false;
        }

        //Check Book Does not Already exist In Group
        var check_added = false;
        if (group_content_wrap.find('.group-content-item').length > 0) {
            group_content_wrap.find('.group-content-item').map(function () {
                var this_book_id = $(this).attr('id').replace('book-', '').trim();
                console.log('match this book: ' + this_book_id);
                if (this_book_id == book_item.id) {
                    console.log('This Book Is already in the group');
                    check_added = true;
                }
            });

            if (check_added === true) {
                return false;
            }
        }

        //Store Thumbnail img for reading-group update
        console.log('----- Add Book to Reading Group -----');
        load_level_reload();
        $.ajax(ajax_data('add_group_book'));
    }

    function delete_group_book(e) {
        e.preventDefault();
        e.stopPropagation();
        var this_btn = $(this);

        if (this_btn.hasClass('deleting')) {
            return false;
        }

        var book_wrap = this_btn.parents('.book-item');

        if (store_current_book(book_wrap) === false) {
            console.log('Could Not Find Current Book');
            return false;
        }

        if (store_current_group() === false || reading_group.id == 'new') {
            console.log('No Group selected, abort.');
            return false;
        }

        if (store_current_class() === false) {
            console.log('No Class selected, abort.');
            return false;
        }

        console.log('----- Remove Book From Reading Group -----');
        console.log('Book ID: ' + book_item.id);
        console.log('Group ID: ' + reading_group.id);

        this_btn.addClass('deleting');
        load_level_reload();
        $.ajax(ajax_data('delete_group_book'));
    }

    /* -----------------------------------------------------------------
     *
     *					----- STORE MENU VARIABLES -----
     *
     * -----------------------------------------------------------------*/
    function store_current_group() {
        console.log('----- Storing Current Group Data -----');
        var current_item = group_menu_wrap.find('.group-menu-item.active');
        if (current_item == null || current_item.length <= 0) {
            console.log('No Level Selected');
            reading_group.el = null;
            reading_group.id = 'new';
            reading_group.name = null;
        } else {
            reading_group.el = current_item;
            reading_group.id = reading_group.el.attr('id').replace('reading-group-', '').trim();
            reading_group.name = reading_group.el.clone().children().remove().end().text().trim();
        }

        if (reading_group.id == null || reading_group.id.length <= 0) {
            console.log('Error: Could Not Determine Current Group');
            return false;
        }

        console.log('--------------- Current Group Stored ---------------');
        console.log('el     : ' + reading_group.el);
        console.log('ID 	: ' + reading_group.id);
        console.log('Group 	: ' + reading_group.name);
        return true;
    }

    function store_clicked_group(menu_item) {
        console.log('----- Storing Clicked Group Data -----');
        if (menu_item.length <= 0) {
            console.log('Error: Could Not Determine Clicked Group');
            return false;
        }
        reading_group.new_el = menu_item;
        reading_group.new_id = reading_group.new_el.attr('id').replace('reading-group-', '').trim();
        reading_group.new_name = reading_group.new_el.text().trim();

        if (reading_group.new_id.length <= 0) {
            console.log('Error: Could Not Determine Clicked Group');
            return false;
        }
        console.log('ID 	: ' + reading_group.new_id);
        console.log('Name 	: ' + reading_group.new_name);
        console.log('--------------- Clicked Group Stored ---------------');
        return true;
    }

    function store_current_level() {
        console.log('----- Storing Current Level Data -----');
        var current_item = level_menu_wrap.find('.level-menu-item.active');
        if (current_item == null || current_item.length <= 0) {
            console.log('No Level Selected');
            reading_level.el = null;
            reading_level.id = 'new';
            reading_level.name = null;
        } else {
            reading_level.el = current_item;
            if (current_item.hasClass('phonics-sound-item')) {
                reading_level.id    = current_item.data('level-id');
                reading_level.sound = current_item.data('sound');
            } else {
                reading_level.id    = current_item.attr('id').replace('reading-level-', '').trim();
                reading_level.sound = null;
            }
            reading_level.name = current_item.text().trim();
        }

        if (reading_level.id == null || reading_level.id.toString().length <= 0) {
            console.log('Error: Could Not Determine Current Level');
            return false;
        }

        console.log('ID 	: ' + reading_level.id);
        console.log('Level 	: ' + reading_level.name);

        console.log('--------------- Current Level Stored ---------------');
        return true;
    }

    function store_clicked_level(menu_item) {
        console.log('----- Storing New Level Data -----');
        if (menu_item == null || menu_item.length <= 0) {
            return false;
        }

        reading_level.new_el = menu_item;
        reading_level.new_id = reading_level.new_el.attr('id').replace('reading-level-', '').trim();
        reading_level.new_name = reading_level.new_el.text().trim();

        if (reading_level.new_id == null || reading_level.new_id.length <= 0) {
            console.log('Error: Could Not Determine Clicked Level');
            return false;
        }

        console.log('---------------  Clicked Level Stored ---------------');

        console.log('Clicked ID 	: ' + reading_level.new_id);
        console.log('Clicked Level 	: ' + reading_level.new_name);

        return true;
    }

    function store_current_book(this_book) {
        console.log('----- Storing Current Book -----');
        if (this_book == null || this_book.length <= 0) {
            return false;
        }

        var book_id = this_book.attr('id').replace('book-', '').trim();

        if (book_id == null || book_id.length <= 0) {
            console.log('Error: Could Not Determine Book ID');
            return false;
        }
        book_item.id = book_id;
        console.log('--------------- Stored ---------------');
        console.log('Book ID	: ' + book_item.id);

        test_book_id = book_item.id;

        return true;
    }

    function store_current_class() {
        console.log('----- Store Current Class -----');
        var class_id = null;
        if ($('li.class-list.class-switch.active').length > 0) {
            class_id = $('li.class-list.class-switch.active a').attr('href').replace('#', '').replace('-class', '').trim();
            console.log('Current Class ID = ' + class_id);
        }

        if (class_id === undefined || class_id == null || class_id.length <= 0) {
            console.log('Error: Could Not Find Class ID');
            return false;
        }

        o_teacher.class_id = class_id;

        console.log('----- Stored Class -----');
        return true;
    }

    /* ---------- ARCHIVING ---------- */
    function archive_group() {
        if (store_current_group() === false) {
            console.log('Could Not Store Group Data: abort action');
            return false;
        }

        book_item.id = 'all';

        open_reading_group_window('archive_books_in_group');
        //$.ajax( ajax_data('archive_books_in_group') );
    }

    function archive_book(e) {
        e.preventDefault();
        e.stopPropagation();
        if (store_current_group() === false) {
            console.log('Could Not Store Group Data: abort action');
            return false;
        }

        var book_wrap = $(this).parents('.book-item');
        if (store_current_book(book_wrap) === false) {
            console.log('Could Not Store Group Data: abort action');
            return false;
        }

        console.log('Archive These Book: ' + book_item.id);

        $.ajax(ajax_data('archive_books_in_group'));
    }

    /* --------- View Book Details ---------- */
    function view_book_details(e) {
        e.preventDefault();
        e.stopPropagation();

        var this_book = $(this).parents('.book-item');
        if (store_current_book(this_book) === false) {
            console.log('Could Not Store Group Data: abort action');
            return false;
        }

        console.log('Open Book Details');

        $.ajax(ajax_data('load_book'));
        open_reading_group_window('load_book');
    }

    /* ----- View Students List ----- */
    function button_window_view_students() {
        while (a_students_data.length > 0) {
            a_students_data.pop();
        }

        if (store_current_group() === false) {
            console.log('Could Not Store Group Data: abort action');
            return false;
        }

        open_reading_group_window('update_students');
    }

    /* Open Menu For Individual Students */
    function open_student_window_menu(e) {
        e.stopPropagation();
        e.preventDefault();
        if ($(this).parents('.student-wrap').hasClass('edit')) {
            return false;
        }
        close_student_window_menus();

        $(this).parent().addClass('menu');
    }

    /* Close Menu For Individual Students */
    function close_student_window_menu(e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).parents('.student-wrap').removeClass('menu');
        $(this).parents('.student-wrap').find('.student-menu-groups').removeClass('move');
    }

    function close_student_window_menus() {
        var e_content = $('.window-section.content');
        e_content.find('.student-wrap').removeClass('menu');
        e_content.find('.student-menu-groups').removeClass('move');
    }

    /* Open The List of Groups to move Student to */
    function open_move_student_window_menu(e) {
        e.stopPropagation();
        e.preventDefault();
        $(this).parent().find('.student-menu-groups').addClass('move');
    }

    // ----- Add Student to Current Reading Group ----- \\
    function add_student_window_menu(e) {
        e.stopPropagation();
        if ($(this).parents('.student-wrap').hasClass('edit')) {
            return false;
        }
        console.log('----- Add Current Student to Reading Group -----');

        student_current_hash = $(this).parents('.student-wrap').attr('id').replace('student-', '').trim();

        a_students_data.push({student: student_current_hash, key: reading_group.id});

        console.log('Adding Student (' + student_current_hash + ') to current Reading Group (' + reading_group.id + ')');
        $(this).parents('.student-wrap').removeClass('menu');
        $(this).parents('.student-wrap').find('.student-menu-groups').removeClass('move');
        $(this).parents('.student-wrap').addClass('edit');
        $('.window-section.content').find('label.save-msg').fadeTo(100, 1);
    }

    // ----- Remove Student from Current Reading Group ----- \\
    function remove_student_window_menu(e) {
        e.stopPropagation();

        if ($(this).parents('.student-wrap').hasClass('edit')) {
            return false;
        }

        console.log('----- Remove Current Student from Reading Group -----');

        student_current_hash = $(this).parents('.student-wrap').attr('id').replace('student-', '').trim();
        a_students_data.push({student: student_current_hash, key: null});

        console.log('Remove Student (' + student_current_hash + ') From Current Reading Group');

        $(this).parents('.student-wrap').removeClass('menu');
        $(this).parents('.student-wrap').find('.student-menu-groups').removeClass('move');
        $(this).parents('.student-wrap').addClass('edit');

        $('.window-section.content').find('label.save-msg').fadeTo(100, 1);
    }

    // ----- Move Student to new Reading Group ----- \\
    function move_student_window_menu(e) {
        e.stopPropagation();
        if ($(this).parents('.student-wrap').hasClass('edit')) {
            return false;
        }

        console.log('----- Move Current Student to New Reading Group -----');

        student_current_hash = $(this).parents('.student-wrap').attr('id').replace('student-', '').trim();
        var group_new_key = $(this).attr('id').replace('group-group-', 'group-').trim();
        a_students_data.push({student: student_current_hash, key: group_new_key});

        console.log('Moving Student (' + student_current_hash + ') To Current Reading Group (' + group_new_key + ')');
        $(this).parents('.student-wrap').removeClass('menu');
        $(this).parents('.student-wrap').find('.student-menu-groups').removeClass('move');
        $(this).parents('.student-wrap').addClass('edit');
        $('.window-section.content').find('label.save-msg').fadeTo(100, 1);
    }

    function reading_ajax_failure(error_msg) {
        console.log('----- Ajax Error -----');
        if (typeof error_msg == 'undefined' || error_msg == null) {
            error_msg = 'Ajax Failed to Return';
        }
        console.log('The Ajax Function Caused an Error');
        console.log('Reason: ' + error_msg);
        console.log('----------------------');
    }

    function load_group_success(ajax_return) {
        //No Errors, Load Group Data to Page
        group_content_wrap.fadeTo(200, 0, function () {
            group_content_wrap.find('.books-wrap').empty().append(ajax_return.data.books);
            group_content_wrap.fadeTo(200, 1);
        });
        users_content_wrap.fadeTo(200, 0, function () {
            users_content_wrap.find('.users-content-list').empty().append(ajax_return.data.students.current);
            users_content_wrap.fadeTo(200, 1);
        });

        load_level_reload();
        if (group_menu_wrap.find('select').length > 0) {
            if (group_menu_wrap.find('select').find('#empty-option').length > 0) {
                group_menu_wrap.find('select').find('#empty-option').remove();
            }
        }
        console.log('Reading Group Data Has Been Stored From Ajax: Save Page, End Loading State.');
    }

    function load_level_success(ajax_return) {
        //No Errors, Load Group Data to Page
        level_content_wrap.fadeTo(200, 0, function () {

            level_content_wrap.empty().append(ajax_return.data);

            //Add filter after loading new level
            var o_filter = $('.btn.btn-filter.selected');
            if (o_filter.length > 0) {
                var s_filter = o_filter.attr('value').trim();
                filter_level_books(s_filter, false);
            } else {
                console.log('Error: Could Not Filter Level; No filter selected');
            }

            level_content_wrap.fadeTo(200, 1);
        });

        console.log('Data Has Been Stored From Ajax: Save Page, End Loading State.');
        //Save New Group as the Current group
        console.log('----- Ajax Ended -----');
        reload_drag();
    }

    function reset_reading_level() {
        reading_level = {
            el: null,
            id: null,
            name: null,
            new_el: null,
            new_id: null,
            new_name: null,
            page: null,
            sound: null
        };
    }

    function reset_reading_group() {
        reading_group = {
            id: null,
            name: null,
            new_id: null,
            new_name: null,
            element: null,
            empty: null
        };
    }

    function toggle_menu_classes(s_type, b_success) {
        if (s_type == null || typeof s_type == 'undefined') {
            return false;
        }
        if (s_type == 'level' && reading_level.new_el == null) {
            return false;
        } else if (s_type == 'group' && reading_group.new_el == null) {
            return false;
        }


        if (s_type == 'level') {
            if (reading_level.el !== null) {
                reading_level.el.removeClass('active');
            }
            reading_level.new_el.removeClass('loading');

            if (b_success === true) {
                reading_level.new_el.addClass('active');
            } else {
                if (reading_level.el !== null) {
                    reading_level.el.addClass('active');
                }
            }
        } else if (s_type == 'group') {
            if (reading_group.el !== null) {
                reading_group.el.removeClass('active');
            }
            reading_group.new_el.removeClass('loading');

            if (b_success === true) {
                reading_group.new_el.addClass('active');
            } else {
                if (reading_group.el !== null) {
                    reading_group.el.addClass('active');
                }
            }

        }
    }

    /* ---------------------------------------------------------------------
     *
     * 						Add New Reading Group
     *
     *  ---------------------------------------------------------------------
     */
    function page_window_button_submit() {
        if (window_contents.type == null) {
            return false;
        }

        window_error.empty();

        if (validate_window(window_contents.type) === false) {
            console.log('Page Window Failed to Valid. Abort Submit');
            return false;
        }

        /* --- STEP 3 --- */
        $.ajax(ajax_data(window_contents.type));
    }

    function validate_window(window_type) {

        //Validate Inputs
        if (window_type == 'new_group' || window_type == 'rename_group') {
            console.log('Validate new Group Name');
            /* --- STEP 1 --- */
            var group_name_input = window_wrap.find('input[type="text"]#group_name');
            if (validate_variable_check(group_name_input) === false) {
                return false;
            }
            var new_name = group_name_input.prop('value').trim();
            console.log('new group name = ' + new_name);
            if (validate_group_name_input(new_name) === false) {
                return false;
            }

            if (store_current_class() === false) {
                console.log('No Class selected, abort.');
                return false;
            }

            /* --- STEP 2 --- */
            //Check New Group Name is Unique
            var check_title = new_name.toLowerCase();
            var name_good = true;
            console.log('Check All Current Group Names');
            group_menu_wrap.find('.group-menu-item').map(function () {
                var this_title = $(this).text().trim().toLowerCase();
                console.log('does new group match this: ' + this_title);
                if (this_title == check_title) {
                    name_good = false;
                    console.log('Group Name already exists!');
                }
            });
            if (name_good === false) {
                console.log('This Group Name is already being used');
                window_error.empty().append('This Group Name is already being used');
                return false;
            }
            console.log('New name is good: save to DB');
            reading_group.new_name = new_name.toLowerCase();
            reading_group.new_id = 'group-' + new_name.replace(' ', '-').trim().toLowerCase();
            console.log('Current id: ' + reading_group.id);
            console.log('Current value: ' + reading_group.name);
            console.log('New id: ' + reading_group.new_id);
            console.log('New Name: ' + reading_group.new_name);

            return true;

        } else if (window_type == 'update_student_groups') {
            if (validate_variable_check(a_students_data) === false) {
                console.log('Student Data Array Failed to Validate');
                return false;
            }

            //window_saving.show().fadeTo(200, 1);

            return true;
        }
    }

    function validate_group_name_input(check_input) {
        //Check Group Name is Defined and not null
        if (validate_variable_check(check_input) === false) {
            console.log('Please Enter a Group Name');
            window_error.empty().append('Please enter a keyword or phrase to save as the new group name');
            return false;
        }

        //Only Numbers and Letters allowed
        if (number_letter_check(check_input) === false) {
            console.log('Numbers and Letters only.');
            window_error.empty().append('Only numbers and letters are allowed for group names');
            return false;
        }

        return true;
    }

    function ajax_data(function_type) {
        if (function_type == 'load_group') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify('load_group'),
                    'group_id': JSON.stringify(reading_group.id)
                },
                error: function () {
                    toggle_menu_classes('group', false);
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    if (validate_ajax_return(ajax_return) !== false) {
                        load_group_success(ajax_return);
                        toggle_menu_classes('group', true);
                        return true;
                    } else {
                        if (ajax_return.msg !== null) {
                            reading_ajax_failure(ajax_return.msg);
                            toggle_menu_classes('group', false);
                        }
                        return false;
                    }
                }
            };
        } else if (function_type == 'load_level') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify('load_level'),
                    'group_id': JSON.stringify(reading_group.id),
                    'level_id': JSON.stringify(reading_level.id),
                    'level_page': JSON.stringify(reading_level.page),
                    'sound_filter': JSON.stringify(reading_level.sound)
                },
                error: function () {
                    toggle_menu_classes('level', false);
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    if (validate_ajax_return(ajax_return) !== false) {
                        toggle_menu_classes('level', true);
                        load_level_success(ajax_return);
                        return true;
                    } else {
                        toggle_menu_classes('level', false);
                        if (ajax_return && ajax_return.msg) {
                            reading_ajax_failure(ajax_return.msg);
                        }
                        return false;
                    }
                }
            };
        } else if (function_type == 'load_class_groups') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify(function_type),
                    'class_id': JSON.stringify(o_teacher.class_id)
                },
                error: function () {
                    reading_ajax_failure('Ajax Did Not Run: Error Method Popped.');
                },
                success: function (ajax_return) {
                    //window_saving.fadeTo(200, 0).hide();
                    if (validate_ajax_return(ajax_return) !== false) {
                        success_load_class_groups(ajax_return);
                    } else {
                        if (ajax_return.msg !== null) {
                            failure_load_class_groups(ajax_return);
                            reading_ajax_failure(ajax_return.msg);
                        }
                    }
                    return false;
                }
            };
        } else if (function_type == 'new_group') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify(function_type),
                    'class_id': JSON.stringify(o_teacher.class_id),
                    'group_new_name': JSON.stringify(reading_group.new_name)
                },
                error: function () {
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    //window_saving.fadeTo(200, 0).hide();
                    if (validate_ajax_return(ajax_return) !== false) {
                        success_new_reading_group(ajax_return, reading_group.new_name);
                    } else {
                        if (ajax_return.msg !== null) {
                            failure_new_reading_group(ajax_return);
                            reading_ajax_failure(ajax_return.msg);
                        }
                    }

                    return false;
                }
            };
        } else if (function_type == 'rename_group') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify(function_type),
                    'group_id': JSON.stringify(reading_group.id),
                    'group_name': JSON.stringify(reading_group.new_name)
                },
                error: function () {
                    //window_saving.fadeTo(200, 0).hide();
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    //window_saving.fadeTo(200, 0).hide();
                    if (validate_ajax_return(ajax_return) !== false) {
                        success_rename_group(ajax_return);
                    } else {
                        if (ajax_return.msg !== null) {
                            failure_new_reading_group(ajax_return);
                        }
                    }

                    return true;
                }
            };
        } else if (function_type == 'delete_group') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify(function_type),
                    'group_id': JSON.stringify(reading_group.id)
                },
                error: function () {
                    //window_saving.fadeTo(200, 0).hide();
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    //window_saving.fadeTo(200, 0).hide();
                    if (validate_ajax_return(ajax_return) !== false) {
                        success_delete_group(ajax_return);
                    } else {
                        if (ajax_return.msg !== null) {
                            failure_delete_group(ajax_return);
                        }
                    }

                    return false;
                }
            };
        } else if (function_type == 'add_group_book') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify(function_type),
                    'book_id': JSON.stringify(book_item.id),
                    'group_id': JSON.stringify(reading_group.id),
                },
                error: function () {
                    //window_saving.fadeTo(200, 0).hide();
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    //window_saving.fadeTo(200, 0).hide();
                    if (validate_ajax_return(ajax_return) !== false) {
                        add_group_book_success(ajax_return);
                    } else {
                        if (ajax_return.msg !== null) {
                            add_group_book_failure(ajax_return);
                        }
                    }

                    return false;
                }
            };

        } else if (function_type == 'delete_group_book') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify(function_type),
                    'book_id': JSON.stringify(book_item.id),
                    'group_id': JSON.stringify(reading_group.id)
                },
                error: function () {
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    if (validate_ajax_return(ajax_return) !== false) {
                        delete_group_book_success(ajax_return);
                    } else {
                        if (ajax_return.msg !== null) {
                            delete_group_book_failure(ajax_return);
                        }
                    }
                    return false;
                },
                complete: function () {
                    if ($('.group-wrap.books-wrap').hasClass('deleting')) {
                        $('.group-wrap.books-wrap').addClass('deleting');
                    }
                }
            };

        } else if (function_type == 'update_students') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify(function_type),
                    'student_data': JSON.stringify(a_students_data),
                    'group_id': JSON.stringify(reading_group.id)
                },
                error: function () {
                    //window_saving.fadeTo(200, 0).hide();
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    //window_saving.fadeTo(200, 0).hide();
                    if (validate_ajax_return(ajax_return) !== false) {
                        update_students_success(ajax_return);
                    } else {
                        if (ajax_return.msg !== null) {
                            update_students_failure(ajax_return);
                        }
                    }
                    return false;
                }
            };

        } else if (function_type == 'archive_books_in_group') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify(function_type),
                    'book_id': JSON.stringify(book_item.id),
                    'group_id': JSON.stringify(reading_group.id)
                },
                error: function () {
                    //window_saving.fadeTo(200, 0).hide();
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    //window_saving.fadeTo(200, 0).hide();
                    if (validate_ajax_return(ajax_return) !== false) {
                        success_archive_books_in_group(ajax_return);
                    } else {
                        if (ajax_return.msg !== null) {
                            failure_archive_books_in_group(ajax_return);
                        }
                    }
                    return false;
                }
            };
        } else if (function_type == 'load_book') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify(function_type),
                    'book_id': JSON.stringify(book_item.id)
                },
                error: function () {
                    //window_saving.fadeTo(200, 0).hide();
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    //window_saving.fadeTo(200, 0).hide();
                    if (validate_ajax_return(ajax_return) !== false) {
                        load_book_success(ajax_return);
                    } else {
                        if (ajax_return.msg !== null) {
                            //load_book_failure(ajax_return);
                        }
                    }
                    return false;
                }
            };
        } else if (function_type == 'load_level_page') {
            return {
                url: thm_tmp_fnc_pth + '/functions/ajax_manage-reading-groups.php',
                type: 'post',
                dataType: 'json',
                data: {
                    'hash_id': JSON.stringify(o_teacher.id_hash),
                    'hash_nonce': JSON.stringify(o_teacher.wp_hash),
                    'ajax_function': JSON.stringify(function_type),
                    'level_id': JSON.stringify(reading_level.id),
                    'level_page': JSON.stringify(reading_level.page),
                    'group_id': JSON.stringify(reading_group.id)
                },
                error: function () {
                    //window_saving.fadeTo(200, 0).hide();
                    reading_ajax_failure('Ajax Did Not Run, Error Method Popped.');
                },
                success: function (ajax_return) {
                    //window_saving.fadeTo(200, 0).hide();
                    if (validate_ajax_return(ajax_return) !== false) {
                        load_level_page_success(ajax_return);
                    } else {
                        if (ajax_return.msg !== null) {
                            load_level_page_failure(ajax_return);
                        }
                    }
                    return false;
                }
            };
        }
    }

    function success_load_class_groups(ajax_return) {
        group_menu_wrap.fadeTo(200, 0, function () {
            group_menu_wrap.empty().append(ajax_return.data.groups);
            convert_lists_to_selections('group');
            group_menu_wrap.fadeTo(200, 1);
        });

        group_content_wrap.fadeTo(200, 0, function () {
            group_content_wrap.find('.books-wrap').empty().append(no_group_item());
            group_content_wrap.fadeTo(200, 1);
        });

        if ($('li.class-switch').hasClass('loading')) {
            $('li.class-switch').removeClass('loading');
        }

    }

    function failure_load_class_groups(ajax_return) {
        //Do Nothing Yet
    }

    function success_new_reading_group(ajax_return) {
        if (reading_group.empty === true) {
            reading_group.empty = false;
        }

        var btn_submit = $('#submit-modal');
        btn_submit.empty().append('Created!');
        btn_submit.addClass('success');

        var btn_timer = null;
        clearTimeout(btn_timer);
        btn_timer = setTimeout(function () {
            var btn_submit = $('#submit-modal');
            btn_submit.removeClass('success');
            btn_submit.empty().append('Submit');
        }, 1000);

        if ($(".panel.panel-default.reading-group-menu").css("color") != "rgb(0, 0, 0)") {
            group_menu_wrap.append(ajax_return.data.new_group);
        } else {
            group_menu_wrap.fadeTo(200, 0, function () {
                if (group_menu_wrap.find('select').length > 0) {
                    group_menu_wrap.append(ajax_return.data.new_group);
                    group_menu_wrap.find('a').each(function () {
                        var el = $(this);
                        $("<option />", {
                            'text': el.text(),
                            'id': el.attr('id'),
                            'class': 'list-group-item group-menu-item',
                            'title': el.attr('title'),
                            'value': el.attr('id')
                        }).appendTo(group_menu_wrap.find('select'));
                        el.remove();
                    });
                    group_menu_wrap.find('select').val('empty-option');
                }
                group_menu_wrap.fadeTo(200, 1);
            });
        }

        load_level_reload();
    }

    function failure_new_reading_group(ajax_return) {
        window_error.empty().append('An Error Occurred Saving your Group.');
    }

    function success_rename_group(ajax_return) {
        var new_name = ajax_return.data.new_name;
        group_menu_wrap.find('.group-menu-item.active').attr('title', new_name);
        group_menu_wrap.find('.group-menu-item.active').text(new_name);
    }

    function success_delete_group(ajax_return) {
        group_menu_wrap.find('.group-menu-item.active').remove();

        group_content_wrap.fadeTo(200, 0, function () {
            group_content_wrap.find('.books-wrap').empty().append(no_group_item());
            group_content_wrap.fadeTo(200, 1);
        });
        users_content_wrap.fadeTo(200, 0, function () {
            users_content_wrap.find('.users-content-list').empty();
            users_content_wrap.fadeTo(200, 1);
        });
    }

    function failure_delete_group(ajax_return) {
        window_error.empty().append('An Error Occurred Deleting This Group.');
    }


    function add_group_book_success(ajax_return) {
        //No Errors, Load Group Data to Page
        group_content_wrap.fadeTo(200, 0, function () {
            if (group_content_wrap.find('.empty-group-item').length > 0) {
                group_content_wrap.find('.empty-group-item').remove();
            }
            group_content_wrap.find('.group-wrap.books-wrap').prepend(ajax_return.data);

            group_content_wrap.fadeTo(200, 1);
        });
    }

    function add_group_book_failure(ajax_return) {
        if (ajax_return.error == 52) {
            $('#duplicate-book-modal').modal('show');
        }
    }

    function delete_group_book_success(ajax_return) {
        $(document).find('.group-content-item#book-' + ajax_return.data).fadeTo(200, 0, function () {
            $(document).find('.group-content-item#book-' + ajax_return.data).remove();
            check_empty_reading_group();
        });
    }

    function delete_group_book_failure(ajax_return) {
        console.log('An Error Occurred Deleting this book. Reloading Group.');
        //load_group();
        book_item.state = true;
    }

    function update_students_success(ajax_return) {
        console.log('Successfully Updated Student Groups');
        //window_saving.fadeTo(200, 0).hide();
        page_section_window.fadeTo(200, 0).hide();
        //Load New Student Group Data
        var curent_wrap = group_content_wrap.find('.group-wrap.student-wrap .current .data-wrap');
        curent_wrap.fadeTo(200, 0, function () {
            curent_wrap.empty().append(ajax_return.data.student_data.current);
            curent_wrap.fadeTo(200, 1);
        });
        var assigned_wrap = group_content_wrap.find('.group-wrap.student-wrap .assigned .data-wrap');
        assigned_wrap.fadeTo(200, 0, function () {
            assigned_wrap.empty().append(ajax_return.data.student_data.assigned);
            assigned_wrap.fadeTo(200, 1);
        });
        var unassigned_wrap = group_content_wrap.find('.group-wrap.student-wrap .unassigned .data-wrap');
        unassigned_wrap.fadeTo(200, 0, function () {
            unassigned_wrap.empty().append(ajax_return.data.student_data.unassigned);
            unassigned_wrap.fadeTo(200, 1);
        });

        while (a_students_data.length > 0) {
            a_students_data.pop();
        }

        window_wrap.find('.student-wrap').removeClass('edit');

    }

    function update_students_failure(ajax_return) {
        while (a_students_data.length > 0) {
            a_students_data.pop();
        }

        window_wrap.find('.student-wrap').removeClass('edit');
        window_wrap.find('label.save-msg').hide().css({'opacity': 0});
        window_wrap.find('label.error-msg').empty().append('There was an error saving your student data');
        console.log('Updating Student Groups Failed');
    }

    function success_archive_books_in_group() {
        console.log('Archive Group Books Succeeded');
        var books_wrap = group_content_wrap.find('.books-wrap');
        books_wrap.fadeTo(200, 0, function () {
            if (book_item.id == 'all') {
                books_wrap.empty();
            } else {
                books_wrap.find('.group-content-item#book-' + book_item.id).remove();
            }

            check_empty_reading_group();
            books_wrap.fadeTo(200, 1);
        });
        load_level_reload();
    }

    function failure_archive_books_in_group() {
        console.log('Archive Group Books Failed');
    }

    function load_book_success(ajax_return) {
        var window = window_wrap.find('.modal-body');
        window.fadeTo(200, 0, function () {
            window.empty().append(ajax_return.data.body);
            window.fadeTo(200, 1);
        });

        var footer = window_wrap.find('.modal-footer');
        footer.fadeTo(200, 0, function () {
            footer.prepend(ajax_return.data.footer);
            footer.fadeTo(200, 1);
        });
    }

    function load_book_failure(ajax_return) {
        console.log('Failed to Load Books');
    }

    function load_level_page_success(ajax_return) {
        //No Errors, Load Group Data to Page
        level_content_wrap.fadeTo(200, 0, function () {
            level_content_wrap.empty().append(ajax_return.data);
            level_content_wrap.fadeTo(200, 1);
        });

        console.log('New Level Page has been loaded From Ajax: Save and Finish Function');
        console.log('----- Ajax Ended -----');
    }

    function load_level_page_failure(ajax_return) {
        console.log('Failed to load New Level Page');
    }

    /* ---------------------------------------------------------------------
     *
     * 						Neutral Functions
     *
     *  ---------------------------------------------------------------------
     */
    //Check a Variable to See if it is undefined and is not null
    function validate_variable_check(check_variable) {
        if (typeof check_variable !== 'undefined' && check_variable !== null && check_variable !== '' && check_variable.length > 0) {
            return true;
        }

        console.log('ERROR: Variable Validation Failed.');
        return false;
    }

    function number_letter_check(input_string) {
        var reg_check = /^[0-9a-zA-Z _]+$/;

        return input_string.match(reg_check);
    }

    //Check the Ajax Return array to verify it is defined/not null
    // And also check the error component is NULL
    function validate_ajax_return(ajax_return) {
        if (typeof ajax_return == 'undefined' || ajax_return == null || ajax_return.length <= 0) {
            reading_ajax_failure('Ajax Return InValid: No Data returned');
            return false;
        } else if (ajax_return.error !== 0) {
            //An Error Has Occured
            console.log('Ajax Return InValid: error data found');
            reading_ajax_failure(ajax_return.msg);
            return false;
        } else {
            console.log('Ajax Return Data Validated');
            return true;
        }
    }

    //Open up Page Window on the Browser, and fill it with
    //content specified by passed variable type
    function open_reading_group_window(window_type) {
        load_default_window_content();
        if (store_current_group() === false) {
            console.log('no current group found: abort action');
            return false;
        }
        $('#submit-modal').attr('data-dismiss', 'modal');

        $('#reading-group-modal').removeClass('book-detail');

        console.log('Function Start: Open Reading Group Window (' + window_type + ')');
        if (validate_variable_check(window_type) === false) {
            console.log('Function Abort: invalid window type');
            return false;
        }

        if (window_type == 'new_group') {
            page_window_new_group();
        } else if (window_type == 'rename_group') {
            page_window_rename_group();
        } else if (window_type == 'delete_group') {
            page_window_delete_group();
        } else if (window_type == 'update_students') {
            page_window_view_students();
        } else if (window_type == 'load_book') {
            $('#reading-group-modal').addClass('book-detail');
            page_window_load_book();
        } else if (window_type == 'archive_books_in_group') {
            page_window_archive_group();
        }

        window_contents.type = window_type;

        build_window_content();

        console.log('----- Function End -----');
    }

    function build_window_content() {
        if (window_contents.heading !== null && window_contents.heading !== '') {
            window_wrap.find('.modal-header').show();
        }
        if (window_contents.content !== null && window_contents.content !== '') {
            window_wrap.find('.modal-body').show();
        } else {
            window_wrap.find('.modal-footer').addClass('no-body');
        }

        window_wrap.find('.modal-header h3').empty().append(window_contents.heading);
        window_wrap.find('.modal-header label.subheading').empty().append(window_contents.sub);
        window_wrap.find('.modal-body').empty().append(window_contents.content);
        //open_modal.find('.modal-footer .btn-window.window-submit'	).attr('value', window_contents.submit	);
        //open_modal.find('.modal-footer .btn-window.window-close'	).attr('value', window_contents.close	);
        //window_wrap.find('.window-section.footer .ppwindow-close'  ).attr('value', window_contents.close   );
        window_wrap.find('.modal-footer label.error-msg').empty();
        window_wrap.find('.modal-footer label.save-msg').empty();

        if (window_contents.type == 'update_students') {
            load_student_window_data();
        }
    }

    function page_window_new_group() {
        var html_content = '';

        //Add Content to Window
        html_content = '<div class="window-content input-group">';
        html_content += '<label class="page-window">Group Name: </label>';
        html_content += '<input type="text" class="page-window" id="group_name" placeholder="Enter a group name..." />';
        html_content += '</div>';

        window_contents.type = 'new_group';
        window_contents.heading = 'Add New Group';
        window_contents.sub = 'Enter the name of the group you wish to create';
        window_contents.content = html_content;
        window_contents.submit = 'Create';
        window_contents.close = 'Cancel';
        console.log('new group content window built');
        //$('#submit-modal').attr('data-dismiss', '');

        return true;
    }

    function page_window_rename_group() {
        var html_content = '';
        //Add Content to Window
        html_content = '<div class="window-content input-group">';
        html_content += '<label class="page-window">New Group Name: </label>';
        //Add Original Name to the Input Field
        html_content += '<input type="text" class="page-window" id="group_name" placeholder="Enter a new name..." value="' + reading_group.name + '"/>';
        html_content += '</div>';

        window_contents.type = 'rename_group';
        window_contents.heading = 'Rename Group';
        window_contents.sub = 'Enter a new name for this group';
        window_contents.content = html_content;
        window_contents.submit = 'Save';
        window_contents.close = 'Cancel';

        return true;
    }

    function page_window_delete_group() {
        window_contents.type = 'delete_group';
        window_contents.heading = 'Delete';
        window_contents.sub = 'You\'re about to delete the group \'' + reading_group.name + '\'.<br/>Are you sure you want to do this?';
        window_contents.content = null;
        window_contents.submit = 'Delete';
        window_contents.close = 'Cancel';

        return true;
    }

    function page_window_view_students() {

        var html_content = '<div class="window-content view-students">';
        html_content += '<div class="window-student-wrap current">';
        html_content += '<p>Current Group</p>';
        html_content += '</div>';

        html_content += '<div class="window-student-wrap unassigned">';
        html_content += '<p>Unassigned</p>';
        html_content += '</div>';

        html_content += '<label class="save-msg"><em>Once all your changes are made, press the save button to update your records.</em></label>';
        html_content += '</div>';

        window_contents.type = 'update_student_group';
        window_contents.heading = 'Add Students to Reading Groups';
        window_contents.sub = 'Add, remove and switch out users from this group';
        window_contents.content = html_content;
        window_contents.submit = 'Save';
        window_contents.close = 'Close';

        return true;
    }

    function page_window_load_book() {
        window_contents.type = 'load_book';
        window_contents.heading = null;
        window_contents.sub = null;
        window_contents.content = null;

        window_wrap.find('.btn-submit').hide();
        window_contents.close = 'Close';
    }

    function page_window_archive_group() {
        window_contents.type = 'archive_books_in_group';
        window_contents.heading = 'Archive';
        window_contents.sub = 'You\'re about to archive every book in group \'' + reading_group.name + '\'.<br/>Are you sure you want to do this?';
        window_contents.content = null;
        window_contents.submit = 'Archive';
        window_contents.close = 'Cancel';
    }

    function load_student_window_data() {
        console.log('Load Student Lists');
        //Add Content to Window('.content-wrap.reading-group .student-wrap .page-section.unassigned .data-wrap')
        var current_student = group_content_wrap.find('.page-section.current .data-wrap').clone();
        //console.log(current_student);
        //var assigned = $('.student-wrap .page-section.assigned').clone();
        var unassigned_student = group_content_wrap.find('.page-section.unassigned .data-wrap').clone();
        //console.log(unassigned_student);

        window_wrap.find('.window-student-wrap.current').append(current_student);
        window_wrap.find('.window-student-wrap.unassigned').append(unassigned_student);
        return true;
    }

    function load_default_window_content() {

        window_contents.type = null;
        window_contents.heading = null;
        window_contents.sub = null;
        window_contents.content = null;
        window_contents.submit = 'Submit';
        window_contents.close = 'Close';
        window_contents.loading = 'Saving';

        window_wrap.find('.btn-submit').show();
        window_wrap.find('.modal-header').hide();
        window_wrap.find('.modal-body').hide();
        if (window_wrap.find('.modal-footer').hasClass('no-body')) {
            window_wrap.find('.modal-footer').removeClass('no-body');
        }

        //window_wrap.find('.modal-header h3').empty();
        //window_wrap.find('.modal-header label.subheading').empty();
        window_wrap.find('.modal-body').empty();
        if (window_wrap.find('.modal-footer .temp-btn').length > 0) {
            window_wrap.find('.modal-footer .temp-btn').remove();
        }
        window_wrap.find('.modal-footer label.error-msg').empty();
        window_wrap.find('.modal-footer label.save-msg').empty();

        //Clear window type variable
        submit_window_type = null;
    }

    function check_empty_reading_group() {
        //Check if the reading Group is empty, if so add notice
        if (group_content_wrap.find('.group-content-item').length <= 0) {
            console.log('Reading Group is now Empty, Add Notice');
            $('.group-wrap.books-wrap').empty().append(empty_group_item());
        }
        return true;
    }

    /* ================================================================================
     *
     * 									DRAGGABLE BOOKS
     *
     *
     * ================================================================================ */

    /*	page_section_window.on('mouseover', '.window-student-wrap.current .data-wrap', function(){
     $(this).droppable({
     accept: '.student-wrap',
     hoverClass : 'drag-hover',
     drop: function(event, ui) {
     var this_student = ui.draggable;

     console.log('----- Add Current Student to Reading Group -----');

     student_current_hash = this_student.attr('id').replace('student-', '').trim();

     a_students_data.push({student:student_current_hash, key:reading_group.id});

     console.log('Adding Student ('+student_current_hash+') to current Reading Group ('+reading_group.id+')');
     this_student.removeClass('menu');
     this_student.find('.student-menu-groups').removeClass('move');
     this_student.addClass('edit');
     $('.window-section.content').find('label.save-msg').fadeTo(100,1);
     }
     });
     });
     page_section_window.on('mouseover', '.window-student-wrap.unassigned .data-wrap', function(){
     $(this).droppable({
     accept: '.student-wrap',
     hoverClass : 'drag-hover',
     drop: function(event, ui) {
     var this_student = ui.draggable;
     console.log('----- Remove Current Student from Reading Group -----');

     student_current_hash = this_student.attr('id').replace('student-', '').trim();

     a_students_data.push({student:student_current_hash, key:null});

     console.log('Remove Student ('+student_current_hash+') From Current Reading Group');
     this_student.removeClass('menu');
     this_student.find('.student-menu-groups').removeClass('move');
     this_student.addClass('edit');
     $('.window-section.content').find('label.save-msg').fadeTo(100,1);
     }
     });
     });
     */
    function load_drag() {
        level_content_wrap.on('mouseover', '.level-content-item', function () {
            $(this).draggable({
                revert: 'invalid',
                containment: $('body'),
                cursor: 'pointer',
                helper: 'clone',
                appendTo: 'body',
                zIndex: 1000
            });
        });

        $(document).on('mouseover', '.group-wrap.books-wrap', function (e) {
            $(this).droppable({
                accept: '.level-content-item',
                hoverClass: 'drag-hover',
                drop: function (event, ui) {
                    console.log('Dropped Book into Reading Group');
                    var this_book = ui.draggable;

                    if (store_current_book(this_book) === false) {
                        console.log('Could Not Find Current Group');
                        return false;
                    }
                    if (store_current_group() === false || reading_group.id == 'new') {
                        console.log('Could Not Find Current Group. Abort');
                        return false;
                    }

                    //Check Book Does not Already exist In Group
                    if (group_content_wrap.find('.group-content-item').length > 0) {
                        group_content_wrap.find('.group-content-item').map(function () {
                            var this_book_id = $(this).attr('id').replace('book-', '').trim();
                            console.log('match this book: ' + this_book_id);
                            if (this_book_id == book_item.id) {
                                console.log('This Book Is already in the group');
                                return false;
                            }
                        });

                    }

                    //Store Thumbnail img for reading-group update
                    console.log('----- Add Book to Reading Group -----');
                    load_level_reload();
                    $.ajax(ajax_data('add_group_book'));
                }
            });
        });
    }

    function reload_drag() {
        $('.level-content-item.ui-draggable.ui-draggable-dragging').remove();
        //$('.student-wrap.ui-draggable.ui-draggable-dragging').remove();
    }

    function empty_group_item() {
        var s_empty = '<a href="#" class="list-group-item empty-group-item">' +
            'This reading group does not contain any books. Books can be added by selecting them from the reading levels.' +
            '</a>';

        return s_empty;
    }

    function no_group_item() {
        var s_empty = '<a href="#" class="list-group-item empty-group-item">' +
            'Please create or select a reading group' +
            '</a>';

        return s_empty;
    }

    function empty_student_item() {
        var s_empty = '<a href="#" class="list-group-item users-content-item">' +
            'No students assigned to this group' +
            '</a>';

        return s_empty;
    }

    /* --------------------------------------------------
     *
     * For Non Desktop Devices:
     *
     * Convert Menu Lists into
     * Selection Inputs
     *
     * -------------------------------------------------- */
    function convert_lists_to_selections(s_type) {
        if ($(".panel.panel-default.reading-group-menu").css("color") != "rgb(0, 0, 0)") {
            return false;
        }

        if (s_type == 'all' || s_type == 'level') {
            /* Reading Level */
            var e_level_wrap = $('.list-group.level-menu-list');
            e_level_wrap.fadeTo(200, 0, function () {
                convert_levels_to_selection();
                e_level_wrap.fadeTo(200, 1);
            });
        }

        if (s_type == 'all' || s_type == 'group') {
            /* Reading Group */
            var e_group_wrap = $('.list-group.group-menu-list');
            e_group_wrap.fadeTo(200, 0, function () {
                convert_groups_to_selection();
                e_group_wrap.fadeTo(200, 1);
            });
        }
    }

    function convert_levels_to_selection() {
        var e_list = $('.list-group.level-menu-list');
        if (e_list.find('select').length > 0) {
            return false;
        }

        e_list.prepend('<select />');

        var e_select = e_list.find('select');

        e_list.find('a').each(function () {
            var el = $(this);
            var s_active = null;
            if (el.hasClass('active')) {
                s_active = 'active';
            }

            $("<option />", {
                'text': el.text(),
                'id': el.attr('id'),
                'class': 'list-group-item level-menu-item ' + s_active,
                'title': el.attr('title')
            }).appendTo(e_select);
            el.remove();
        });

        var e_active = $('.level-menu-item.active');
        if (e_active.length > 0) {
            e_select.val(e_active.attr('value'));
        } else {
            e_select.find('option:selected').addClass('active');
        }
    }

    function convert_groups_to_selection() {
        var e_list = $('.list-group.group-menu-list');
        e_list.prepend('<select />');
        var e_select = e_list.find('select');

        e_list.find('a').each(function () {
            var el = $(this);
            var s_active = null;
            if (el.attr('id') == 'reading-group-new') {
                $("<option />", {
                    'text': 'Create New',
                    'id': 'reading-group-new',
                    'class': 'list-group-item reading-group-item',
                    'data-toggle': 'modal',
                    'data-target': '#reading-group-modal',
                    'title': 'Create a new reading group'
                }).appendTo(e_select);
            } else {
                if (el.hasClass('active')) {
                    s_active = 'active';
                }
                $("<option />", {
                    'text': el.text(),
                    'id': el.attr('id'),
                    'class': 'list-group-item group-menu-item ' + s_active,
                    'title': el.attr('title'),
                    'value': el.attr('id')
                }).appendTo(e_select);
            }
            el.remove();
        });

        var e_active = $('.group-menu-item.active');
        if (e_active.length > 0) {
            e_select.val(e_active.attr('value'));
        } else {
            //No Group is actively selected add "Select or Create a Group" option
            $("<option />", {
                'text': 'Select or create a group',
                'id': 'empty-option',
                'class': 'list-group-item group-menu-item',
                'title': 'Select or create a group',
                'value': 'empty-option'
            }).prependTo(e_select);
            e_select.find('option:selected').addClass('active');
        }
    }


    //Filter Fiction
    $('.btn.btn-fiction').on('click', function (e) {
        e.preventDefault();
        e.stopPropagation();

        $('.btn.btn-fiction').removeClass('selected');
        $(this).addClass('selected');

        var s_filter = $(this).attr('value').trim();
        if (s_filter.length > 0) {
            filter_level_books(s_filter, true);
        }

        return true;
    });

    function filter_level_books(s_filter, b_fade) {
        var o_wrap = $('.level-wrap.books-wrap');
        var i_speed = b_fade ? 200 : 0;

        o_wrap.fadeTo(i_speed, 0, function () {
            o_wrap.find('[data-fiction]').hide();

            if (typeof s_filter !== 'undefined' && s_filter.length > 0 && s_filter !== 'both') {
                o_wrap.find('[data-fiction="' + s_filter + '"]').show();
            } else {
                o_wrap.find('[data-fiction]').show();
            }
            o_wrap.fadeTo(i_speed, 1);
        });


        return true;
    }
});