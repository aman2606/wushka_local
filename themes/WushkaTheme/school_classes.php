<?php
/*
  Template Name: School classes
 */
 
//Is User Logged In AND is user a school?
if( ! is_user_logged_in() || (! current_user_can('administrator') && ! current_user_can('school')) ) {
    //Redirect to Login Page
    wp_redirect(esc_url(get_permalink(get_page_by_title('Login'))));
    exit;
}

global $wpdb;
if( ! isset($current_user) ) {
    global $current_user;
}

//Get School Taxonomy ID of current school user
$o_terms     = wp_get_object_terms($current_user->ID, 'school');
$school_id   = NULL;
$school_name = NULL;
if( isset($o_terms) && ! empty($o_terms) ) {
    $school_id   = $o_terms[0]->term_taxonomy_id;
    $school_name = $o_terms[0]->name;
    $school_slug = $o_terms[0]->slug;
}

//Get School years of current school user
$school_years = $current_user->school_years;
if( ! isset($school_years) || empty($school_years) ) {
    $school_years = get_wushka_school_years();
}

//Get Teacher Users for Current School
$a_teachers = wushka_get_school_users($school_id, 'teacher');
//Get Class Rows for current school
$a_results = wushka_get_classes($school_id);

/* Retrieve an array of wp_classes_teachers for this school*/
$s_query = 'SELECT * FROM '.$wpdb->prefix.'classes_teachers WHERE school_id = %d ORDER BY class_id ASC;';

$a_class_teachers = $wpdb->get_results(
    $wpdb->prepare($s_query, $school_id)
);

//Retrieve and array of student numbers for each class of this school
$s_query = 'SELECT meta_value, count(*) as numbers FROM '.$wpdb->prefix.'usermeta as m1 WHERE ' .
    'meta_key = %s AND meta_value IN ( ' .
        'SELECT convert(cast(id as char) using latin1) FROM '.$wpdb->prefix.'classes WHERE school_id = %d' .
    ') AND user_id IN ( '.
        'SELECT user_id FROM '.$wpdb->prefix.'usermeta WHERE meta_key = %s AND meta_value = %d AND user_id = m1.user_id'.
' ) GROUP BY m1.meta_value';

$a_numbers = $wpdb->get_results(
    $wpdb->prepare($s_query, 'class', $school_id, 'active', 1)
);

//Combine All Gathered Data into Single class-based array format
$a_classes = array();
if( isset($a_results) && ! empty($a_results) ) {
    foreach( $a_results as $idx => $o_class ) {
        $a_class = array(
            'class'    => NULL,
            'teachers' => array(),
            'students' => 0
        );

        $a_class['class'] = $o_class;
        if ( $o_class->archived !== '0' ) {
            continue;
        }

        if( isset($a_class_teachers) && ! empty($a_class_teachers) ) {
            foreach( $a_class_teachers as $ii => $o_teacher ) {
                if( (int)$o_teacher->class_id == (int)$o_class->id ) {
                    $a_class['teachers'][] = $o_teacher->teacher_id;
                }
            }
        }

        if( isset($a_numbers) && ! empty($a_numbers) ) {
            foreach( $a_numbers as $ii => $o_number ) {
                if( (int)$o_number->meta_value == (int)$o_class->id ) {
                    $a_class['students'] = $o_number->numbers;
                }
            }
        }

        $a_classes[] = $a_class;
        unset($a_class);
    }
}

get_header();
?>
<div class="page-school-classes container-fluid">
    <div class="row mt15">
        <div class="col-xs-12">
            <h2 class="glyphicon-heading text-left">
                <span class="x2 glyphicon glyphicon-group hidden-xs"></span>
                <span class="glyphicon-heading-text">Step 3: Classes - <?php echo $school_name; ?></span>
                <span class="glyphicon-heading-btn-group">
                    <span class="btn-back-dashboard">
                        <a href="/school-teachers" role="button" class="btn btn-primary btn-back-to-dashboard">
                            <span class="glyphicon glyphicon-chevron-left"></span> Previous Step
                        </a>
                    </span>
                    <span class="btn-back-dashboard">
                        <a href="/school-students" role="button" class="btn btn-primary btn-back-to-dashboard">
                            Go to Step 4 <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>
                    </span>
                </span>
            </h2>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-2 col-md-3">
            <div class="panel panel-default" id="add-new-class">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-10 col-md-10 col-sm-10">
                            <i class="glyphicon glyphicon-group"></i>
                            <span style="padding-left:10px;">a. Add Class</span>
                        </div>

                    </div>
                </div>
                <div class="panel-body">
                    <div class="well new-class">
                        <div class="form-group">
                            <label for="class_name" class="control-label">Class Name</label>
                            <input type="text" name="class_name" class="form-control" id="class_name" value=""
                                placeholder="Class Name" />
                        </div>
                        <div class="form-group" style="display: none;">
                            <label for="class_size" class="control-label">Class Size</label>
                            <input type="number" name="class_size" class="form-control" id="class_size" value="50"
                                min="50" placeholder="Class Size" />
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12" data-toggle="tooltip" data-placement="right"
                        title="add new class"
                        style="font-size: 24px; text-align: center; padding-left: 10px; padding-right: 10px;">
                        <button type="button" class="btn btn-primary btn-lg btn-add-class" aria-label="Add Class">
                            Add
                            Class
                        </button>
                    </div>
                </div>
                <div class="label-danger" id="class-msg"></div>
            </div>
            <!--
                            <div class="panel panel-default" id="panel-donut">
                                <div class="panel-heading">
                                    <i class="glyphicon glyphicon-signal"></i> Classes
                                    <span class="pull-right"><button type="button" class="btn btn-default btn-small btn-auto-update" aria-label="auto-update graph" title="auto-update graph" data-toggle="tooltip" data-placement="left"><span class="glyphicon glyphicon-clock"></span></button></span>
                                    <span class="clearfix">
                                </div>
                                <div class="panel-body">
                                    <div id="class-donut"></div>
                                </div>
                            </div>
                -->
            <div class="panel panel-default" id="school-years-selection">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-calendar"></i> b. School Years
                    <span class="pull-right">

                    </span>
                    <span class="clearfix"></span>
                </div>
                <div class="panel-body">
                    <div id="year-list" class="list-group">
                        <?php if( ! empty($school_years) ) { ?>
                        <?php foreach( $school_years as $year ) { ?>
                        <?php if( $year['c'] == 1 ) { ?>
                        <a href="#" class="list-group-item list-year" data-id="<?php echo $year['i']; ?>">
                            <div class="row">
                                <div class="col-lg-12 col-md-12"><?php echo $year['v']; ?></div>
                            </div>
                        </a>
                        <?php } ?>
                        <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-3">
            <div class="panel panel-default" id="school-teachers-selection">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-education"></i> c. Teachers
                    <span class="pull-right">
                    </span>
                    <span class="clearfix"></span>
                </div>
                <div class="panel-body">
                    <div id="teacher-list" class="list-group">
                        <?php
                            foreach( $a_teachers as $o_teacher ) {
                                if( user_can($o_teacher->ID, "teacher") ) {
                                    $s_teacher = ucwords(trim($o_teacher->first_name . ' ' . $o_teacher->last_name));
                                    ?>
                        <a href="#" class="list-group-item list-teacher" data-id="<?php echo $o_teacher->id_hash; ?>"
                            data-name="<?php echo $s_teacher; ?>">
                            <?php echo $s_teacher; ?>
                        </a>
                        <?php
                                }
                            }
                            ?>
                    </div>
                </div>
            </div>

            <div class="panel panel-default" id="class-licence">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-credit-card"></i> d. Licence
                    <span class="pull-right">
                    </span>
                    <span class="clearfix"></span>
                </div>
                <div class="panel-body">
                    <!-- 99999999 -->
                    <div id="licence-list" class="list-group">
                        <?php
                        $current_date = date('Y-m-d H:i:s');
                        $licence_table = $wpdb->prefix . "wushka_licence";
                        $licences = $wpdb->get_results(
                            $wpdb->prepare(
                                "SELECT * FROM $licence_table WHERE `account_id` = %s and `licence_end` > %s and `licence_type` not like '%Cancelled%' and `licence_type` not like '%Changed%' and licence_count > 0", $school_slug, $current_date
                            )
                        ); 
                        foreach( $licences as $licence ) { 
                                ?>
                        <a href="#" class="list-group-item list-single-licence"
                            data-id="<?= $licence->licence_product; ?>" data-name="<?= $licence->licence_product; ?>">
                            <?= $licence->licence_product; ?>
                        </a>
                        <?php 
                            }
                            ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-md-6">
            <div class="panel panel-default panel-classes">
                <div class="panel-heading">
                    <div class="panel-heading-group">
                        <i class="glyphicon glyphicon-group"></i> e. Classes:
                        <button type="button" class="btn btn-default btn-small btn-sort" data-action="asc">
                            <span class="glyphicon glyphicon-sort-by-order" data-toggle="tooltip" data-placement="top"
                                title="Sort by Year ascending"></span>
                            <span class="sr-only">Sort Ascending</span>
                        </button>
                        <button type="button" class="btn btn-default btn-small btn-sort" data-action="des">
                            <span class="glyphicon glyphicon-sort-by-order-alt" data-toggle="tooltip"
                                data-placement="top" title="Sort by Year descending"></span>
                            <span class="sr-only">Sort Descending</span>
                        </button>
                    </div>
                    <div class="panel-classes-note">
                        Drag and Drop School Years, Teachers, and Licence from the lists on the left into your classes
                    </div>
                    <span class="clearfix"></span>
                </div>
                <div class="panel-body">
                    <div class="row class-container">
                        <?php
                            if( ! empty($a_classes) ) {
                                foreach( $a_classes as $a_class ) {
                                    $o_class    = $a_class['class'];
                                    $class_year = explode(':', $o_class->year);
                                    $i_kids     = empty($a_class['students']) ? 0 : $a_class['students'];
                                    $i_total    = empty($o_class->students) ? 0 : $o_class->students;
                                    $s_name     = ucfirst(trim($o_class->name));
                                    ?>
                        <div class="col-lg-4 col-md-6 class" data-year="<?php echo $class_year[0]; ?>">
                            <div class="panel panel-default panel-class-editable" data-id="<?php echo $o_class->id; ?>">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-lg-10 col-md-10 col-sm-10">
                                            <button class="editable" data-id="name" data-title="Class Name"
                                                aria-label="Class <?= trim(str_replace('class','',strtolower($s_name))); ?>">
                                                <?php echo $s_name; ?>
                                            </button>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2" data-toggle="tooltip"
                                            data-placement="left" title="delete this class">
                                            <button type="button" class="close close-confirm class"
                                                aria-label="Delete Class"><span
                                                    class="glyphicon glyphicon-remove-2"></span></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <?php 
                                                if( isset($o_class->year) && ! empty($o_class->year) ) {
                                                    ?>
                                    <div class="row year <?php echo $class_year[0]; ?>"
                                        data-id="<?php echo $class_year[0]; ?>">
                                        <div class="col-lg-10 col-md-10"><i
                                                class="glyphicon glyphicon-calendar"></i>&nbsp;<?php echo $class_year[1]; ?>
                                        </div>
                                        <div class="col-lg-2 col-md-2" data-toggle="tooltip" data-placement="left"
                                            title="remove"><span class="pull-right"><button type="button"
                                                    class="close year" aria-label="Remove year">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button></span></div>
                                    </div>
                                    <?php
                                         } 
                                         
                                         if($o_class->licence_product != "0" || !empty($o_class->licence_product)) {
                                            //var_dump($o_class->licence_product);
                                    ?>
                                    <div
                                        class="row licence-row <?= sanitize_title_with_dashes($o_class->licence_product); ?>">
                                        <div class="col-lg-10 col-md-10"><i
                                                class="glyphicon glyphicon-credit-card"></i>&nbsp;<?= $o_class->licence_product; ?>
                                        </div>
                                        <div class="col-lg-2 col-md-2" data-toggle="tooltip" data-placement="left"
                                            title="remove"><span class="pull-right"><button type="button"
                                                    class="close licence-remove" aria-label="Remove Licence">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button></span></div>
                                    </div>
                                    <?php
                                                }

                                                if( ! empty($a_class['teachers']) ) {
                                                    foreach( $a_teachers as $o_teacher ) {
                                                        if( in_array($o_teacher->ID, $a_class['teachers']) ) {
                                                            $s_teacher = ucwords(trim($o_teacher->first_name . ' ' . $o_teacher->last_name));
                                                            ?>
                                    <div class="row teacher" data-class="<?php echo $o_class->id; ?>"
                                        data-id="<?php echo $o_teacher->id_hash; ?>"
                                        data-name="<?php echo $s_teacher; ?>">
                                        <div class="col-lg-10 col-md-10">
                                            <i class="glyphicon glyphicon-education"></i> <?php echo $s_teacher; ?>
                                        </div>
                                        <div class="col-lg-2 col-md-2" data-toggle="tooltip" data-placement="left"
                                            title="remove">
                                            <span class="pull-right">
                                                <button type="button" class="close teacher" aria-label="Remove Teacher">
                                                    <span class="glyphicon glyphicon-remove"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <?php
                                                        }
                                                    }
                                                } 
                                                
                                                if(empty($o_class->year) || empty($o_class->licence_product) || empty($a_class['teachers'])){

                                                    (empty($o_class->year))? $year = 'year, ' : $year = '' ; 
                                                    (empty($o_class->licence_product))? $licence = 'licence, ' : $licence = '' ; 
                                                    (empty($a_class['teachers']))? $teacher = 'teacher, ' : $teacher = '' ; 
                                                    
                                                    $class_set = rtrim($year.$licence.$teacher, ', ');

                                                    echo '<p class="small" style="color:red;">This Class does not have a '.$class_set.' set. Please drag and drop '.$class_set.' from the lists.</p>';
                                                }
                                                ?>
                                </div>
                                <div class="panel-footer">
                                    <div class="row students">
                                        <div class="col-lg-8 col-md-8">Students</div>
                                        <div class="col-lg-4 col-md-4 pull-right text-right" data-toggle="tooltip"
                                            data-placement="left" title="students registered/class size">
                                            <?php echo $i_kids; ?>/
                                            <button class="editable student-size" data-id="students" data-title="Class size">
                                                <?php echo $i_total; ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <?php
                                }
                            }
                            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="button-group-footer"><span class="btn-back-dashboard"><a href="/school-teachers" role="button"
                class="btn btn-primary btn-back-to-dashboard"><span class="glyphicon glyphicon-chevron-left"></span>
                Previous Step</a></span>
        <span class="btn-back-dashboard"><a href="/school-students" role="button"
                class="btn btn-primary btn-back-to-dashboard"> Finished: Go to Step 4 <span
                    class="glyphicon glyphicon-chevron-right"></span></a></span>
    </div>

</div>
<!-- template components -->
<div class="col-lg-4 col-md-6 class" id="class-template">
    <div class="panel panel-default panel-class-editable">
        <div class="panel-heading">
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10"><span class="editable" data-id="class-name"
                        data-title="Class name"></span></div>
                <div class="col-lg-2 col-md-2 col-sm-2" data-toggle="tooltip" data-placement="left"
                    title="delete this class">
                    <button type="button" class="close close-confirm class" aria-label="Delete Class"><span
                            class="glyphicon glyphicon-remove-2"></span></button>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <p class="small" style="color:red;">This Class does not have a teacher or year set. Please drag and
                drop teachers and years from the lists.</p>
        </div>
        <div class="panel-footer">
            <div class="row students">
                <div class="col-lg-6 col-md-9">Students</div>
                <div class="col-lg-3 col-md-2 pull-right text-right" data-toggle="tooltip" data-placement="left"
                    title="students registered/class size">
                    0/<span class="editable" data-id="students" data-title="Class size"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row year" id="year-template">
    <div class="col-lg-10 col-md-10"><i class="glyphicon glyphicon-calendar"></i></div>
    <div class="col-lg-2 col-md-2" data-toggle="tooltip" data-placement="left" title="remove"><span
            class="pull-right"><button type="button" class="close year" aria-label="Remove year"><span
                    class="glyphicon glyphicon-remove"></span></button></span></div>
</div>
<div class="row teacher" id="teacher-template">
    <div class="col-lg-10 col-md-10"><i class="glyphicon glyphicon-education"></i></div>
    <div class="col-lg-2 col-md-2" data-toggle="tooltip" data-placement="left" title="remove"><span
            class="pull-right"><button type="button" class="close teacher" aria-label="Remove Teacher"><span
                    class="glyphicon glyphicon-remove"></span></button></span></div>
</div>
<div class="modal fade" id="class-delete-dialog" tabindex="-1" role="dialog" aria-labelledby="cd-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-xl" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="cd-label">Delete Class?</h3>
            </div>
            <div class="modal-body">
                <p>Confirm that you wish to delete this class</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="class-confirm" data-id="">Delete Class</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="help-media" tabindex="-1" role="dialog" aria-labelledby="help-media" aria-hidden="true">
    <div class="container-wrapper video-wushka">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center dummy-video">
                    <div class="text-center embed-responsive embed-responsive-16by9 video-item-wrapper">
                        <span class="glyphicon glyphicon-play x2 btn-play-pause"></span>
                        <video controls id="video1" class="embed-responsive-item video-item">
                            <source src="//cdn1.wushka.com.au/Resources/manage-class-list.mp4" type="video/mp4">
                        </video>
                    </div>
                    <div class="col-xs-4 col-xs-offset-4 padding-y">
                        <button type="button" class="btn btn-primary btn-block" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="scroll-up">DRAG HERE TO SCROLL UP&nbsp;<i class="x2 glyphicon glyphicon-circle-arrow-top"></i></div>
<div class="scroll-down">DRAG HERE TO SCROLL DOWN&nbsp;<i class="x2 glyphicon glyphicon-circle-arrow-down"></i>
</div>
<script>
var donut;
var graph_timer;
jQuery(document).ready(function($) {
    var classObj = null;
    //        function renderGraph(donut, type) {
    //            $.ajax({
    //                type: "POST",
    //                dataType: "json",
    //                url: "<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>",
    //                data: {action: "wushka_class_graph", type: JSON.stringify(type), school: JSON.stringify('<?php echo $school_id ?>')},
    //                success: function (data) {
    //                    if (typeof donut === 'undefined') {
    //                        donut = Morris.Donut({
    //                            element: 'class-donut',
    //                            resize: 'true',
    //                            data: data.data,
    //                            formatter: function (value, data) {
    //                                if (typeof classObj !== undefined && classObj !== null && classObj.length !== 0) {
    //                                    classObj.removeClass('highlighted');
    //                                }
    //                                classObj = $('div.row.' + data.id);
    //                                classObj.addClass('highlighted');
    //                                return data.value;
    //                            }
    //                        }).on('click', function (i, row) {
    //                            console.log(i, row);
    //                        });
    //                        setdonut(donut);
    //                    } else {
    //                        donut.setData(data);
    //                    }
    //                }
    //            });
    //        }
    function teacherListDrag() {
        $('#teacher-list a.list-teacher').draggable({
            revert: 'invalid',
            containment: 'document',
            cursor: 'pointer',
            helper: 'clone',
            appendTo: 'body'
        });
    }

    function licenceListDrag() {
        $('#licence-list a.list-single-licence').draggable({
            revert: 'invalid',
            containment: 'document',
            cursor: 'pointer',
            helper: 'clone',
            appendTo: 'body'
        });
    }

    function yearListDrag() {
        $('#year-list a.list-year').draggable({
            revert: 'invalid',
            containment: 'document',
            cursor: 'pointer',
            helper: 'clone',
            appendTo: 'body'
        });
    }

    function teacherDrag() {
        $('.row.teacher:not(.ui-draggable)').draggable({
            revert: 'invalid',
            containment: '.panel-classes',
            cursor: 'pointer',
            helper: 'clone',
            appendTo: 'body',
            zIndex: 1000
        });
    }

    function tooltips() {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    }

    function convertToSlug(text) {
        return text
            .toString() // Cast to string
            .toLowerCase() // Convert the string to lowercase letters
            .normalize(
                'NFD') // The normalize() method returns the Unicode Normalization Form of a given string.
            .trim() // Remove whitespace from both sides of a string
            .replace(/\s+/g, '-') // Replace spaces with -
            .replace(/[^\w\-]+/g, '') // Remove all non-word chars
            .replace(/\-\-+/g, '-'); // Replace multiple - with single -
    }

    function notificationDisplay(message, messageType = 'success') {
        //Remove already open div
        if ($('.alert')) {
            $('.alert').fadeOut(1000, function() {
                $(this).remove();
            });
        }
        var alertDiv = '<div class="alert alert-' + messageType + ' alert-dismissible" role="alert">' +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
            message +
            '</div>';
        return alertDiv;
    }

    function autoCloseNotification(delayTime = 4000) {
        $(".alert").delay(delayTime).slideUp(200, function() {
            $(this).alert('close');
        });
    }

    function missingElements(year, licence, teacher) {
        var message = "";
        if (typeof year === 'undefined' || typeof licence === 'undefined' ||
            typeof teacher === 'undefined') {
            (typeof year === 'undefined') ? year = 'year, ': year = '';
            (typeof licence === 'undefined') ? licence = 'licence, ': licence = '';
            (typeof teacher === 'undefined') ? teacher = 'teacher, ': teacher = '';

            var str = year + licence + teacher;
            var missingElements = str.replace(/,\s*$/, "");

            message =
                '<p class="small" style="color:red;">This Class does not have a ' +
                missingElements +
                ' set. Please drag and drop ' + missingElements + ' from the lists';

        }
        return message;
    }

    function droppable() {
        $('.panel-class-editable:not(ui.droppable)').droppable({
            accept: '#teacher-list a, #year-list a, .row.teacher, #licence-list a',
            drop: function(event, ui) {
                validDrop = true;
                var message = false;
                if (ui.draggable.hasClass('teacher') || ui.draggable.hasClass('list-teacher')) {
                    dropRows = $(this).find('div.row.teacher');
                    dropRows.each(function() {
                        if ($(this).attr('data-name') === ui.draggable.attr('data-name')) {
                            validDrop = false;
                        }
                    });
                    if (!validDrop) {
                        return false;
                    }
                    $(this).find('div.panel-body p.small').remove();
                    if (ui.draggable.hasClass('teacher')) {
                        $(this).find('div.panel-body').append(ui.draggable);
                    } else {
                        $(this).find('div.panel-body').append($(
                            '<div class="row teacher" data-id="' + ui.draggable.attr('data-id') +'" data-name="' + ui.draggable.attr('data-name') +'"><div class="col-lg-10 col-md-10"><i class="glyphicon glyphicon-education"></i> ' + ui.draggable.text() + '</div><div class="col-lg-2 col-md-2" data-toggle="tooltip" data-placement="left" title="remove"><span class="pull-right"><button type="button" class="close teacher" aria-label="Remove Teacher"><span class="glyphicon glyphicon-remove"></span></button></span></div></div>'
                        ));
                    }
                    var json = {
                        id: $(this).attr('data-id'),
                        teacher_add: ui.draggable.attr('data-id'),
                        teacher_rem: ui.draggable.attr('data-class'),
                        school_id: <?php echo $school_id ?>
                    }

                    //Alert missing elements
                    $(this).find('div.panel-body p.small').remove();
                    var year = $(this).find('div.panel-body .year').html();
                    var licence = $(this).find('div.panel-body .licence-row').html();
                    var teacher = $(this).find('div.panel-body .teacher').html();

                    var missingMessage = missingElements(year, licence, teacher);

                    $(this).find('div.panel-body').append(missingMessage);
                    //end alert

                } else if (ui.draggable.hasClass('list-single-licence')) { 
                    var json = {
                        id: $(this).attr('data-id'),
                        licence: $.trim(ui.draggable.text()),
                        school_id: <?php echo $school_id ?>
                    };


                    //Alert missing elements
                    $(this).find('div.panel-body p.small').remove();
                    var year = $(this).find('div.panel-body .year').html();
                    var licence = $(this).find('div.panel-body .licence-row').html();
                    var teacher = $(this).find('div.panel-body .teacher').html();

                    var missingMessage = missingElements(year, licence, teacher);

                    $(this).find('div.panel-body').append(missingMessage);
                    //end alert 

                } else {
                    $(this).find('div.panel-body p.small').remove();
                    if ($(this).find('div.row.year').length !== 0) {
                        $(this).find('div.row.year').remove();
                    }
                    $(this).find('div.panel-body').prepend($('<div class="row year ' + ui.draggable
                        .attr('data-id') +
                        '"><div class="col-lg-10 col-md-10"><i class="glyphicon glyphicon-calendar"></i> ' +
                        ui.draggable.text() +
                        '</div>    <div class="col-lg-2 col-md-2" data-toggle="tooltip" data-placement="left" title="remove"><span class="pull-right"><button type="button" class="close teacher" aria-label="Remove Year"><span class="glyphicon glyphicon-remove"></span></button></span></div></div>'
                    ));
                    $(this).closest('div.class').attr('data-year', ui.draggable.attr('data-id'));
                    var json = {
                        id: $(this).attr('data-id'),
                        year: ui.draggable.attr('data-id') + ':' + $.trim(ui.draggable.text()),
                        school_id: <?php echo $school_id ?>
                    };

                    //Alert missing elements
                    $(this).find('div.panel-body p.small').remove();
                    var year = $(this).find('div.panel-body .year').html();
                    var licence = $(this).find('div.panel-body .licence-row').html();
                    var teacher = $(this).find('div.panel-body .teacher').html();

                    var missingMessage = missingElements(year, licence, teacher);

                    $(this).find('div.panel-body').append(missingMessage);
                    //end alert
                }
                save(json, $(this), ui);

                teacherDrag();
                tooltips();

                
            }
        });
    }

    $(document).on('click', '.close.licence-remove', function(e) {
        e.preventDefault();
        json = {
            id: $(this).closest('div.panel').attr('data-id'),
            licence: '0',
            school_id: <?php echo $school_id; ?>,
        };
        save(json);
        $(this).closest('div.row').remove();



        var message =
            '<strong>Note: </strong> Licence updates are applied immediately which might affect logged in users.';
        if (message) {
            $('.panel[data-id=' + json.id + ']').closest('.class').prepend(notificationDisplay(message))
                .hide().fadeIn(1000);
        }
        autoCloseNotification();

    });



    $(document).on("click", '.panel-class-editable .close.teacher, .panel-class-editable .close.year', function(
        e) {
        e.preventDefault();
        var year, teacher;
        $row = $(this).closest('div.row');
        if ($row.hasClass('year')) {
            year = '';
        } else {
            teacher = $row.attr('data-id');
        }
        var json = {
            id: $(this).closest('div.panel').attr('data-id'),
            year: year,
            school_id: <?php echo $school_id; ?>,
            teacher_add: teacher,
            teacher_rem: $(this).closest('div.panel').attr('data-id')
        };
        save(json);
        $(this).closest('div.row').remove();
    });
    $('.btn-add-class').on('click', function() {
        $('#class-msg').hide();
        var e_item = $(this);
        wushka_button_loading(e_item, 'Creating...');
        var json = {
            name: $('#class_name').val(),
            school: '<?php echo $school_id; ?>',
            size: $('#class_size').val()
        }
        $.ajax({
            url: "<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>",
            type: "POST",
            data: {
                action: 'wushka_class_create',
                json: JSON.stringify(json)
            },
            success: function(data) {
                var result = JSON.parse(data);
                if (result.result === 'success') {
                    $class = $('#class-template').clone();
                    $class.attr('id', '');
                    $class.find('div.panel').attr('data-id', result.id);
                    $class.find('[data-id="class-name"]').text($('#class_name').val());
                    $class.find('div.row.students span.editable').text($('#class_size')
                        .val());
                    $class.find('div.panel-body').prepend($('<div class="row licence-row ' +
                        convertToSlug(result.licence) +
                        '"><div class="col-lg-10 col-md-10"><i class="glyphicon glyphicon-credit-card"></i> ' +
                        result.licence +
                        '</div>    <div class="col-lg-2 col-md-2" data-toggle="tooltip" data-placement="left" title="remove"><span class="pull-right"><button type="button" class="close licence-remove" aria-label="Remove Licence"><span class="glyphicon glyphicon-remove"></span></button></span></div></div>'
                    ));
                    $('div.row.class-container').prepend($class);
                    //                        $('div.row.class-container').append($class);
                    $('#class_name').val('');
                    //$('#class_size').val('');
                    wushka_button_finished(e_item, 'Created!', 'Add Class');
                } else {
                    $('#class-msg').text(result.msg);
                    $('#class-msg').show();
                    wushka_button_failed(e_item, 'Error', 'Add Class');
                }
                tooltips();
                droppable();
                editable();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
                wushka_button_failed(e_item, 'Error', 'Add Class');
            }
        });
    });
    $(document).on("click", '.close.close-confirm', function(e) {
        $('#class-confirm').attr('data-id', $(this).closest('div.panel-class-editable').attr(
            'data-id'));
        $('#class-delete-dialog').modal('show');
    });
    $(document).on("click", '#class-confirm', function(e) {
        console.log('confirm deleted:' + $(this).attr('data-id'));
        $('div.row.class-container div.class [data-id="' + $(this).attr('data-id') + '"]').closest(
            'div.class').remove();
        $.ajax({
            type: "POST",
            url: "<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>",
            data: {
                action: "wushka_class_delete",
                json: JSON.stringify({
                    id: $(this).attr('data-id')
                })
            }
        });
        $('#class-delete-dialog').modal('hide');
    });

    function editable() {
        $(".editable").editable({
            type: 'text',
            emptytext: 'Not set',
            success: function(response, value) {
                var field = $(this).attr('data-id');
                if (field === 'name') {
                    var json = {
                        id: $(this).closest('div.panel').attr('data-id'),
                        name: value
                    };
                    save(json);
                } else if (field === 'students') {
                    var json = {
                        id: $(this).closest('div.panel').attr('data-id'),
                        students: value
                    };
                    save(json);
                }
            }
        });
    }

    function isJson(item) {
        item = typeof item !== "string"
            ? JSON.stringify(item)
            : item;

        try {
            item = JSON.parse(item);
        } catch (e) {
            return false;
        }

        if (typeof item === "object" && item !== null) {
            return true;
        }

        return false;
    }

    function save(json, $that = null, ui = null) {
        $.ajax({
            type: "POST",
            url: "<?php echo esc_url(wp_nonce_url(site_url("wp-admin/admin-ajax.php"), "wp-admin/admin-ajax.php")); ?>",
            data: {
                action: "wushka_class_save",
                json: JSON.stringify(json)
            }
        })
        .done((data, status, xhr) => { 
          if(data.length !== 0){
            var dataMessage = data;  
            
            var warningMessage = '';
            if(isJson(dataMessage)){ 
                let dataParse = JSON.parse(dataMessage); 
                dataMessage = dataParse.licence_product; 
                warningMessage = dataParse.message; 
            } 

            if(dataMessage === json.licence){  
                if ($that.find('div.row.licence-row').length !== 0) {
                    $that.find('div.row.licence-row').remove(); 
                }
                $that.children('.panel-body').prepend($('<div class="row licence-row ' +
                    convertToSlug(ui.draggable.text()) +
                    '"><div class="col-lg-10 col-md-10"><i class="glyphicon glyphicon-credit-card"></i> ' +
                    ui.draggable.text() +
                    '</div>    <div class="col-lg-2 col-md-2" data-toggle="tooltip" data-placement="left" title="remove"><span class="pull-right"><button type="button" class="close licence-remove" aria-label="Remove Licence"><span class="glyphicon glyphicon-remove"></span></button></span></div></div>'
                ));
                $that.closest('div.class').attr('data-licence', convertToSlug(ui.draggable.text()));
                if(!warningMessage){
                    message = '<strong>Note: </strong> Licence updates are applied immediately which might affect logged in users.';
                    if (message) {
                        $that.closest('.class').prepend(notificationDisplay(message)).hide().fadeIn(1000);
                    }
                }else{
                    $that.closest('.class').prepend(notificationDisplay('<strong>NOTICE: </strong> ' + warningMessage, 'warning')).hide().fadeIn(1000);
                }
            }else{ 
                if(xhr.status === 202){
                    $that.closest('.class').prepend(notificationDisplay('<strong>NOTICE: </strong> ' + dataMessage, 'warning')).hide().fadeIn(1000);
                }else{
                    $that.closest('.class').prepend(notificationDisplay('<strong>Invalid Licence: </strong> ' + dataMessage, 'danger')).hide().fadeIn(1000);
                } 
            } 

            if(xhr.status !== 202){
                autoCloseNotification(); 
            }
            
          }  
        });
    }

    

    //        var is_real_time = false;
    //        $(document).on("click", '.btn-auto-update', function (e) {
    //            e.preventDefault();
    //            if (!is_real_time) {
    //                graph_timer = setInterval(function () {
    //                    renderGraph(donut, 'donut');
    //                }, 10000);
    //                is_real_time = true;
    //                $('#panel-donut').append('<div class="panel-footer">Auto update mode</div>')
    //            } else {
    //                clearInterval(graph_timer);
    //                is_real_time = false;
    //                $('#panel-donut div.panel-footer').remove();
    //            }
    //        });
    //        function setdonut(graph) {
    //            donut = graph;
    //        }
    $(document).on("click", '.btn-sort', function(e) {
        e.preventDefault();
        var sort_sequence = $(this).attr('data-action');
        var classes_container = $('div.class-container');
        var classes = classes_container.children('div.class').get();
        classes.sort(function(a, b) {
            var A = $(a).attr('data-year');
            var B = $(b).attr('data-year');
            if (sort_sequence === 'asc') {
                return A.localeCompare(B);
            } else {
                return B.localeCompare(A);
            }
        });
        $.each(classes, function(key, item) {
            classes_container.append(item);
        });

    });

    teacherDrag();
    teacherListDrag();
    licenceListDrag();
    yearListDrag();
    droppable();
    tooltips();
    editable();
    //        renderGraph(donut, 'donut');
    $('.panel-class-editable .panel-body').equalHeights();
    var stop = true;
    var e_draggable = $('.ui-draggable');

    e_draggable.on("drag", function(e) {

        $('.navbar-wushka').addClass('slide');
        $('.navbar-fixed-bottom').addClass('slide');

        $('.scroll-up').addClass('slide');
        $('.scroll-down').addClass('slide');

        stop = true;

        if (e.originalEvent.clientY < 150) {
            stop = false;
            scroll(-1)
        }

        if (e.originalEvent.clientY > ($(window).height() - 150)) {
            stop = false;
            scroll(1)
        }
    });

    e_draggable.on("dragstop", function(e) {

        $('.navbar-wushka').removeClass('slide');
        $('.navbar-fixed-bottom').removeClass('slide');
        $('.scroll-up').removeClass('slide');
        $('.scroll-down').removeClass('slide');

        stop = true;
    });

    var scroll = function(step) {
        var scrollY = $(window).scrollTop();
        $(window).scrollTop(scrollY + step);
        if (!stop) {
            setTimeout(function() {
                scroll(step)
            }, 20);
        }
    }
    jQuery('.page-template-school_classes div[data-year]').each(function(index) {
        var studentVal = jQuery(this).find("div[data-placement='left']").text().trim().split("/")[0];
        console.log(studentVal);
        if (studentVal > 0) {
            jQuery(this).find('.close-confirm').prop("disabled", "true").css("cursor", "not-allowed");
            jQuery(this).find('div[data-toggle="tooltip"]').attr("data-original-title",
                "Archive students or move students to new class before deleting");
        }
    });
});
</script>
<?php
//Add Footer
include 'dashboard_options.php';
get_footer();
/* ----- END OF FILE ----- */