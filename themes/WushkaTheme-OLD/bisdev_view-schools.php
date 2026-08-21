<?php
/**
 * Template Name: Business Dev - View Schools
 * Created by PhpStorm.
 * User: Jordan
 * Date: 22/09/2015
 * Time: 2:11 PM
 */
include_once('functions/class_view-schools.php');
$c_page = new View_Schools();
//Is BisDev logged in?
if ( ! $c_page->has_access() ) {
    $c_page->redirect();
}

get_header();

//wushka_page_header('View Schools', 'user');

//echo $c_page->load_page();

$a_search = $c_page->load_search_schools();

?>
<style>
</style>
<script>
    var a_search = <?php echo json_encode($a_search); ?>;
    var i_current = '<?php echo $current_user->id_hash; ?>';
</script>
<div class="container-fluid">
    <div class="row mt30"> 
        <div class="col-xs-12"> 
            <h1 class="glyphicon-heading"><span class="x2 glyphicon glyphicon-education hidden-xs"></span><span class="glyphicon-heading-text">View Schools</span></h1>
        </div>
        <section class='page-section padding-y grad-radial'>
            <div class="settings panel-wrapper col-xs-12" id="panel_4">
                <!-- SEARCH SECTION -->
                <div class="col-xs-12 col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="input-group">
                                <input type="text" id="search_field" class="form-control" placeholder="Enter a school customer code here...">
                                <span id="activate_search" class="input-group-addon"><span class="glyphicon glyphicon-search"></span> </span>
                            </div>
                            <!-- RESULTS SECTION -->
                            <div id="results-info"></div>
                            <div id="results-wrap"></div>
                        </div>
                    </div>
                </div>
                <!-- DETAILS SECTION-->
                <div class="col-xs-12 col-sm-6">
                    <form class="form-horizontal">
                        <div class="panel panel-default" id="details_wrap">
                            <div class="panel-body"></div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
<?php

get_footer();

/* ----- END OF FILE ----- */
