<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//exits when file is load directly 
if ( !function_exists( 'add_action' ) ) {
	echo "This page cannot be called directly.";
	exit;
}

/** ============================================================================
 * 
 * 							My Statistics Page
 * The Following Statistics will be displayed Here:
 * - Most Flagged Resources 	(Top 10)
 * - Most Collected Resources 	(Top 10)
 * - Largest Collections 		(Top 10)
 * - Most Followed Collections 	(Top 10)
 * - Most Saved Collections 	(Top 10)
 * - User with most Followers 	(Top 10)
 * - User with most Flags 		(Top 10)
 * - User with most Collections (Top 10)
 * - Top User Overall 			(Top 10)
 * ----------- Additional Stats For Lessonzone ---------------
 * - Most User Logins   		(Top 10)
 * - Most Printed Printables    (Top 10)
 * - Most Downloaded Printables (Top 10)
 * - Most Read eBooks			(Top 10)
 * - Highest Rated Resource		(Top 10)
 * 
 * ============================================================================ **/
//Declare Global Variabels
global $wpdb;

//Set Admin Nonce for querying statistics
$ms_chk_nce = my_statistics_verify_start();
if( ! $ms_chk_nce ) {
	error_log('user not verified. End loading.');
	die();
}

// ----------------------- Statistics HTML - BEGIN ---------------------------- \\
/* 
<script type="text/javascript">
jQuery(window).on('load', function(){ var $ = jQuery;
 $('.statistics-body-wrap').masonry('reloadItems');
   $('.statistics-body-wrap').masonry({
    	itemSelector: '.statistics-item-wrap',
        isFitWidth: true,
        columnWidth: 260
    }).css('visibility', 'visible');    
});
</script>
 */
?>
<h1>LesssonZone Statistics</h1>
<p>
	<em>This Section displays assorted statistics and information about the <br />current usage of LessonZone and its components</em>
</p>

<?php $stat_menu = my_statistics_menu_html(); ?>
<?php echo $stat_menu['html']; ?>

<div class="statistics-body-wrap">

<?php 
/* ----------------------------------------------------------------------------
 * 							Declare Stat Functions
* Each Array entry should have the following:
*  - slug string
*  - title string
*
* ---------------------------------------------------------------------------- */
$my_statistics_array = my_statistics_declare_stats($ms_chk_nce, $stat_menu['current']);

//Check Contents - If no data fields available, prompt user
if ( ! $my_statistics_array || ! count($my_statistics_array) > 0) {
	//error_log('my statistics: (stats array) - no data fields.');
	echo "<label>There are Currently no Statistics available.</label>";
} else {
	
	//error_log('my statistics: (stats array) - Data Fields found: Running Display Loop.');
	foreach ( $my_statistics_array as $stat_array ) {
		//For Ease of Use: Declare array parameters
		
		if ( isset( $stat_menu['current'] ) ) {
			if ( is_array( $stat_array['menu']) ) {
				if ( ! in_array( $stat_menu['current'], $stat_array['menu'] ) ) {
					continue;
				}
			} else {
				if ( $stat_menu['current'] !== $stat_array['menu'] ) {
					continue;
				}	
			}
		}
		
		$stat_slug 		= $stat_array['slug'];
		$stat_title 	= $stat_array['title'];
		$stat_query		= $stat_array['query'];
		$stat_results 	= isset($stat_array['results']) ? $stat_array['results'] : null;
		$stat_columns 	= $stat_array['column'];
		$col_count		= count($stat_columns);
		
		// ---------- BEGIN STATISTIC TABLE PRINTOUT ------------ \\
		$stat_html  = "<div class='statistics-item-wrap item-$stat_slug' >";
			$stat_html .= "<div class='statistics-item item-header' >";
				$stat_html .= "<h2 class='statistics-item-heading'>$stat_title</h2>";
			$stat_html .= "</div>";	
			$stat_html .= "<div class='statistics-item item-$stat_slug'>";
				$stat_html .= "<table class='statistics-results-table item-$stat_slug'><tbody>";
					$stat_html .= "<tr><th>#</th>";
					// ----- BEGIN ROW HEADINGS PRINTOUT ----- \\		
					foreach ( $stat_columns as $no => $name ) {
						$stat_html .= "<td><strong>{$name}</strong></td>";
					}
					// ------ END ROW HEADINGS PRINTOUT ------ \\
					$stat_html .= "</tr>";
					// ----- BEGIN RESULT ROW PRINTOUT ----- \\
					if ( isset( $stat_array['results'] ) ) {
						foreach ( $stat_array['results'] as $res_no => $result ) {
							$res_no++;		
							$stat_html .= "<tr>";
								$stat_html .= "<td>$res_no</td>";
								foreach ( $stat_columns as $no => $name ) {
									$stat_html .= "<td>{$result->$no}</td>";
								}
							$stat_html .= "</tr>";
						}
					} else {
						$stat_html .= '<tr><td colspan=3 >No Results Found.</td></tr>';
					}
						
					// ----- END RESULT ROW PRINTOUT ----- \\
				$stat_html .= "</tbody></table>";
			$stat_html .= "</div>";
		$stat_html .= "</div>";
		// ------------ END STATISTIC TABLE PRINTOUT ------------ \\
		echo $stat_html;
		unset($stat_html);
	}	
}


?>
</div><!-- END statistics-body-wrap -->
<?php /* EOF */ ?>