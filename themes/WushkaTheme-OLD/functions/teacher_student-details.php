<?php
/* --- BOF --- */
?>
<!-- Student Statistics Page HTML -->
<!-- Graph Section -->
<div class="col-xs-12" id="screen-grab">
    <div class="col-sm-12" id="statistics-wrap">
        <?php global $current_user; ?>
        <?php if( user_can($current_user, 'student') ) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-star"></i> 5 of My Favourites
                <span class="pull-right">
                </span>
                <span class="clearfix"></span>
            </div>
            <div class="panel-body">
                <div class="student-details top-books"></div>
            </div>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-address-book"></i> Reading Record
                <span class="pull-right">
                </span>
                <span class="clearfix"></span>
            </div>
            <div class="panel-body">
                <div class="statistic-section-wrap page-middle" id="student_graphs">
                    <div class="row">
                        <div class="col-xs-12">
                            <label class="line-graph y-axis">Number of Readers</label>
                            <label class="line-graph x-axis">Last 7 Days</label>
                            <label class="bar-graph x-axis"> Last 4 Weeks</label>

                            <div id="student-line-graph"></div>
                            <div id="student-bar-graph"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-stats"></i> Reading Statistics
                <span class="pull-right">
                </span>
                <span class="clearfix"></span>
            </div>
            <div class="panel-body">
                <div class="row statistic-section-wrap page-bottom-left" id="pie-chart-section">
                    <div class="col-lg-8 col-md-8 col-sm-12">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="glyphicon glyphicon-signal"></i> New vs Reread
                                    <span class="pull-right">
                                    </span>
                                    <span class="clearfix"></span>
                                </div>
                                <div class="panel-body">
                                    <div class="pie-chart chart" id="section-1-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="glyphicon glyphicon-signal"></i> Read vs Unread
                                    <span class="pull-right">
                                    </span>
                                    <span class="clearfix"></span>
                                </div>
                                <div class="panel-body">
                                    <div class="pie-chart chart" id="section-3-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="glyphicon glyphicon-signal"></i> Fiction vs Non-Fiction
                                    <span class="pull-right">
                                    </span>
                                    <span class="clearfix"></span>
                                </div>
                                <div class="panel-body">
                                    <div class="pie-chart chart" id="section-2-chart"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <i class="glyphicon glyphicon-signal"></i> Read With Narration vs Without
                                    <span class="pull-right">
                                    </span>
                                    <span class="clearfix"></span>
                                </div>
                                <div class="panel-body">
                                    <div class="pie-chart chart" id="section-4-chart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="glyphicon glyphicon-person-running"></i> Reading Level Progress
                                <span class="pull-right">
                                </span>
                                <span class="clearfix"></span>
                            </div>
                            <div class="panel-body">
                                <div id="ebook-rating-section"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="glyphicon glyphicon-star-half"></i> Online Quiz Results
                <span class="pull-right">
                    <a href="/quiz-results/" class="btn btn-default btn-small" title="View Student's Quiz Results">
                        See All Quiz Results<span class="glyphicon glyphicon-conversation"
                            style="padding-left:5px;"></span>
                    </a>
                    <!-- <a href="/quiz-results/">
                    <button type="submit" class="btn btn-default btn-small" title="View Student's Quiz Results">
                        See All Quiz Results<span class="glyphicon glyphicon-conversation"
                                                  style="padding-left:5px;"></span>
                    </button>
                </a> -->
                </span>
                <span class="clearfix"></span>
            </div>
            <div class="panel-body" style="overflow-y: hidden;">
                <div class="row">
                    <div class="col-xs-12">
                        <label class="quiz-graph y-axis">Correct / Incorrect Answers</label>
                        <label class="quiz-graph x-axis">Quizzes</label>
                        <label class="quiz-empty">No quiz results have been found</label>

                        <div id="student-quiz-graph"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pie Chart Section -->
        <!-- /div -->
    </div>
</div>
<!-- END Student Statistics HTML -->
<?php 
$taxonomies = get_terms( array(
    'taxonomy' => 'reading-level',
    'orderby' => 'slug',
    'order' => 'ASC', 
) );

$gold_and_above_slug = [];
$count = 1;
foreach($taxonomies as $taxonomy){
    if($count > 8 ){
        $gold_and_above_slug[] = '"'.$taxonomy->slug.'"';
    }
    $count++;
}
$gold_and_above_slug = implode( ', ', $gold_and_above_slug );
?>
<script>
    function decodable_reading_level(status = false){
        let hide = [<?=$gold_and_above_slug;?>];
        $('.level-wrap').each(function(){
            let progress = $(this).find('.progress').attr('data-id');
            if(hide.includes(progress)){
                if(status){
                    $(this).hide();
                }else{
                    $(this).show();
                }
                
            } 
        });
    }
    $(document).ajaxSuccess(function(event, request, settings) {
        let jsonResponse = request.responseJSON;
        if(jsonResponse.hasOwnProperty('licence')){
            let licence = jsonResponse.licence; 
            if(licence == 'Wushka Decodables'){
                decodable_reading_level(true);
            }else{
                decodable_reading_level(false);
            }
        }
        
        
    });
</script>

<?php
/* ----- End Of single-student-detail.php file ----- */