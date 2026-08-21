<?php
/* --- BOF --- */
?>
<!-- Student Statistics Page HTML -->
<!-- Graph Section -->
<?php global $current_user; ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <i class="glyphicon glyphicon-stats"></i> Reading Statistics
        <span class="pull-right"></span>
        <span class="clearfix"></span>
    </div>
    <div class="panel-body">
        <div class="row statistic-section-wrap page-bottom-left" id="pie-chart-section">
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
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="glyphicon glyphicon-signal"></i> Reading Record
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
                </div>
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
                            <i class="glyphicon glyphicon-signal"></i> Fiction vs Non-fiction
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
                            <i class="glyphicon glyphicon-signal"></i> Read with narration vs without
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
        </div>
    </div>
</div>
<!-- Pie Chart Section -->
<!-- /div -->
<!-- END Student Statistics HTML -->
<?php
/* ----- End Of single-student-detail.php file ----- */