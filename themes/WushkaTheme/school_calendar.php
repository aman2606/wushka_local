<?php
/*
  Template Name: School Calendar
 */

//Is User Logged In AND is user a school?
if (!is_user_logged_in() || (!current_user_can('school'))) {
    //Redirect to Login Page
    wp_redirect(home_url() . "/wp-login.php");
    exit;
}

global $current_user;
global $wpdb;

$i_school = wushka_get_user_school($current_user->ID);
$s_state = wushka_get_school_caldendar_state($i_school);

if ($s_state == 'WORLD') {
    wp_redirect(home_url('/school-settings'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' AND $s_state !== 'WORLD' ) {
    if (isset($_POST['calendar_edit']) && !empty($_POST['calendar_edit'])) {
        error_log('editing calendar:' . $_POST['calendar_edit']);
        error_log('time range:' . $_POST['time_range']);
        error_log('from date:' . $_POST['date_from']);
        error_log('end date:' . $_POST['date_end']);
        $from_date = new DateTime($_POST['date_from']);
        $end_date = new DateTime($_POST['date_end']);
        error_log('formatted from date:' . $from_date->format("Y-m-d"));
        error_log('formatted end date:' . $end_date->format("Y-m-d"));

        // determine if we are updating an existing calendar entry or copying from base to new for the school
        $term_event = $wpdb->get_row("SELECT * from ".$wpdb->prefix."spidercalendar_event where id = " . $_POST['calendar_edit']);
        if ($term_event->userID === '') {
            // create copy for school
            $sql = "INSERT into ".$wpdb->prefix."spidercalendar_event (calendar, date, date_end, title, category, time, text_for_date, userID, repeat_method, `repeat`, week, month, month_type, monthly_list, month_week, `year_month`, published) " .
                    "SELECT calendar, '" . $from_date->format("Y-m-d") . "', '" . $end_date->format("Y-m-d") . "', title, category, '" . $_POST['time_range'] . "', text_for_date, " . $current_user->ID . ", repeat_method, " .
                    "`repeat`, week, month, month_type, monthly_list, month_week, `year_month`, published FROM ".$wpdb->prefix."spidercalendar_event WHERE id=" . $_POST['calendar_edit'];
            $rows_affected = $wpdb->query($sql);
            if ( $rows_affected === false ) {
                error_log('1. failed to update calendar');
            }
        } else {
            // updating existing record
            $sql = "UPDATE ".$wpdb->prefix."spidercalendar_event SET time = %s, date = %s, date_end = %s WHERE id = %d";
            $sql = $wpdb->prepare( $sql, array($_POST['time_range'], $from_date->format("Y-m-d"), $end_date->format("Y-m-d"), $_POST['calendar_edit']));
            $rows_affected = $wpdb->query($sql);
            if ( $rows_affected === false ) {
                error_log('2. failed to update calendar');
            }
        }
    }
}

$custom_calendar = isset($current_user->calendar);
// get State calendar
$calendar = $wpdb->get_var("SELECT id from ".$wpdb->prefix."spidercalendar_calendar where title = '" . $s_state . "'");
$results = $wpdb->get_results("SELECT * from ".$wpdb->prefix."spidercalendar_event where calendar = " . $calendar . " AND YEAR(date) >= " . date("Y") . " AND userID = ''");
$event = array();
foreach ($results as $key => $result) {
    $date = new DateTime($result->date);
    $event[$date->format("Y") . $result->title] = $result;
}
// retrieve school customised events
$user_results = $wpdb->get_results("SELECT * from ".$wpdb->prefix."spidercalendar_event where calendar = " . $calendar . " AND YEAR(date) >= " . date("Y") . " AND userID = '" . $current_user->ID . "'");
foreach ($user_results as $key => $result) {
    $date = new DateTime($result->date);
    $event[$date->format("Y") . $result->title] = $result;
}

/* --- Deploy Page --- */
//Add Header
get_header();
?>

<div class="page-school-calendar container-fluid">
    <div class="row mt15">
        <div class="col-xs-12">
            <h2 class="glyphicon-heading text-left">
                <span class="x2 glyphicon glyphicon-calendar hidden-xs"></span>
                <span class="glyphicon-heading-text">School Calendar</span>
                <span class="glyphicon-heading-btn-group">
                    <span class="btn-back-dashboard">
                        <a href="/school-settings/" role="button" class="btn btn-primary btn-back-to-dashboard">
                            <span class="glyphicon glyphicon-chevron-left"></span>Back to Settings
                        </a>
                    </span>
                </span>
            </h2>
        </div>
        <section class='page-section padding-y grad-radial' id='school-calendar'>
            <div class="col-lg-6 col-sm-12">
                <div class="col-lg-10 col-lg-offset-1">
                    <?php
                    foreach ($event as $key => $result) {
                        $from = new DateTime($result->date);
                        $end = new DateTime($result->date_end);
                        $year = $from->format("Y");
                        if (!isset($last_year) || $year !== $last_year) {

                            if (isset($last_year)) {
                                ?>
                    </ul>
                    <?php } ?>
                    <?php if ($result->category <= 4 || !empty($result->userID)) { ?>
                    <h2 class="school-calendar-heading-year"><?php echo $from->format("Y"); ?></h2>
                    <ul class="school-terms">
                        <?php } ?>
                        <?php
                                $last_year = $year;
                            }
                            ?>
                        <?php if ($result->category <= 4 || !empty($result->userID)) { ?>
                        <li class="school-term">
                            <?php } ?>
                            <div class="term-edit">
                                <?php if ($result->category <= 4 || !empty($result->userID)) { ?>
                                <button type="button" class="btn btn-default btn-edit"
                                    data-id="<?php echo $result->id ?>"
                                    data-from="<?php echo $from->format("l d F Y") ?>"
                                    data-end="<?php echo $end->format("l d F Y") ?>"
                                    data-year="<?php echo $from->format("Y") ?>"
                                    data-term="<?php echo $result->title ?>" data-time="<?php echo $result->time?>">
                                    <span class="glyphicon glyphicon-edit"></span>
                                </button>
                                <?php } ?>
                            </div>
                            <?php if ($result->category <= 4 || !empty($result->userID)) { ?>
                            <div class="term-name"><?php echo $result->title ?></div>
                            <div class="term-dates">
                                <?php echo $from->format("l, jS F Y") . ' - ' . $end->format("l, jS F Y") ?></div>
                            <?php } ?>
                        </li>
                        <?php
                        }
                        if (isset($last_year)) {
                        ?>
                    </ul>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12">
                <h2 class="calendar-title"><?php echo $s_state . ' default calendar'?></h2>
                <?php echo do_shortcode('[Spider_Calendar id="' . $calendar . '" theme="20" default="month" select="month,"] '); ?>
            </div>
        </section>
    </div>
</div>
<div class="modal fade" id="edit-calendar" tabindex="-1" role="dialog" aria-labelledby="ec-title" aria-hidden="true">
    <form action="<?php the_permalink(); ?>" id="calendar_form" method="post">
        <input type="hidden" name="calendar_edit" id="calendar_edit" value="" />
        <input type="hidden" name="time_range" id="time_range" value="" />
        <input type="hidden" name="date_from" id="date_from" value="" />
        <input type="hidden" name="date_end" id="date_end" value="" />

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="ec-title">Edit Calendar</h3>
                </div>
                <div class="modal-body">
                    <div class="edit-details">
                        <span id="edit-year"></span> - <span id="edit-term"><span>
                    </div>
                    <div class="input-daterange input-group" id="datepicker">
                        <label for="start" class="sr-only">Start</label>
                        <input type="text" class="input-sm form-control" name="start" id="start" />

                        <label for="end" class="sr-only">End</label>
                        <span class="input-group-addon">to</span>
                        <input type="text" class="input-sm form-control" name="end" id="end" />
                    </div>
                    <div class="input-group" id="time-range">
                        <p>School time: <span class="slider-time"></span></p>
                        <div class="sliders_step1">
                            <div id="slider-range"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary btn-save-calendar">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
<?php  //Accessibility Fixes  ?>
$('.term-edit button').append('<span class="sr-only">Edit Calendar</span>');
$('table').attr('role', 'presentation');
var count = 0;
$('#bigcalendar1 table a').each(function(index) {
    if (count == 0) {
        var getDateText = 'Previous Month';
    } else if (count == 1) {
        var getDateText = 'Next Month';
    } else {
        var getDateText = count - 1;
    }
    $('#bigcalendar1 table a:eq(' + index + ')').attr('aria-label', 'Calendar details - ' + getDateText);
    count++;
});
$("table input[name*='day']").attr('id', 'input-day').before(
    '<label for="input-day" class="sr-only" role="none">Day</label>');





//Change tags and add onkeypress 
function replaceTagsAndAddKeyPress() {
    var replacementTag = 'a';
    $("#cats p").each(function(i) {

        var clickValue = $(this).attr('onclick');
        $(this).attr('onkeypress', clickValue).attr('href', '#');


        var outer = this.outerHTML;
        // Replace opening tag
        var regex = new RegExp('<' + this.tagName, 'i');
        var newTag = outer.replace(regex, '<' + replacementTag);
        // Replace closing tag
        regex = new RegExp('</' + this.tagName, 'i');
        newTag = newTag.replace(regex, '</' + replacementTag);
        //Replace 
        $(this).replaceWith(newTag);
    });


    $(".views").each(function(i) {

        var clickValue = $(this).attr('onclick');
        if (clickValue != 'undefined') {
            $(this).attr('onkeypress', clickValue).attr('href', '#');


            var outer = this.outerHTML;
            // Replace opening tag
            var regex = new RegExp('<' + this.tagName, 'i');
            var newTag = outer.replace(regex, '<' + replacementTag);
            // Replace closing tag
            regex = new RegExp('</' + this.tagName, 'i');
            newTag = newTag.replace(regex, '</' + replacementTag);
            //Replace 
            $(this).replaceWith(newTag);
        }
    });

    $(".views_select").each(function(i) {

        var clickValue = $(this).attr('onclick');
        if (clickValue != 'undefined') {
            $(this).attr('onkeypress', clickValue).attr('href', '#');


            var outer = this.outerHTML;
            // Replace opening tag
            var regex = new RegExp('<' + this.tagName, 'i');
            var newTag = outer.replace(regex, '<' + replacementTag);
            // Replace closing tag
            regex = new RegExp('</' + this.tagName, 'i');
            newTag = newTag.replace(regex, '</' + replacementTag);
            //Replace 
            $(this).replaceWith(newTag);
        }
    });

    $("#afterbig1 table .top_table td div").each(function(i) {

        var clickValue = $(this).attr('onclick');
        if (clickValue != 'undefined') {
            $(this).attr('onkeypress', clickValue).attr('href', '#');


            var outer = this.outerHTML;
            // Replace opening tag
            var regex = new RegExp('<' + this.tagName, 'i');
            var newTag = outer.replace(regex, '<' + replacementTag);
            // Replace closing tag
            regex = new RegExp('</' + this.tagName, 'i');
            newTag = newTag.replace(regex, '</' + replacementTag);
            //Replace 
            $(this).replaceWith(newTag);
        }
    });
}


replaceTagsAndAddKeyPress();

<?php //Accessibility fixes ends  ?>


var timeRange, startTime = 480,
    endTime = 960;
jQuery(document).ready(function($) {
    $(document).on('click', '.btn-edit', function() {
        $('#edit-calendar .input-daterange').datepicker('remove');

        $('#edit-year').empty().text($(this).attr('data-year'));
        $('#edit-term').empty().text($(this).attr('data-term'));
        $('#start').val($(this).attr('data-from'));
        $('#end').val($(this).attr('data-end'));
        $('#edit-calendar .input-daterange').datepicker({
            format: "DD dd MM yyyy",
            daysOfWeekDisabled: "0,6"
        });
        timeRange = $(this).attr('data-time');
        times = timeRange.split('-');
        fromT = times[0].split(':');
        endT = times[1].split(':');
        startTime = (Number(fromT[0]) * 60) + Number(fromT[1]);
        endTime = (Number(endT[0]) * 60) + Number(endT[1]);

        $slide.slider('option', 'values', [startTime, endTime]);
        $('.slider-time').html($(this).attr('data-time'));

        $('.btn-save-calendar').attr('data-id', $(this).attr('data-id'));

        $('#edit-calendar').modal('show');
    });

    $(document).on('click', '.btn-save-calendar', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#calendar_edit').val($(this).attr('data-id'));
        $('#time_range').val(timeRange);
        $('#date_from').val($('#start').val());
        $('#date_end').val($('#end').val());
        $('#calendar_form').submit();
    });

    // var datepicker = $.fn.datepicker.noConflict();
    //$.fn.bootstrapDP = datepicker;
    $slide = $("#slider-range").slider({
        range: true,
        min: 480,
        max: 960,
        step: 15,
        values: [startTime, endTime],
        slide: function(e, ui) {
            var hours1 = Math.floor(ui.values[0] / 60);
            var minutes1 = ui.values[0] - (hours1 * 60);

            if (hours1.toString().length === 1) hours1 = '0' + hours1;
            if (minutes1.toString().length === 1) minutes1 = '0' + minutes1;
            if (minutes1 === 0) minutes1 = '00';
            var minutes3 = minutes1;
            timeRange = hours1 + ":" + minutes3;

            var hours2 = Math.floor(ui.values[1] / 60);
            var minutes2 = ui.values[1] - (hours2 * 60);

            if (hours2.toString().length === 1) hours2 = '0' + hours2;
            if (minutes2.toString().length === 1) minutes2 = '0' + minutes2;
            if (minutes2 === 0) minutes2 = '00';
            minutes3 = minutes2;
            timeRange = timeRange + "-" + hours2 + ":" + minutes3;
            $('.slider-time').html(timeRange);
        }
    });
});
</script>
<?php
include 'dashboard_options.php';
get_footer();
?>