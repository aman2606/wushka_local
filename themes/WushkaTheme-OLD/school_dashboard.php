<?php
/*
  Template Name: School Dashboard
 */

//Is User Logged In AND is user a school?
global $current_user;

$a_terms = wp_get_object_terms($current_user->ID, 'school');

$a_classes   = array();
$school_id   = NULL;
$school_name = NULL;
if( isset($a_terms) && ! empty($a_terms) ) {
    $school_id   = $a_terms[0]->term_taxonomy_id;
    $school_name = $a_terms[0]->name;
    $a_classes   = wushka_get_classes($school_id, NULL, NULL, 'year');
}

$i_total_students = 0;
$i_total_classes  = 0;

$a_ids = array();
if( ! empty($a_classes) ) {
    foreach( $a_classes as $idx => $o_class ) {
        if( ! isset($o_class->year) || empty($o_class->year) ) {
            unset($a_classes[ $idx ]);
            // continue;
        }
        $a_ids[] = $o_class->id;
    }
    $i_total_classes = count($a_ids);
}

//Get All Students in This School
// updated Feb 2019 to prevent count_total performing slow query
$args = array(
    'role'       => 'student',
    'count_total'   => false,
    'meta_query' => array(
        'relation' => 'AND',
        0          => array(
            'key'     => 'class',
            'value'   => $a_ids,
            'compare' => 'IN'
        ),
        1          => array(
            'key'   => 'active',
            'value' => '1'
        )
    )
);

$a_results = array();

$o_query = new WP_User_Query($args);    // args updated for slow query
if( isset($o_query->results) && ! empty($o_query->results) ) {
    $a_results        = $o_query->get_results();
    $i_total_students = count($a_results);
}

$i_total = count($a_results);
error_log('Found ' . $i_total . ' Active Students in School ' . $school_id);

$a_years        = array();
$a_school_years = get_wushka_school_years();
foreach( $a_school_years as $idx => $a_year ) {
    $a_years[ $a_year['i'] ] = array(
        'label'    => $a_year['v'],
        'students' => 0,
        'classes'  => 0
    );
}

if( ! empty($a_classes) ) {
    foreach( $a_classes as $ii => $o_class ) {
        $i_count = 0;

        $a_label = explode(':', $o_class->year);
        $s_slug  = $a_label[0];

        if( array_key_exists($s_slug, $a_years) ) {
            $a_years[ $s_slug ]['classes']++;

            if( ! empty($a_results) ) {
                foreach( $a_results as $iii => $o_kid ) {
                    if( isset($o_kid->class) ) {
                        if( (int)$o_kid->class == (int)$o_class->id ) {
                            $i_count++;
                            $a_years[ $s_slug ]['students']++;
                        }
                    }
                }
            }
        }
    }
}

//Create Graph Data for Morris Charts
$a_graphs = array();

//Create Student Graph Data
foreach( $a_years as $idx => $a_year ) {
    $a_graphs['students'][] = array(
        'label' => $a_year['label'],
        'value' => $a_year['students']
    );
    $a_graphs['classes'][]  = array(
        'label' => $a_year['label'],
        'value' => $a_year['classes']
    );
}

require_once('functions/school-events/class_school-events.php');
$c_School_Events = new School_Events();

$args = array(
    'school_id' => $school_id,
    'order_by'  => 'date_created',
    'order'     => 'DESC',
    'limit'     => 10
);

$a_school_events = $c_School_Events->get_events($args);

//Add Header
get_header();
?>

    <div class="page-school-dashboard container-fluid">
        <div class="row mt15">
            <div class="col-xs-12">
                <h2 class="glyphicon-heading text-left">
                    <span class="x2 glyphicon glyphicon-stats hidden-xs"></span>
                    <span
                        class="glyphicon-heading-text">Wushka Program Coordinator Overview: <?php echo $school_name; ?></span>
            <span class="glyphicon-heading-btn-group">
                <span class="btn-back-dashboard"><a href="/school-dashboard" role="button"
                                                    class="btn btn-primary btn-back-to-dashboard"><span
                            class="glyphicon glyphicon-chevron-left"></span> Back to Dashboard</a></span>
            </span>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-flex">
                        <i class="glyphicon glyphicon-signal"></i> Classes
                        <span class="class-counter flex-push-right">
                            Total Active School Classes: <?php echo $i_total_classes; ?>
                        </span>
                    <span class="btn-graph-wrap">
                        <button type="button" class="btn btn-default btn-small btn-graph" data-action="donut" aria-label="Donut graph"><span
                                class="glyphicon glyphicon-pie-chart" data-toggle="tooltip" data-placement="top"
                                title="Donut graph"></span></button>
                        <button type="button" class="btn btn-default btn-small btn-graph" data-action="bar" aria-label="Bar graph"><span
                                class="glyphicon glyphicon-charts" data-toggle="tooltip" data-placement="top"
                                title="Bar graph"></span></button>
                    </span>
                        <span class="clearfix"></span>
                    </div>
                    <div class="panel-body class-graphs">
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="class-graph"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-flex">
                        <i class="glyphicon glyphicon-signal"></i> Student Users
                      <span>
                        Total Active Student Users: <?php echo $i_total_students; ?>
                      </span>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-8">
                                <div id="students-bar"></div>
                            </div>
                            <div class="col-lg-4">
                                <div id="students-donut"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading panel-heading-flex">
                        <i class="glyphicon glyphicon-bullhorn"></i> Notifications
                    </div>
                    <div class="panel-body">
                        <div class="list-group school-list">
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
                                    //$s_glyph        = $c_School_Events->get_glyph($o_event);
                                    $s_time_ago     = $c_School_Events->format_time($o_event->date_created);
                                    $a_event_html[] = '<a class="list-group-item" id="school-event-' . $o_event->ID . '">';
                                    //$a_event_html[] = '<i class="glyphicon ' . $s_glyph . '"></i>';
                                    $a_event_html[] = '<div class="notification top-line">';
                                    $a_event_html[] = $o_event->description;
                                    $a_event_html[] = '</div><div class="notification bottom-line">';
                                    $a_event_html[] = '<span class="pull-right text-muted small"><em>' . $s_time_ago . '</em></span>';
                                    $a_event_html[] = '</div>';
                                    $a_event_html[] = '</a>';
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
                            ?>
                        </div>
                        <a href="/school-notifications/" class="btn btn-default btn-block">View All Notifications</a>
                    </div>
                </div>

            </div>
        </div>

    </div>
    <script>
        jQuery(document).ready(function ($) {
            var a_graphs = null;
            a_graphs = <?php echo json_encode($a_graphs); ?>;

            //Setup Student Bar Graph
            Morris.Bar({
                element: 'students-bar',
                resize: 'true',
                data: a_graphs.students,
                xkey: 'label',
                ykeys: ['value'],
                labels: ['Students'],
                xLabelAngle: 25
            });


            //Setup Student Donut Graph
            Morris.Donut({
                element: 'students-donut',
                resize: 'true',
                data: a_graphs.students
            });

            //Setup Class Bar Graph
            Morris.Bar({
                element: 'class-graph',
                resize: 'true',
                data: a_graphs.classes,
                xkey: ['label'],
                ykeys: ['value'],
                labels: ['Classes'],
                xLabelAngle: 25
            });

            $('.btn.btn-graph').on('click', function () {
                var s_type = $(this).attr('data-action').trim();

                var o_wrap = $('.class-graphs');
                o_wrap.fadeTo(200, 0, function () {
                    $('#class-graph').empty();
                    if (s_type == 'donut') {
                        //Setup Class Donut Graph
                        Morris.Donut({
                            element: 'class-graph',
                            data: a_graphs.classes
                        });
                    } else {
                        //Setup Class Bar Graph
                        Morris.Bar({
                            element: 'class-graph',
                            resize: 'true',
                            data: a_graphs.classes,
                            xkey: ['label'],
                            ykeys: ['value'],
                            labels: ['Classes'],
                            xLabelAngle: 25
                        });
                    }

                    o_wrap.fadeTo(200, 1);
                });

                return true;
            });


            function tooltips() {
                $('[data-toggle="tooltip"]').tooltip();
                $('[data-toggle="popover"]').popover();
            }

            tooltips();
        });

        setTimeout(function(){ 
            if(!$('svg').attr('aria-label')){ 
                $('svg').attr('aria-label','Class Overview');
            }    
         }, 3000);
        

        

    </script>
<?php
//Add Footer
include 'dashboard_options.php';
get_footer();

/* ----- END OF FILE ----- */
