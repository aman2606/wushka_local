<?php

/*
  Template Name: School Notifications
 */

/*
 * TODO - Filter By Time
 */

//Is User Logged In AND is user a school?
if( ! user_can($current_user, "administrator") ) {
    if( ! is_user_logged_in() || ! user_can($current_user, "school") ) {
        //Redirect to Login Page
        wp_redirect(esc_url(get_permalink(get_page_by_title('Login'))));
        exit();
    }
}

$school    = $terms = wp_get_object_terms($current_user->ID, 'school');
$school_id = $school[0]->term_taxonomy_id;
$classes   = array();
$teachers  = array();
$parents   = array();
if( isset($school_id) && ! empty($school_id) ) {
    //$classes  = wushka_get_class_count($school_id);
    //$teachers = wushka_get_teacher_count($school_id);
    //$parents  = wushka_get_parent_count($school_id);
}

require_once('functions/school-events/class_school-events.php');
$c_School_Events = new School_Events();
$args            = array(
    'school_id'  => $school_id,
    'event_type' => NULL,
    'sub_type'   => NULL,
    'action'     => NULL,
    'meta_value' => NULL,
    'time'       => 'all',
    'order_by'   => 'date_created',
    'order'      => 'DESC',
    'page_no'    => 1,
    'limit'      => 10
);

if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    //event type
    if( isset($_POST['event_type']) && ! empty($_POST['event_type']) ) {
        $args['event_type'] = sanitize_text_field($_POST['event_type']);
    }

    //event type
    if( isset($_POST['time']) && ! empty($_POST['time']) ) {
        $args['time'] = sanitize_text_field($_POST['time']);
    }

    //Event LIMIT
    if( isset($_POST['limit']) && ! empty($_POST['limit']) ) {
        $args['limit'] = (int)sanitize_text_field($_POST['limit']);
    }

    //Event Order BY
    if( isset($_POST['order_by']) && ! empty($_POST['order_by']) ) {
        $args['order_by'] = sanitize_text_field($_POST['order_by']);
    }

    //Event Order
    if( isset($_POST['order']) && ! empty($_POST['order']) ) {
        $args['order'] = sanitize_text_field($_POST['order']);
    }

    if( isset($_POST['page_no']) && ! empty($_POST['page_no']) ) {
        $args['page_no'] = sanitize_text_field($_POST['page_no']);
    }

}
$a_school_events = $c_School_Events->get_events($args);

$i_query_total  = 0;
$i_pages        = 0;
$_pages         = NULL;
$s_result_count = 'No Results Found';
if( ! empty($a_school_events['events']) ) {
    $i_query_total  = $a_school_events['total'];
    $i_showing      = ($args['limit'] > $i_query_total) ? $i_query_total : $args['limit'];
    $s_result_count = 'Showing ' . $i_showing . ' of ' . $i_query_total . ' results';
    //Calculate Page Counter for Pagination
    if( $i_query_total <= $args['limit'] ) {
        $i_pages = 1;
    } else {
        $i_pages = ceil($i_query_total / $args['limit']);
    }
}

//Create Pagination
$a_pages['pages'] = $i_pages;
$a_pages['max']   = 10;
$a_pages['half']  = round($a_pages['max'] / 2);
$a_pages['start'] = ($a_pages['start'] = $args['page_no'] - $a_pages['half']) > 1 ? $a_pages['start'] : 1;
$a_pages['end']   = ($a_pages['end'] = $args['page_no'] + $a_pages['half']) < $a_pages['pages'] ? $a_pages['end'] : $a_pages['pages'];
$a_pages['first'] = 1;
$a_pages['last']  = $a_pages['pages'];
$a_pages['next']  = ($args['page_no'] + 1) <= $a_pages['pages'] ? ($args['page_no'] + 1) : $a_pages['pages'];
$a_pages['prev']  = ($args['page_no'] - 1) >= 1 ? ($args['page_no'] - 1) : 1;

$a_pagination = array();
$a_nav        = array(
    'prev',
    'pages',
    'next'
);
foreach( $a_nav as $i_key => $nav ) {
    if( $nav !== 'pages' ) {
        $s_li = '<li><a href="#" class="menu-filter btn-page-no" aria-label="' . ucfirst($nav) . '" data-value="' . $a_pages[ $nav ] . '">';
        $s_li .= '<span aria-hidden="true">' . ucfirst($nav) . '</span></a></li>';
        $a_pagination[ $i_key ] = $s_li;
        continue;
    }

    //Page Loop
    for( $ii = $a_pages['start']; $ii <= $a_pages['end']; $ii++ ) {
        if( $ii < $a_pages['start'] ) {
            continue;
        }
        $s_class = ($ii == $args['page_no']) ? 'selected' : NULL;
        $a_li[]  = '<li><a href="#" aria-label="Go to page: '. $ii .'" class="menu-filter btn-page-no ' . $s_class . '" data-value="' . $ii . '">' . $ii . '</a></li>';
    }
    $a_pagination[ $i_key ] = implode('', $a_li);
}
unset($a_pages, $a_li, $s_li, $s_class, $a_nav);


//Add Header
get_header();
?>

<div class="page-school-notifications container-fluid">
    <div class="row mt15">
        <div class="col-xs-12">
            <h2 class="glyphicon-heading text-left">
                <span class="x2 glyphicon glyphicon-bullhorn hidden-xs"></span>
                <span class="glyphicon-heading-text">School Notifications</span>
                <span class="glyphicon-heading-btn-group">
                    <span class="btn-back-dashboard">
                        <a href="/school-dashboard" role="button" class="btn btn-primary btn-back-to-dashboard">
                            <span class="glyphicon glyphicon-chevron-left"></span> Back to Dashboard
                        </a>
                    </span>
                </span>
            </h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-lg-4">
            <div class="row sm-gutter">
                <form action="#" method="POST" name="notification_filters" id="notification_filters">
                    <div class="col-xs-12 col-sm-6 panel-wrap">
                        <div class="panel panel-default">
                            <div class="panel-heading">Filter By</div>
                            <div class="panel-body">
                                <?php //$b_extended = ($args['event_type'] == 'admin' || $args['event_type'] == 'teacher' || $args['event_type'] == 'student') ? 'extended': NULL; ?>
                                <?php $b_extended = 'extended'; ?>
                                <div class="form-group btn-group-vertical btn-group event-type-group <?php echo $b_extended; ?>"
                                    id="type-select">
                                    <strong>Type:</strong><br />
                                    <div class="flex-filter-by">
                                        <button aria-label="Filter by type all" type="button"
                                            class="btn btn-default btn-filter btn-type <?php echo (! isset($args['event_type']) || $args['event_type'] == NULL) ? 'selected' : NULL; ?>">
                                            <i class="glyphicon glyphicon-group"></i> All
                                        </button>
                                        <!-- <button type="button" class="btn btn-default btn-filter btn-type <?php echo ($args['event_type'] == 'user') ? 'selected' : NULL; ?>" value="user">
  						    			<i class="glyphicon glyphicon-group"></i> Users <i class="glyphicon <?php echo isset($b_extended) ? 'glyphicon-circle-minus' : 'glyphicon-circle-plus'; ?> pull-right extend-user-type"></i>
  						    		</button>-->
                                        <button type="button"
                                            class="btn btn-default btn-filter btn-type user-set <?php echo ($args['event_type'] == 'admin') ? 'selected' : NULL; ?>"
                                            value="admin">
                                            <i class="glyphicon glyphicon-dashboard"></i> Coordinator
                                        </button>
                                        <button type="button"
                                            class="btn btn-default btn-filter btn-type user-set <?php echo ($args['event_type'] == 'teacher') ? 'selected' : NULL; ?>"
                                            value="teacher">
                                            <i class="glyphicon glyphicon-education"></i> Teachers
                                        </button>
                                        <button type="button"
                                            class="btn btn-default btn-filter btn-type user-set <?php echo ($args['event_type'] == 'student') ? 'selected' : NULL; ?>"
                                            value="student">
                                            <i class="glyphicon glyphicon-user"></i> Students
                                        </button>
                                    </div>
                                    <!-- <button type="button" class="btn btn-default btn-filter btn-type <?php echo ($args['event_type'] == 'class') ? 'selected' : NULL; ?>" value="class">
					    				<i class="glyphicon glyphicon-education"></i> Class
					    			</button> -->
                                    <input type="hidden" name="event_type" id="filter-type"
                                        value="<?php echo $args['event_type']; ?>" />
                                </div>
                                <fieldset class="form-group" id="time-select">
                                    <legend>Time:</legend>

                                    <input id="the-last-hour" type="radio" name="time" aria-label="The Last Hour"
                                        value="hours" <?php echo ($args['time'] == 'hours') ? 'checked' : NULL; ?> />
                                    <label for="the-last-hour">The Last Hour</label><br />

                                    <input id="time-today" type="radio" name="time" aria-label="Today" value="days"
                                        <?php echo ($args['time'] == 'days') ? 'checked' : NULL; ?> />
                                    <label for="time-today">Today</label><br />

                                    <input id="time-weeks" type="radio" name="time" aria-label="This Week" value="weeks"
                                        <?php echo ($args['time'] == 'weeks') ? 'checked' : NULL; ?> />
                                    <label for="time-weeks">This Week</label><br />

                                    <input id="time-month" type="radio" name="time" aria-label="This Month"
                                        value="months" <?php echo ($args['time'] == 'months') ? 'checked' : NULL; ?> />
                                    <label for="time-month">This Month</label><br />

                                    <input id="time-no-limi" type="radio" name="time" aria-label="No Limit" value="all"
                                        <?php echo ($args['time'] == 'all') ? 'checked' : NULL; ?> />
                                    <label for="time-no-limi">No Limit</label><br />

                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <!-- End Filter Panel -->
                    <div class="col-xs-12 col-sm-6 panel-wrap">
                        <div class="panel panel-default">
                            <div class="panel-heading">Sort By</div>
                            <div class="panel-body filter-list">
                                <div class="form-group btn-group order-by-group" role="group">
                                    <strong>Order By:</strong><br />
                                    <div class="flex-sort-by">
                                        <button type="button"
                                            class="btn btn-default btn-filter btn-order-by <?php echo ($args['order_by'] == 'date_created') ? 'selected' : NULL; ?>"
                                            value="date_created">Time
                                        </button>
                                        <button type="button"
                                            class="btn btn-default btn-filter btn-order-by <?php echo ($args['order_by'] == 'event_type') ? 'selected' : NULL; ?>"
                                            value="event_type">Type
                                        </button>
                                        <button type="button"
                                            class="btn btn-default btn-filter btn-order-by <?php echo ($args['order_by'] == 'description') ? 'selected' : NULL; ?>"
                                            value="description">Name
                                        </button>
                                    </div>
                                    <input type="hidden" name="order_by" id="filter-order-by"
                                        value="<?php echo $args['order_by']; ?>" />
                                </div>
                                <div class="form-group btn-group order-group" role="group">
                                    <strong>Order:</strong><br />
                                    <div class="flex-sort-by">
                                        <button type="button"
                                            class="btn btn-default btn-filter btn-order <?php echo ($args['order'] == 'DESC') ? 'selected' : NULL; ?>"
                                            value="DESC">Descending
                                        </button>
                                        <button type="button"
                                            class="btn btn-default btn-filter btn-order <?php echo ($args['order'] == 'ASC') ? 'selected' : NULL; ?>"
                                            value="ASC">Ascending
                                        </button>
                                    </div>
                                    <input type="hidden" name="order" id="filter-order"
                                        value="<?php echo $args['order']; ?>" />
                                </div>
                                <div class="form-group btn-group limit-group" role="group">
                                    <strong>Results Per Page:</strong><br />
                                    <div class="flex-sort-by">
                                        <button type="button" aria-label="Results per page: 10"
                                            class="btn btn-default btn-filter btn-limit <?php echo ($args['limit'] == 10) ? 'selected' : NULL; ?>"
                                            value="10">10
                                        </button>
                                        <button type="button" aria-label="Results per page: 25"
                                            class="btn btn-default btn-filter btn-limit <?php echo ($args['limit'] == 25) ? 'selected' : NULL; ?>"
                                            value="25">25
                                        </button>
                                        <button type="button" aria-label="Results per page: 50"
                                            class="btn btn-default btn-filter btn-limit <?php echo ($args['limit'] == 50) ? 'selected' : NULL; ?>"
                                            value="50">50
                                        </button>
                                        <button type="button" aria-label="Results per page: 100"
                                            class="btn btn-default btn-filter btn-limit <?php echo ($args['limit'] == 100) ? 'selected' : NULL; ?>"
                                            value="100">100
                                        </button>
                                    </div>
                                    <input type="hidden" name="limit" id="filter-limit"
                                        value="<?php echo $args['limit']; ?>" />
                                </div>
                                <div class="form-group">
                                    <input type="hidden" name="page_no" id="filter-page-no"
                                        value="<?php echo $args['page_no']; ?>" />
                                    <input type="submit" name="notification_submit" id="notification_submit"
                                        class="btn btn-primary btn-block" value="Apply Filters" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- END SORT PANEl -->
                </form>
            </div>
        </div>
        <!-- Close Col -->

        <div class="col-xs-12 col-lg-8 panel-wrap">
            <div class="panel panel-default">
                <div class="panel-heading">Notifications: <?php echo $s_result_count; ?></div>
                <div class="panel-body school-list">
                    <?php
                    //Display Recent School Events
                    $a_glyphs      = array(
                        'class' => 'education',
                        'user'  => 'user',
                    );
                    $a_events_html = array();
                    if( ! empty($a_school_events['events']) ) {
                        $c_School_Events->set_timezone($school_id);
                        foreach( $a_school_events['events'] as $i_key => $o_event ) {
                            if( $i_key >= $args['limit'] ) {
                                break;
                            }
                            //$s_glyph = $c_School_Events->get_glyph($o_event);
                            //$s_time_ago = $c_School_Events->event_time_ago($o_event->date_created);
                            $s_time_ago     = $c_School_Events->format_time($o_event->date_created);
                            $a_event_html[] = '<div class="list-group-item" id="school-event-' . $o_event->ID . '">';
                            //$a_event_html[] = '<i class="hidden-xs hidden-sm hidden-md glyphicon ' . $s_glyph . '"></i>';
                            $a_event_html[] = '<div class="notification top-line">';
                            $a_event_html[] = $o_event->description;
                            $a_event_html[] = '</div><div class="notification bottom-line">';
                            $a_event_html[] = '<span class="pull-right text-muted small"><em>' . $s_time_ago . '</em></span>';
                            $a_event_html[] = '</div>';
                            $a_event_html[] = '</div>';
                            //Store to Events Array
                            $a_events_html[] = implode('', $a_event_html);
                            unset($a_event_html);
                        }
                        //Display Events
                        if( ! empty($a_events_html) ) {
                            echo implode('', $a_events_html);
                        } else {
                            $a_event_html[] = '<p>';
                            $a_event_html[] = '<i class="glyphicon warning-sign"></i>';
                            $a_event_html[] = 'No School Events Found';
                            $a_event_html[] = '</p>';
                            echo implode('', $a_event_html);
                            unset($a_event_html);
                        }
                    }

                    //Pagination
                    ?>
                    <div class="pagination-wrap">
                        <nav aria-label="pagination">
                            <ul class="pagination">
                                <?php echo implode('', $a_pagination); ?>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <!-- END NOTIFICATIONS -->
    </div>
    <!-- Close Row -->

</div><!-- END PAGE CONTENT -->

<script>
jQuery(document).ready(function($) {
    /* ---- Open/Close User Types in Filter Menu --- */
    $(document).on('click', '.extend-user-type', function() {
        var e_parent = $(this).parents('.btn-group.event-type-group');
        if (!e_parent.hasClass('extended')) {
            e_parent.addClass('extended');
            $(this).removeClass('glyphicon-circle-plus');
            $(this).addClass('glyphicon-circle-minus');
        } else {
            e_parent.removeClass('extended');
            $(this).addClass('glyphicon-circle-plus');
            $(this).removeClass('glyphicon-circle-minus');
        }
    });

    $(document).on('click', '.btn.btn-filter', function() {
        var this_btn = $(this);
        var btn_value = null;
        if (typeof this_btn.attr('value') !== 'undefined' && this_btn.attr('value') !== null) {
            btn_value = this_btn.attr('value').trim();

        }

        this_btn.parent().find('.btn').removeClass('selected');
        this_btn.addClass('selected');

        if (this_btn.parents('.form-group.btn-group').find('input[type="hidden"][id^="filter-"]')
            .length > 0) {
            this_btn.parents('.form-group.btn-group').find('input[type="hidden"][id^="filter-"]').attr(
                'value', btn_value);
        }
    });

    $(document).on('click', '#notification_submit', function() {
        $(this).parent().find('input[type="hidden"]#filter-page-no').attr('value', 1);
    });

    $(document).on('click', '.menu-filter.btn-page-no', function() {
        event.preventDefault();
        var this_btn = $(this);
        var btn_value = null;
        if (typeof this_btn.attr('data-value') !== 'undefined' && this_btn.attr('data-value') !==
            null) {
            btn_value = this_btn.attr('data-value').trim();
        }

        this_btn.parents('ul.pagination').find('.menu-filter').removeClass('selected');
        this_btn.addClass('selected');

        if ($(document).find('input[type="hidden"][id="filter-page-no"]').length > 0) {
            $(document).find('input[type="hidden"][id="filter-page-no"]').attr('value', btn_value);
        }

        $('#notification_filters').submit();
    });

});
</script>

<?php
//Add Footer
include 'dashboard_options.php';
get_footer();
?>