<?php
/*
 * Template Name: Teacher - Reader Records Page Template
 */
get_header();
if( ! isset($current_user) ) {
    global $current_user;
}

$a_page = wushka_load_teacher_page();

include_once 'functions/class_reader_records.php';

$c_records = new Reader_Records();
$c_records->create_class_records($a_page);
$a_results = $c_records->get_results();

//Create Reading Group Options
$a_selectors['groups'][] = '<option value="empty">Select a Group...</option>';
if( ! empty($a_results['data']['groups']) ) {
    foreach( $a_results['data']['groups'] as $i_group => $a_group ) {
        $a_selectors['groups'][] = '<option data-class="' . $a_group['class'] . '" value="' . $a_group['id'] . '">' . $a_group['name'] . '</option>';
    }
}

//Create Reading Level Options
$a_selectors['levels'][] = '<option value="">Select a Level...</option>';
if( ! empty($a_results['data']['levels']) ) {
    foreach( $a_results['data']['levels'] as $i_level => $a_level ) {
        $a_selectors['levels'][] = '<option value="' . $a_level['id'] . '">' . $a_level['name'] . '</option>';
    }
}

//Reading level option to hide for decodables
$taxonomies = get_terms( array(
    'taxonomy' => 'reading-level',
    'orderby' => 'slug',
    'order' => 'ASC',
) );

$gold_and_above_term_id = [];
$count = 1;
foreach($taxonomies as $taxonomy){
    if($count > 8 ){
        $gold_and_above_term_id[] = '"'.$taxonomy->term_id.'"';
    }
    $count++;
}
$gold_and_above_term_id = implode( ', ', $gold_and_above_term_id );

?>
    
    <script>
        var o_records = <?php echo json_encode($a_results['data']['records']); ?>;
        var o_groups = <?php echo json_encode($a_results['data']['groups']); ?>;
        var o_levels = <?php echo json_encode($a_results['data']['levels']); ?>;
        var o_licence = <?php echo json_encode($a_results['class']['licence']); ?>;

        <?php
            /**
             *  To Handle large rows of data
             *
             *  @since 26 May, 2021
             * 
             *  Render data for class only if it exceeds data rows limitation
             *  Will refresh when class link is clicked
             */
            if($a_results['refresh']){
        ?>
        $( document ).ready(function() {
            $('.class-switch a').removeAttr('role').removeAttr('data-toggle');
        });
        //Refresh page
        $(document).on('click', '.class-switch a', function(e){ 
            e.preventDefault();
            location.reload();
        });
        <?php
            }
        ?>
    </script>
    <div class="quiz-results-wrapper padding-y">

        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12">                    
                    <div class="glyphicon-heading text-left">
                        <span class="x2 glyphicon glyphicon-list-alt hidden-xs"></span>
                        <h2 class="glyphicon-heading-text text-left colour-white text-left pb0" style="line-height:39px;">
                            Reader Records
                        </h2>
                        <div class="submodule-right"><?php echo implode('', $a_page['menus']['tabs']); ?></div>
                    </div> 
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-12 col-md-2">
                    <?php echo implode('', $a_page['menus']['lists']); ?>
                </div>
                <div id="display-section" class="col-xs-12 col-md-10 col-lg-8">
                    <div id="records-table" class="table-responsive woocommerce">
                        <table class="table shop_table_responsive table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Student</th>
                                <!--<th>Resource ID</th>-->
                                <th>Reader Title</th>
                                <th>Reading Level</th>
                                <th>Date Read</th>
                                <th>Time Spent Reading</th>
                                <th>Fiction</th>
                                <th>Narrated</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <?php if( current_user_can('teacher') || current_user_can('school') ) { ?>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <button class="btn btn-primary btn-block btn-stamp disabled" id="student-records"
                                        data-id="">
                                    Generate Student Records Report
                                </button>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                <button class="btn btn-primary btn-block btn-stamp disabled" id="class-records"
                                        data-id="">
                                    Generate Class Records Report
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                    <div id="no-results" class="panel panel-info">
                        <div class="panel-heading">
                            <p style="margin-bottom: 0;text-align:center;">
                                Current Filters found 0 matching Records. Please Refine your search parameters
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="glyphicon glyphicon-search"></i> Filter Records
                        </div>
                        <div class="panel-body">
                            <div class="form-group btn-group" role="group">
                                <label>School Hours</label><br>
                                <button type="button" class="btn btn-default btn-filter" data-type="hours"
                                        value="school">
                                    School
                                </button>
                                <button type="button" class="btn btn-default btn-filter" data-type="hours"
                                        value="home">Home
                                </button>
                                <button type="button" class="btn btn-default btn-filter selected" data-type="hours"
                                        value="both">Both
                                </button>
                            </div>
                            <div class="form-group btn-group" role="group">
                                <label>Year Viewable:</label><br/>
                                <button type="button" class="btn btn-default btn-filter btn-current selected"
                                        data-type="years"
                                        value="current">Current
                                </button>
                                <button type="button" class="btn btn-default btn-filter btn-current" data-type="years"
                                        value="all">
                                        <span class="sr-only">All Year View</span>
                                    All
                                </button>
                            </div>
                            <div class="form-group btn-group reading-group-filters" role="group">
                                <div class="class-groups">
                                    <label for="class-group-filter">Reading Groups</label><br>
                                    <select id="class-group-filter" data-type="group">
                                        <?php echo implode('', $a_selectors['groups']); ?>
                                    </select>
                                </div>
                                <div class="user-group">
                                    <label for="user-group-filter">Reading Group</label><br>
                                    <input type="checkbox" id="user-group-filter" value=""/>
                                    <span class="group-name">This Group Name</span>
                                </div>
                                <div class="empty-group">
                                    <label>Reading Group</label><br>

                                    <p><em>No Reading Group</em></p>
                                </div>
                            </div>
                            <div class="form-group btn-group" role="group">
                                <div class="form-group btn-group" role="group">
                                    <label for="level-filter">Reading Levels</label><br>
                                    <select id="level-filter" data-type="level">
                                        <?php echo implode('', $a_selectors['levels']); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group btn-group" role="group">
                                <button class="btn btn-default btn-block" id="reset-filter">
                                    Reset Filters
                                </button>
                                <button class="btn btn-primary btn-block" id="apply-filter">
                                    Filter Records
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--END ROW -->
        </div>
    </div>
    <script>
        jQuery(document).ready(function ($) {
            initiate_table();
            add_btn_to_lists();
            reading_level_option();
            /* ----------------------------------------------------- */
            /*                     jQuery Events
             /* ----------------------------------------------------- */
            //Change Class Trigger
            $('.class-switch').on('click', function () {
                console.log('Switch to New Class First User');
                var o_class = $(this);
                //Get Class ID
                var id_class = o_class.find('a').attr('href').trim();
                var i_class = id_class.replace('#', '').replace('-class', '').trim();

                //Get First User in Class List
                var o_user = $(id_class).find('.list-student')[0];
                var i_hash = $(o_user).attr('data-id');

                set_active_user(i_hash);
                display_user_records(i_class, i_hash);
                reading_level_option(i_class);
            });

            //Change Student Trigger
            $('.list-student').on('click', function () {
                console.log('Switch to Selected User');
                //Get The Selected User
                var o_user = $(this);
                var i_hash = $(o_user).attr('data-id');

                //Get Class ID
                //Get User Class
                var o_class = o_user.parents('.tab-pane.fade');
                var i_class = o_class.attr('id').replace('-class', '').trim();

                set_active_user(i_hash);
                display_user_records(i_class, i_hash);
            });

            //Apply Filters
            $('#apply-filter').on('click', function () {
                console.log('Apply Filters');
                //Get Current Active User
                var o_user = get_active_user();
                if (typeof o_user == 'undefined') {
                    console.log('Error: Cannot Initiate Table with NULL User');
                    return false;
                }

                //Get User Class
                var o_class = o_user.parents('.tab-pane.fade');
                var i_class = o_class.attr('id').replace('-class', '').trim();

                var i_hash = o_user.attr('data-id').trim();

                //Load User Records
                if (o_class.find('.class-active').length > 0) {
                    display_class_records(i_class);
                } else {
                    display_user_records(i_class, i_hash);
                }
            });

            //Reset Filters
            $('#reset-filter').on('click', function () {
                console.log('Apply Filters');
                //Get Current Active User
                var o_user = get_active_user();
                if (typeof o_user == 'undefined') {
                    console.log('Error: Cannot Initiate Table with NULL User');
                    return false;
                }

                //Get User Class
                var o_class = o_user.parents('.tab-pane.fade');
                var i_class = o_class.attr('id').replace('-class', '').trim();

                var i_hash = o_user.attr('data-id').trim();

                reset_filters();

                //Load User Records
                if (o_class.find('.class-active').length > 0) {
                    display_class_records(i_class);
                } else {
                    display_user_records(i_class, i_hash);
                }
            });

            //Select New School Hours Option
            $('.btn-filter[data-type="hours"]').on('click', function () {
                $('.btn-filter[data-type="hours"]').removeClass('selected');
                $(this).addClass('selected');

                return true;
            });

            //Select New Current Year Option
            $('.btn-filter[data-type="years"]').on('click', function () {
                $('.btn-filter[data-type="years"]').removeClass('selected');
                $(this).addClass('selected');

                return true;
            });

            //View All of the current class Student records
            $(document).on('click', '.view-all', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var o_all = $(this);
                var o_single = $(this).parent().find('.switch-back');

                o_all.hide();
                o_single.show();

                var i_class = $(this).parents('.tab-pane').attr('id').replace('-class', '').trim();

                //Make no User Active
                var a_students = $('#' + i_class + '-class').find('.list-student');
                a_students.addClass('class-active');

                set_class_group_options(i_class);
                display_class_records(i_class);

                return true;
            });

            //Toggle Back from Class View to Student
            $(document).on('click', '.switch-back', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var o_single = $(this);
                var o_all = $(this).parent().find('.view-all');

                o_single.hide();
                o_all.show();

                var i_class = $(this).parents('.tab-pane').attr('id').replace('-class', '').trim();

                //Make no User Active
                var a_students = $('#' + i_class + '-class').find('.list-student');
                a_students.removeClass('class-active');

                var o_user = get_active_user();
                var i_hash = o_user.attr('data-id').trim();

                set_user_group_option(i_class, i_hash);
                display_user_records(i_class, i_hash);

                return true;
            });

            /* ----------------------------------------------------- */
            /*                   jQuery Functions
             /* ----------------------------------------------------- */
            //Runs on Finished Page Load
            function initiate_table() {
                //Get Current Active User
                var o_user = get_active_user();
                if (typeof o_user == 'undefined') {
                    console.log('Error: Cannot Initiate Table with NULL User');
                    return false;
                }

                //Get User Class
                var o_class = o_user.parents('.tab-pane.fade');
                var i_class = o_class.attr('id');
                i_class = i_class ? i_class.replace('-class', '').trim(): '';

                var i_hash = o_user.attr('data-id');
                i_hash = i_hash ? i_hash.trim() : '';

                //Load User Records
                display_user_records(i_class, i_hash);
            }

            //Create the 'view all' button on all the class student selector panels
            function add_btn_to_lists() {
                var o_single = $('<button class="btn btn-primary btn-block switch-back">Back to Student</button>');
                var o_all = $('<button class="btn btn-primary btn-block view-all">View Class Records</button>');

                $('.student-tabs').find('.tab-pane').find('.panel-body').prepend(o_all).prepend(o_single);
            }

            //Display Option for Users Current Group
            function set_user_group_option(i_class, i_hash) {
                var o_section = $('.reading-group-filters');
                var o_class = o_section.find('.class-groups');
                var o_user = o_section.find('.user-group');
                var o_empty = o_section.find('.empty-group');

                var i_group = o_records[i_class][i_hash].user.group;

                o_section.fadeTo(200, 0, function () {
                    o_class.hide();
                    o_empty.hide();
                    o_user.show();

                    if (typeof i_group !== 'undefined' && i_group != null && i_group > 0) {
                        var o_group = o_groups[i_group];
                        o_user.find('#user-group-filter').attr('value', o_group.id);
                        o_user.find('.group-name').empty().append(o_group.name);
                    } else {
                        o_empty.show();
                        o_user.hide();
                    }

                    o_section.fadeTo(200, 1);
                });

                return true;
            }

            //Only Allow Groups of current class to be selected
            function set_class_group_options(i_class) {
                var o_section = $('.reading-group-filters');
                var o_class = o_section.find('.class-groups');
                var o_user = o_section.find('.user-group');
                var o_empty = o_section.find('.empty-group');

                o_section.fadeTo(200, 0, function () {
                    o_empty.hide();
                    o_user.hide();
                    o_class.show();
                    o_class.find('option').show();

                    //Display Correct Group Options
                    if (o_class.find('option[data-class="' + i_class + '"]').length > 0) {
                        o_class.find('option').hide();
                        o_class.find('option[data-class="' + i_class + '"]').show();
                        o_class.find('option[value="empty"]').show();
                    } else {
                        o_class.hide();
                        o_empty.show();
                    }

                    o_section.fadeTo(200, 1);
                });

                return true;
            }

            function display_user_records(i_class, i_hash) {
                if (typeof i_class == 'undefined' || i_class.length <= 0) {
                    console.log('Error: Cannot Display Records of NULL Class');
                    return false;
                }
                if (typeof i_hash == 'undefined' || i_hash.length <= 0) {
                    console.log('Error: Cannot Display Records of NULL User');
                    return false;
                }

                //Show The Correct Reading Group Filter Option for this user
                set_user_group_option(i_class, i_hash);

                //Retrieve User Records
                console.log('Retrieve Records For Class ' + i_class + ' User ' + i_hash);
                var a_records = get_user_records(i_class, i_hash);
                console.log('Notice: This User has ' + a_records.length + ' records.');
                populate_table(a_records, false);

                return true;
            }

            function display_class_records(i_class) {
                if (typeof i_class == 'undefined' || i_class.length <= 0) {
                    console.log('Error: Cannot Display Records of NULL Class');
                    return false;
                }

                var a_records = get_class_records(i_class);

                populate_table(a_records, true);

                return true;
            }

            function get_user_records(i_class, i_hash) {
                var a_records = o_records[i_class][i_hash].records;
                if (a_records.length > 0) {
                    //Filter Records
                    var a_filtered = [];
                    $(a_records).each(function (idx, o_record) {
                        if (in_filter(o_record, 'user')) {
                            a_filtered.push(o_record);
                        }
                    });

                    if (a_filtered.length > 0) {
                        return a_filtered;
                    }
                }

                return [];
            }

            function get_class_records(i_class) {
                console.log(o_records[i_class]);
                var a_users = o_records[i_class];
                var a_filtered = [];

                $.each(a_users, function (idx, o_user) {
                    if (o_user.records.length > 0) {
                        $(o_user.records).each(function (idx, o_record) {
                            if (in_filter(o_record, 'class')) {
                                a_filtered.push(o_record);
                            }
                        });
                    }
                });

                if (a_filtered.length > 0) {
                    return a_filtered;
                }

                return [];
            }

            function reset_filters() {
                $('.btn.btn-filter').removeClass('selected');
                //Reset School Hours Filter
                $('.btn-filter[data-type="hours"][value="both"]').addClass('selected');
                //Reset Current Year Filter
                $('.btn-filter[data-type="years"][value="current"]').addClass('selected');

                //Reset Reading Group Filter
                $('#class-group-filter').val('empty');
                $('#user-group-filter').attr('checked', null);

                //Reset Reading level Filter
                $('#level-filter').val('');

                return true;
            }

            function set_active_user(i_hash) {
                //Remove Current Active Users
                $('.tab-pane').find('.list-student').removeClass('active').removeClass('class-active');

                //Set New Active User
                var o_user = $('.list-student[data-id="' + i_hash + '"]');
                o_user.addClass('active');
            }

            function get_active_user() {
                var o_active = null;
                if ($(this).hasClass('class-switch')) {
                    o_active = $(this);
                } else {
                    o_active = $('.class-switch.active');
                }

                if (o_active.length > 0) {
                    var s_class = o_active.find('a').attr('href').replace('#', '').replace('-class', '').trim();
                    var o_class = $('#' + s_class + '-class');
                    var o_user = o_class.find('.student-list').find('.active');
                    if (o_user.length <= 0) {
                        o_user = $(o_class.find('.list-student:eq(0)'));
                    }
                } else {
                    //For Student Users who dont load multiple class lists
                    o_user = $('.list-student:eq(0)');
                }

                return o_user;
            }

            function getClassActive() {
                var o_active = $('.class-list.class-switch.active');
                if (o_active.length > 0) {
                    return o_active.find('a').attr('href').split('-').shift().split('#').pop();
                }
                return false;
            }

            function decodable_reading_level(status = false){
                let hide = [<?=$gold_and_above_term_id;?>];
                $('#level-filter option').each(function(){
                    let val = $(this).val();
                    if(hide.includes(val)){
                        if(status){
                            $(this).hide();
                        }else{
                            $(this).show();
                        }
                    }
                });
            }

            function reading_level_display(status = true){
                if(status == true){
                    $('#level-filter').prop('selectedIndex',0);
                    $('#level-filter').parent().parent().show();
                }else{
                    $('#level-filter').parent().parent().hide();
                }
            }

            function reading_level_option(activeClass = null){
                if (activeClass == null) {
                    activeClass = getClassActive();
                }
                let active_class_licence = o_licence[activeClass];

                if(active_class_licence == 0){
                    reading_level_display(false);
                }else{
                    reading_level_display(true);
                    if(active_class_licence == 'Wushka Decodables'){
                        decodable_reading_level(true);
                    }else{
                        decodable_reading_level(false);
                    }
                }
            }

            function get_filter_hours() {
                var o_filter = $('.btn-filter[data-type="hours"].selected');
                var s_filter = 'both';
                if (typeof o_filter !== 'undefined' && o_filter.length > 0) {
                    s_filter = o_filter.attr('value').trim();
                } else {
                    console.log('Warning: Could not find School Hours Filter');
                }

                return s_filter;
            }

            function get_filter_years() {
                var o_filter = $('.btn-filter[data-type="years"].selected');
                var s_filter = 'all';
                if (typeof o_filter !== 'undefined' && o_filter.length > 0) {
                    s_filter = o_filter.attr('value').trim();
                } else {
                    console.log('Warning: Could not find School Hours Filter');
                }

                return s_filter;
            }

            function get_dropdown_filter(s_id) {
                var o_filter = $('#' + s_id);
                var s_filter = '';
                if (typeof o_filter !== 'undefined' && o_filter.length > 0) {
                    s_filter = o_filter.val();
                    console.log('Filter Value =' + s_filter);
                } else {
                    console.log('Warning: Could not find ' + s_id + ' Filter');
                }

                return s_filter;
            }

            function populate_table(a_records, b_class) {
                var o_section = $('#display-section');
                var o_note = $('#no-results');
                var o_table = $('#records-table');
                var o_body = o_table.find('tbody');
                var a_rows = create_rows(a_records);

                //Hide Table
                o_section.fadeTo(200, 0, function () {
                    o_table.hide();
                    o_note.hide();
                    if (a_records.length <= 0) {
                        o_note.show();
                    } else {
                        o_body.empty().append(a_rows.join(''));
                        o_table.show();
                    }
                    toggle_table_view(b_class);

                    o_section.fadeTo(200, 1);
                });

                return true;
            }

            function toggle_table_view(b_class) {
                //If Viewing Class Records:
                //- Display Student Name Column
                //- Remove Corner Radius to 2nd Column
                if (b_class) {
                    $('th:first-child, tr td:first-child').show();
                    $('th:nth-child(2)').css('border-top-left-radius', '0px');
                    $('tr:last-child td:nth-child(2)').css('border-bottom-left-radius', '0px');
                } else {
                    $('th:first-child, tr td:first-child').hide();
                    $('th:nth-child(2)').css('border-top-left-radius', '6px');
                    $('tr:last-child td:nth-child(2)').css('border-bottom-left-radius', '6px');
                }

                return true;
            }

            function create_rows(a_records) {
                var a_rows = [];
                $.each(a_records, function (idx, o_record) {
                    a_rows.push(create_row(o_record));
                });

                return a_rows;
            }

            function create_row(o_record) {
                var a_row = [];

                //a_data = record_attr(o_record);

                a_row.push('<tr class="" data-years="' + o_record.years + '" data-hours="' + o_record.hours + '" data-group="' + o_record.group + '" data-level="' + o_record.id + '">');
                a_row.push('<td class="column-username">' + o_record.username + '</td>');
                //a_row.push('<td class="column-resid">' + o_record.res_id + '</td>');
                a_row.push('<td class="column-title">' + o_record.post_title + '</td>');
                a_row.push('<td class="column-level">' + o_record.level.name + '</td>');
                a_row.push('<td class="column-created">' + o_record.created + '</td>');
                a_row.push('<td class="column-duration">' + o_record.duration + '</td>');
                a_row.push('<td class="column-fiction">' + o_record.fiction + '</td>');
                a_row.push('<td class="column-narrated">' + o_record.narrated + '</td>');
                a_row.push('</tr>');


                return a_row.join('');
            }

            //Compare Record Data against current Set Filters
            function in_filter(o_record, s_type) {
                //---- Get Filters ----\\
                //School Hour Filter
                var s_hours = get_filter_hours();
                if (s_hours != 'both' && o_record.hours != s_hours) {
                    return false;
                }

                //Current Year Filter
                var s_years = get_filter_years();
                if (s_years != 'all' && o_record.years != s_years) {
                    return false;
                }

                //Get Reading Group Filter
                if (s_type == 'class') {
                    var s_group = get_dropdown_filter('class-group-filter');
                    console.log('Class Group Select  =' + s_group);
                    if (s_group !== 'empty' && o_record.group != s_group) {
                        return false;
                    }
                } else {
                    var o_group = $('#user-group-filter');
                    if (typeof o_group.attr('checked') !== 'undefined') {
                        var i_group = o_group.attr('value');
                        if (i_group != o_record.group) {
                            return false;
                        }
                    }
                }
                //Get Reading Level Filter
                var s_level = get_dropdown_filter('level-filter');

                if (s_level !== '' && o_record.level.id != s_level) {
                    return false;
                }

                //console.log('User Passes Filters');

                return true;
            }
        });
    </script>

    <!-- Accessibility Fixes -->
    <script>
    function removeEmptyList(){
        $('.nav li a, .list-group a').each(function(){
            if($(this).text() == '' || $(this).text() == 'undefined' || $.trim( $(this).text() ) == '')
            {
                $(this).remove();
            }
            $(this).removeAttr('aria-controls') 
        });
    }
    $(function(){
        removeEmptyList();
        $(document).ajaxSuccess(function() { 
            removeEmptyList();
        });
    });
    </script>


<?php
include 'dashboard_options.php';
get_footer();
/* ----- EOF ----- */
