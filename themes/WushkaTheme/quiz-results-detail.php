<?php
/*
 * Template Name: Quiz Results detail page
 */
get_header();

global $wpdb, $current_user;
$results_db_name = $wpdb->prefix . 'plugin_slickquiz_scores';
$quiz_db_name = $wpdb->prefix . 'plugin_slickquiz';
$quiz_id = filter_input(INPUT_POST, 'quiz_id');
$hash_id = filter_input(INPUT_POST, 'quiz_user');
if (!empty($hash_id)) {
	$quiz_user = get_user_by_hash($hash_id)->ID;
} else {
	$quiz_user = $current_user->ID;
}



$score = $wpdb->get_row(
	$wpdb->prepare(
		"SELECT $results_db_name.id, $results_db_name.quiz_id, answers, score, $quiz_db_name.name, $quiz_db_name.publishedJson, $results_db_name.createdDate ".
		"FROM $results_db_name LEFT JOIN $quiz_db_name on $quiz_db_name.id = $results_db_name.quiz_id ".
		"WHERE $results_db_name.createdBy = %d AND $results_db_name.id = %d", $quiz_user, $quiz_id
	)
);
$quiz = json_decode($score->publishedJson);
$answers = json_decode($score->answers);

$o_school_term = wp_get_object_terms($current_user->ID, 'school');
$i_school      = NULL;
if( isset($o_school_term) && ! empty($o_school_term) ) {
    $i_school = $o_school_term[0]->term_taxonomy_id;
}


//Get TimeZones For DateTime displaying
$s_tz = wushka_get_school_timezone($i_school);
$tz_utc = new DateTimeZone('UTC');
$tz_school = new DateTimeZone($s_tz);


?>
<div class="quiz-results-wrapper padding-y grad-radial">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="quiz-results-container">
<?php
if (!isset($score)) {
    echo 'You have not completed any quizzes yet. To complete a quiz open and read a Reader, when you have finished the book click on the cross and you will be taken to the quiz for that Reader.';
} else {
?>
                    <h1 class="quiz-results-heading">Detailed Quiz Results</h1>
                    <div class="table-responsive">
                        <h2>Summary</h2>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Title</th>
                                    <th>Score</th>
                                    <th>Question 1</th>
                                    <th>Question 2</th>
                                    <th>Question 3</th>
                                    <th>Question 4</th>
                                    <th>Question 5</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                $dt = new DateTime($score->createdDate, $tz_utc);
                                $dt->setTimeZone($tz_school);

                                $scoreRow = "";
                                $scoreRow .= '<tr>';
//                                $scoreRow .= '<td class="table-date">' . (new DateTime($score->createdDate))->format("l, dS M Y g:ia") . '</td>';
                                $scoreRow .= '<td class="table-date">' . $dt->format("l, dS M Y g:ia") . '</td>';
                                $scoreRow .= '<td class="table-title">' . $score->name . '</td>';
                                $scoreRow .= '<td class="table-score">' . $score->score . '</td>';
                                $xx = 0;
                                foreach ($answers as $answer) {
                                    $scoreRow .= '<td class="table-answer ' . $answer->valid . '">' . $answer->valid . '</td>';
                                    $xx++;
                                }
                                while ($xx < 5) {
                                    $scoreRow .= '<td class="table-answer answer-na">N/A</td>';
                                    $xx++;
                                }
                                $scoreRow .= '</tr>';
                                echo $scoreRow;
                                ?>
                            </tbody>
                        </table>
                        <h2>Details</h2>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Question</th>
                                    <th>Answers</th>
                                    <th>Mark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($quiz->questions as $key => $question) {
                                    $scoreRow = "";
                                    $scoreRow .= '<tr>';
                                    $scoreRow .= '<td>' . ($key + 1) . '</td>';
                                    $scoreRow .= '<td class="table-questions">' . $question->q . '</td>';
                                    $scoreRow .= '<td class="table-answers"><ol>';
                                    foreach ($question->a as $answer_key => $answer) {
                                        $response = $answer_key == $answers[$key]->a ? 'true' : '';
                                        $correct = $answer->correct;
                                        $option = $answer->option;
                                        if ($correct == 'checked' || $response == 'true') {
                                            $option = '<span>' . $option . '</span>';
                                        }
                                        $scoreRow .= '<li class="table-answer ' . $correct . $response . '">' . $option . '</li>';
                                    }
                                    $scoreRow .= '</ol></td>';
                                    $scoreRow .= '<td class="table-response ' . $answers[$key]->valid . '">';
                                    $scoreRow .= $answers[$key]->valid;
                                    $scoreRow .= '</td>';
                                    $scoreRow .= '</tr>';
                                    echo $scoreRow;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
<?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'dashboard_options.php';
get_footer();
/* ---- EOF ----- */