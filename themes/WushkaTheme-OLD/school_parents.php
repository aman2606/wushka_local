<?php

/*
  Template Name: School parents
 */

//Is User Logged In AND is user a school?
if (!user_can($current_user, "administrator")) {
    if (!is_user_logged_in() || !user_can($current_user, "school")) {
        //Redirect to Login Page
        wp_redirect(esc_url(get_permalink(get_page_by_title('Login'))));
        exit;
    }
}
$school = $terms = wp_get_object_terms($current_user->ID, 'school');
//error_log('school:' . print_r($school, true));
$school_id = $school[0]->term_taxonomy_id;
$parents = wushka_get_parents($school_id);
//error_log('parents:' . print_r($parents, true));

get_header();
?>
<style>
.timeline {
    list-style: none;
    padding: 15px 0 15px;
    position: relative;
}

    .timeline:before {
        top: 0;
        bottom: 0;
        position: absolute;
        content: " ";
        width: 3px;
        background-color: #eeeeee;
        left: 50%;
        margin-left: -1.5px;
    }

    .timeline > li {
        margin-bottom: 20px;
        position: relative;
    }

        .timeline > li:before,
        .timeline > li:after {
            content: " ";
            display: table;
        }

        .timeline > li:after {
            clear: both;
        }

        .timeline > li:before,
        .timeline > li:after {
            content: " ";
            display: table;
        }

        .timeline > li:after {
            clear: both;
        }

        .timeline > li > .timeline-panel {
            width: 44%;
            float: left;
            border: 1px solid #d4d4d4;
            border-radius: 2px;
            padding: 15px;
            position: relative;
            -webkit-box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.175);
        }

            .timeline > li > .timeline-panel:before {
                position: absolute;
                top: 26px;
                right: -15px;
                display: inline-block;
                border-top: 15px solid transparent;
                border-left: 15px solid #ccc;
                border-right: 0 solid #ccc;
                border-bottom: 15px solid transparent;
                content: " ";
            }

            .timeline > li > .timeline-panel:after {
                position: absolute;
                top: 27px;
                right: -14px;
                display: inline-block;
                border-top: 14px solid transparent;
                border-left: 14px solid #fff;
                border-right: 0 solid #fff;
                border-bottom: 14px solid transparent;
                content: " ";
            }

        .timeline > li > .timeline-badge {
            color: #fff;
            width: 50px;
            height: 50px;
            text-align: center;
            position: absolute;
            top: 16px;
            left: 50%;
            margin-left: -25px;
            z-index: 100;
            border-radius: 50%;
        }

        .timeline > li.timeline-inverted > .timeline-panel {
            float: right;
        }

            .timeline > li.timeline-inverted > .timeline-panel:before {
                border-left-width: 0;
                border-right-width: 15px;
                left: -15px;
                right: auto;
            }

            .timeline > li.timeline-inverted > .timeline-panel:after {
                border-left-width: 0;
                border-right-width: 14px;
                left: -14px;
                right: auto;
            }

.timeline-badge.primary {
    background-color: #2e6da4 !important;
}

.timeline-badge.success {
    background-color: #3f903f !important;
}

.timeline-badge.warning {
    background-color: #f0ad4e !important;
}

.timeline-badge.danger {
    background-color: #d9534f !important;
}

.timeline-badge.info {
    background-color: #5bc0de !important;
}

.timeline-title {
    margin-top: 0;
    color: inherit;
}

.timeline-body > p,
.timeline-body > ul {
    margin-bottom: 0;
}

    .timeline-body > p + p {
        margin-top: 5px;
    }

@media (max-width: 767px) {
    ul.timeline:before {
        left: 40px;
    }

    ul.timeline > li > .timeline-panel {
        width: calc(100% - 90px);
        width: -moz-calc(100% - 90px);
        width: -webkit-calc(100% - 90px);
    }

    ul.timeline > li > .timeline-badge {
        left: 15px;
        margin-left: 0;
        top: 16px;
    }

    ul.timeline > li > .timeline-panel {
        float: right;
    }

        ul.timeline > li > .timeline-panel:before {
            border-left-width: 0;
            border-right-width: 15px;
            left: -15px;
            right: auto;
        }

        ul.timeline > li > .timeline-panel:after {
            border-left-width: 0;
            border-right-width: 14px;
            left: -14px;
            right: auto;
        }
}
</style>
<div class="container-fluid">

<div class="row mt30">
    <div class="col-xs-12">
      <h1 class="glyphicon-heading">
        <span class="x2 glyphicon glyphicon-parents hidden-xs"></span>
        <span class="glyphicon-heading-text">Home Subscribers: <?php echo $school[0]->name?></span>
        <span class="glyphicon-heading-btn-group">
            <span class="btn-back-dashboard"><a href="/school-dashboard" role="button" class="btn btn-primary btn-back-to-dashboard"><span class="glyphicon glyphicon-chevron-left"></span> Back to Dashboard</a></span>
        </span>
      </h1>
    </div>
</div>

    <div class="row">
        <div class="col-lg-7 col-md-7">
            <div class="panel panel-default panel-parents">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-parents"></i> Parents
                    <?php /* ?><span class="pull-right">
                        <a role="button" tabindex="0" class="btn btn-default btn-small" data-toggle="popover" data-trigger="focus" data-placement="left" data-html="true" title="Icon Help" data-content="<p><span class='glyphicon glyphicon-remove'></span> Delete Class</p><p><span class='glyphicon glyphicon-remove'></span> Remove this item</p><p><span class='glyphicon glyphicon-pencil'></span> Edit this field</p>"><span class="glyphicon glyphicon-circle-question-mark help-group-popover"></span></a>
                    </span><?php */ ?>
                    <span class="clearfix">
                </div>
                <div class="panel-body">
                    <div class="row parent-container">
                        <?php
                        foreach ($parents as $parent) {
                        ?>
                        <div class="col-lg-4 col-md-4 parent" data-id="<?php echo $parent->id_hash?>">
                            <div class="panel panel-default panel-parent">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12"><a class="editable" data-id="first_name" data-title="First name"><?php echo $parent->first_name?></a> <a class="editable" data-id="last_name" data-title="Last name"><?php echo $parent->last_name?></a></div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <i class="glyphicon glyphicon-user"></i>
                                            <a class="uneditable" data-id="login"><?php echo $parent->user_login?></a>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <i class="glyphicon glyphicon-envelope"></i>
                                            <a class="editable" data-id="email" data-title="Email address"><?php echo $parent->user_email?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                </div>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 col-md-5">
            <div class="panel panel-default" id="timeline">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12"><i class="glyphicon glyphicon-stopwatch"></i> Registration Timeline</div>
                    </div>
                </div>
                <div class="panel-body">
                    <ul class="timeline">
                    <?php
                    $class = 'timeline-inverted';
                    foreach ($parents as $parent) {
                        $dt = new DateTime($parent->user_registered, new DateTimeZone(date_default_timezone_get()));
                        $dt->setTimeZone(new DateTimeZone(get_option('timezone_string')));
                        if ($class === 'timeline-inverted') {
                            $class = '';
                        } else {
                            $class = 'timeline-inverted';
                        }
                    ?>
                    <li class='<?php echo $class ?>'>
                        <div class="timeline-badge bg-purple"><i class="glyphicon glyphicon-user"></i></div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4><?php echo $parent->first_name?> <?php echo $parent->last_name?></h4>
                                <small class="text-muted"><i class="glyphicon glyphicon-clock"></i> <?php echo $dt->format("l, dS M Y g:ia")?></small>
                            </div>
                        </div>
                    </li>
                    <?php
                    }
                    ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function($) {
    function tooltips() {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    }
    tooltips();
});
</script>
<?php
//Add Footer
include 'dashboard_options.php';
get_footer();
?>
