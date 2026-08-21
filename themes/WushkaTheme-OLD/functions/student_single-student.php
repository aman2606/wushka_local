<?php
/* --- BOF --- */
?>
<!-- Student Statistics Page HTML -->
<div class="page-section student-statistics">
    <!-- div class="container" -->
    <!-- Details Section -->
    <!-- 	<div class=" container statistic-section-wrap page-buttons"  id="student_buttons">
		<div class="row">
				<div class="col-xs-3 col-sm-2"><input type="button" class="btn student-btn prev btn-default btn-lg btn-block" id="prev_student" value="Previous" /></div>
				<div class="col-xs-6 col-sm-8"><input type="button" class="btn student-btn back btn-default btn-lg btn-block" id="overall_students" value="Back to Student Overview" /></div>
				<div class="col-xs-3 col-sm-2"><input type="button" class="btn student-btn next btn-default btn-lg btn-block" id="next_student" value="Next" /></div>
		</div>
	</div> -->
    <h2 class="student_name"></h2>
    <div class="container statistic-section-wrap page-top" id="student_details">
        <div class="row">
            <!-- Details Section -->
            <div class="col-xs-12 col-sm-12">
                <div class="student-details top-books"></div>
                <!--<div class="statistic-section top left reading-history-wrapper">
					<div class="reading-history line row">
						<label class="col-xs-5 reading-history text-right" id="student-username"></label>
					</div>
					<div class="reading-history line row">
						<label class="col-xs-7">Password: </label>
						<label class="col-xs-5 reading-history text-right" id="student-pw"></label>
					</div>
				</div> -->
            </div>

            <!-- Button Section -->
            <!-- <div class="col-xs-12 col-sm-4 statistic-section top middle">
				<input type="button" class="student-btn top-section btn-student-badges btn btn-default btn-xl btn-block disabled" title="This feature is still in development and will be available soon" id="student-badges" value="Badges" />
				<input type="button" class="student-btn top-section btn-student-stories btn btn-default btn-xl btn-block disabled" title="This feature is still in development and will be available soon" id="student-stories" value="Stories" />
				<form method="post" action="<?php echo home_url().'/quiz-results/'; ?>">
					<input type="hidden" name="quiz_user" id="quiz_user" value="" />
					<input type="submit" class="student-btn top-section btn-student-results btn btn-default btn-xl btn-block" title="View student's quiz results" id="student-results" value="Quiz Results" />
				</form>
			</div>  -->

            <!-- eBook Section -->
            <div class="col-xs-12 col-sm-4 statistic-section top right">
                <a href="#" class="student-most-read" title="My Favourite Book"></a>
            </div>
        </div>
    </div>
    <div class="container statistic-section-wrap page-middle" id="student_graphs">
        <div class="row">
            <div class="ebook-rating-section page-bottom-right" id="ebook-rating-section">
                <div class="student-rating-section">
                    <div class="reading-level progress-wrap">
                        <h3>Reading Level Progress Chart</h3>
                        <div class="levels-wrap">
                            <div class="level-wrap">
                                <p>Magenta (Levels 1-2)</p>
                                <div class="progress a-magenta-levels-1-2">
                                    <div class="progress-bar a-magenta-levels-1-2" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Red (Levels 3-5)</p>
                                <div class="progress b-red-levels-3-5">
                                    <div class="progress-bar b-red-levels-3-5" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Yellow (Levels 6-8)</p>
                                <div class="progress c-yellow-levels-6-8">
                                    <div class="progress-bar c-yellow-levels-6-8" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Blue (Levels 9-11)</p>
                                <div class="progress d-blue-levels-9-11">
                                    <div class="progress-bar d-blue-levels-9-11" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Green (Levels 12-14)</p>
                                <div class="progress e-green-levels-12-14">
                                    <div class="progress-bar e-green-levels-12-14" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Orange (Levels 15-16)</p>
                                <div class="progress f-orange-levels-15-16">
                                    <div class="progress-bar f-orange-levels-15-16" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Turquoise (Levels 17-18)</p>
                                <div class="progress g-turquoise-levels-17-18">
                                    <div class="progress-bar g-turquoise-levels-17-18" role="progressbar"
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                        style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Purple (Levels 19-20)</p>
                                <div class="progress h-purple-levels-19-20">
                                    <div class="progress-bar h-purple-levels-19-20" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Gold (Levels 21-22)</p>
                                <div class="progress i-gold-levels-21-22">
                                    <div class="progress-bar i-gold-levels-21-22" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Silver (Levels 23-24)</p>
                                <div class="progress j-silver-levels-23-24">
                                    <div class="progress-bar j-silver-levels-23-24" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Emerald (Levels 25-26)</p>
                                <div class="progress k-emerald-levels-25-26">
                                    <div class="progress-bar k-emerald-levels-25-26" role="progressbar"
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                        style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Ruby (Levels 27-28)</p>
                                <div class="progress l-ruby-levels-27-28">
                                    <div class="progress-bar l-ruby-levels-27-28" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Sapphire (Levels 29-30)</p>
                                <div class="progress m-sapphire-levels-29-30">
                                    <div class="progress-bar m-sapphire-levels-29-30" role="progressbar"
                                        aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"
                                        style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Bronze (Levels-31+)</p>
                                <div class="progress n-bronze-levels-31">
                                    <div class="progress-bar n-bronze-levels-31" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                            <div class="level-wrap">
                                <p>Black (Levels-31++)</p>
                                <div class="progress o-black-levels-31">
                                    <div class="progress-bar o-black-levels-31" role="progressbar" aria-valuenow="0"
                                        aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:0%">0%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Not To be Included in V1.0 -->
    <!-- 	<div class="container statistic-section-wrap page-middle" id="student_details">
		<div class="row">
			<p> Insert Badge Display Here! </p>
		</div>
	</div> -->

    <!-- Pie Chart Section -->
    <div class="container statistic-section-wrap page-bottom-left" id="pie-chart-section">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                <!-- Pie Section 1 -->
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                        <div class="pie-chart-section pie-section pie-chart-1">
                            <div class="pie-chart chart" id="section-1-chart"></div>
                            <!-- <div class="pie-chart-text inside">
				    	<p><span id="chart-1-text-1">0</span><span>%</span></p>
				  	</div>
				  	<div class="pie-chart-text legend">
				    	<p><span class="legend colour" id="colour-1"></span><span class="legend key" id="key-1">New Books</span></p>
				    	<p><span class="legend colour" id="colour-2"></span><span class="legend key" id="key-2">Re-Read Books</span></p>
				  	</div> -->
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="pie-chart-section text-section pie-chart-1 btn-view-responses-wrapper"
                            id="section-1-block">
                            <div class="text-section-content">
                                <p class="book-read-count">Books read in total: <span>0</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="pie-chart-section">
                            <p>Quizzes Completed</p>
                            <div class="quiz-result-total"></div>
                        </div>
                    </div>
                </div>

                <!-- Pie Section 2 -->
                <div class="row">
                    <div class="col-xs-12 col-md-4">
                        <div class="pie-chart-section pie-section pie-chart-2">
                            <div class="pie-chart chart" id="section-2-chart"></div>
                            <!-- <div class="pie-chart-text inside">
					    	<p><span id="chart-2-text-1">0</span><span>%</span></p>
					  	</div>
						<div class="pie-chart-text legend">
							<p><span class="legend colour" id="colour-3"></span><span class="legend key" id="key-3">Fiction</span></p>
						    <p><span class="legend colour" id="colour-4"></span><span class="legend key" id="key-4">Non-Fiction</span></p>
						</div> -->
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="pie-chart-section text-section pie-chart-2" id="section-2-block">
                            <div class="text-section-content">
                                <h3 class="read-with-narration-heading">Read With Narration</h3>
                                <p class="narrated strong"><span>0</span>%</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <div class="pie-chart-section">
                            <p>Quiz Average</p>
                            <div class="quiz-result-average"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="col-xs-12 col-md-4">

		<!-- eBook Rating Section -->
            <!-- <input type="button" class="student-btn top-section btn-student-badges btn btn-default btn-xl btn-block disabled" title="View all the badges this student has unlocked" id="student-badges" value="Badges" />
		<input type="button" class="student-btn top-section btn-student-stories btn btn-default btn-xl btn-block disabled" title="This feature is still in development and will be available soon" id="student-stories" value="Stories" />
		<form method="post" action="<?php // echo home_url().'/quiz-results/'; ?>">
			<input type="hidden" name="quiz_user" id="quiz_user" value="" />
			<input type="submit" class="student-btn top-section btn-student-results btn btn-default btn-xl btn-block" title="View student's quiz results" id="student-results" value="Quiz Results" />
		</form>
	</div>-->
            <!-- End Row -->
        </div>
        <!-- End Container -->
    </div>

    <!-- Graph Section -->
    <div class="container statistic-section-wrap page-middle" id="student_graphs">
        <div class="row">
            <div class="col-xs-12">
                <h3>Books Read</h3>
                <label class="line-graph y-axis">Number of Books</label>
                <label class="line-graph x-axis">Last 7 Days</label>
                <label class="bar-graph x-axis"> Last 4 Weeks</label>
                <div id="student-line-graph"></div>
                <div id="student-bar-graph"></div>
            </div>
        </div>
    </div>
    <!-- /div -->
</div>
<!-- END Student Statistics HTML -->

<!-- Popup Window For Badges/Stories/QuizWritten -->
<div class="page-section popup-window" id="popup-window">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-8 col-sm-offset-2">
                <div class="popup-window-wrapper">
                    <h2 id="window-heading" class="title-heading"></h2>
                    <div class="popup-window content-wrap" id="badge-content">
                        <?php echo get_teacher_badges(); ?>
                    </div>
                    <div class="popup-window content-wrap" id="stories-content"></div>
                    <div class="popup-window content-wrap" id="quiz-content"></div>
                    <div class="popup-window content-wrap" id="graph-content"></div>
                    <div class="popup-window close-wrap">
                        <input type="button" class="popup-window close-btn btn btn-default" value="Close" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
/* ----- End Of single-student-detail.php file ----- */