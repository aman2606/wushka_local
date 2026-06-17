<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
//exits when file is load directly 
if ( !function_exists( 'add_action' ) ) {
	echo "This page cannot be called directly.";
	exit;
}
global $wpdb;
/* -----------------------------------------------------------------------------------------
 * 
 * 									Declare Data Items 
 * 							one item for each table of statistics
 * 
 * 	Here is a description of the available arguments
 *  for a single data field item:
 *  	- slug (string) - unique name for data field (required)
 *  	- title (string) - Front-end title on stat tables
 *   	- query (wpdb class prepared array) - prepared query for table stored here.
 *      - column (array) - multidimensional array that lists the titles of each column
 *      -  				   in the HTML display of the results.
 *   	
 *   
 *   - NOTE: display process requires columns have a fixed naming structure, which will affect.
 *   - the order they are displayed in the generated results table.
 *   - 
 *   - example of query : "SELECT ID as column_2 , post_title as column_1 FROM wp_posts"  
 *		( post_title will be displayed in first column of html table, ID in second.)
 * 	 - example of column array:
 *   - $item_column = array(
 *   					'column_1' => 'POST TITLE',
 *   					'column_2' => 'POST ID',
 *   				  );
 *   			
 * ---------------------------------------------------------------------------------------- */
//$myarea_names = if ( ! empty( myArea_collect_names() ) ;


$data_item[] = array(
	'slug' 		=> 'flags_most',
	'title' 	=> "Most Flagged Resource",
	'menu' 		=> array("zone", 'post'), 
	'query' => $wpdb->prepare(
 		"SELECT p.ID as column_1, p.post_title as column_3, count(p.ID) as column_4, ".
		"( SELECT meta_value FROM wp_postmeta WHERE meta_key = %s AND post_id = p.ID ) as column_2 ".
		"FROM ".$wpdb->prefix."posts p ".
 		"LEFT JOIN ".$wpdb->prefix."my_area_flags f ON p.ID = f.post_id ".
 		"WHERE p.post_status = %s AND post_type IN ( %s, %s ) ".
 		"GROUP BY p.ID ORDER BY count(p.ID) DESC LIMIT %d ",
 		'esiss_resource_id', 'publish', 'post', 'ebook', 20 
 	),
	'column' => array(
		'column_1' => 'ID', 
		'column_2' => 'ResID', 
		'column_3' =>'Post Title', 
		'column_4' =>'Flags' ,
	), 
);

$data_item[] = array(
		'slug' 	=> 'group_res_count',
		'title' => "Most Collected Resource",
		'menu' 		=> array("zone", 'post'),
		'query' => $wpdb->prepare(
				"SELECT gm.post_id as column_1, gm.meta_title as column_3, count(gm.post_id) as column_4, ".
				"( SELECT pm.meta_value FROM ".$wpdb->prefix."postmeta pm WHERE pm.meta_key = %s AND pm.post_id = gm.post_id) as column_2 ".
				"FROM ".$wpdb->prefix."my_area_group_meta gm ".
				"WHERE gm.meta_type = %s GROUP BY gm.post_id ORDER BY count(gm.post_id) DESC LIMIT %d ",
				'esiss_resource_id', 'post', 20
		),
		'column' => array(
				'column_1' => 'ID',
				'column_2' => 'Res ID',
				'column_3' => 'Resource',
				'column_4' => 'Count',
		),
);

$data_item[] = array( 
	'slug' 		=> 'groups_large',
	'title' 	=> "Largest Collection",
	'menu' 		=> "zone",
	'query' 	=> $wpdb->prepare(
 		"SELECT g.ID as column_1, g.group_name as column_2, g.meta_count as column_3, g.user_id as column_4, u.user_email as column_5 ".
		"FROM ".$wpdb->prefix."my_area_group g JOIN ".$wpdb->prefix."users u ON g.user_id = u.ID ORDER BY g.meta_count DESC LIMIT %d ",
 		 20
 	), 
	'column' 	=> array(
		'column_1' => 'ID', 
		'column_2' => "Collection", 
		'column_3' => 'No. Posts', 
		'column_4' => 'User',
		'column_5' => 'Email'
	), 
);

$data_item[] = array(
		'slug' 	=> 'group_followers',
		'title' => "Most Followed Collection",
		'menu' 		=> "zone",
		'query' 	=> $wpdb->prepare(
				"SELECT g.ID as column_1, g.group_name as column_2, g.follow_count as column_3, g.user_id as column_4, ".
				"u.user_email as column_5, ".
				"FROM ".$wpdb->prefix."my_area_group g JOIN ".$wpdb->prefix."users u ON g.user_id = u.ID ORDER BY g.follow_count DESC LIMIT %d ",
				20
		),
		'column' => array(
				'column_1' => 'ID',
				'column_2' => "Collection",
				'column_3' => 'No. Followers',
				'column_4' => 'User',
				'column_5' => 'Email'
		),
);

$data_item[] = array( 
	'slug' => 'user_flags',
	'title' => "User with Most Flags",
	'menu' 		=>  array("zone", 'user'),
	'query' => $wpdb->prepare(
 		"SELECT u.ID as column_1, u.display_name as column_2, u.user_email as column_3, count(f.user_id) as column_4 ".
		"FROM ".$wpdb->prefix."users u ". 
		"JOIN ".$wpdb->prefix."my_area_flags f ON u.ID = f.user_id ".
		"GROUP BY u.ID ORDER BY count(f.user_id) DESC LIMIT %d ", 
 		20
 	), 
	'column'  => array( 
		'column_1' => 'User', 
		'column_2' => 'Display Name', 
		'column_3' => 'email address',
		'column_4' => "No. Flags",
	),
);

$data_item[] = array( 
	'slug' => 'user_groups', 
	'title' => "User with Most Collections", 
	'menu' 		=>  array("zone", 'user'),
	'query' => $wpdb->prepare(
 		"SELECT u.ID as column_1, u.display_name as column_2, u.user_email as column_3, count(g.user_id) as column_4 ".
		"FROM ".$wpdb->prefix."users u ".
 		"JOIN ".$wpdb->prefix."my_area_group g ON u.ID = g.user_id ".
 		"GROUP BY u.ID ORDER BY count(g.user_id) DESC LIMIT %d",
 		20
 	), 
	'column' => array( 
		'column_1' => 'User', 
		'column_2' => 'Display Name', 
		'column_3' => 'Email',
		'column_4' => "No. Collections",
	),	 
);

$data_item[] = array(
		'slug' 	=> 'user_followers',
		'title' => "User with Most Followers",
		'menu' 		=>  array("zone", 'user'),
		'query' 	=> $wpdb->prepare(
			"SELECT gf.id as column_1, gf.author_id column_2, u.display_name as column_3, u.user_email as column_4, ". 
			"count(gf.follower_id) as column_5 FROM ".$wpdb->prefix."my_area_group_followers gf JOIN ".$wpdb->prefix."users u ON gf.author_id = u.ID ".
			"GROUP BY gf.author_id ORDER BY count(gf.follower_id) DESC LIMIT %d", 
			20
		),
		'column' => array(
				'column_1' => 'ID',
				'column_2' => 'User',
				'column_3' => 'User Name',
				'column_4' => 'Email',
				'column_5' => 'No. Followers',
		),
);

$data_item[] = array(
		'slug' 	=> 'download_res',
		'title' => "Most Downloaded Resources <br/> ( Printables, Language Variants & Support Materials )",
		'menu' 		=> "post",
		'query' => $wpdb->prepare(
				"SELECT (CASE attachment_id WHEN %d THEN post_id else attachment_id END) as column_1, ".
				"resource_id as column_2, ".
				"post_type as column_3, ".
				"(SELECT post_title FROM ".$wpdb->prefix."posts WHERE ID = (CASE attachment_id WHEN %d THEN post_id else attachment_id END)) as column_4, ".
				"(SELECT count(event_type) FROM ".$wpdb->prefix."my_statistics_lessonzone WHERE event_type = %s AND (CASE attachment_id WHEN %d THEN post_id else attachment_id END) = column_1 ) as column_5, ".
				"(SELECT count(event_type) FROM ".$wpdb->prefix."my_statistics_lessonzone WHERE event_type = %s AND (CASE attachment_id WHEN %d THEN post_id else attachment_id END) = column_1 ) as column_6, ".
				"(count((CASE attachment_id WHEN %d THEN post_id else attachment_id END))) as column_7 ".
				"FROM ".$wpdb->prefix."my_statistics_lessonzone WHERE post_type != %s GROUP BY column_1 ORDER BY column_7 DESC LIMIT %d",
				0, 0, 'download', 0, 'print', 0, 0, 'ebook', 20
		),
		'column' => array(
				//'column_1' => 'ID',
				'column_2' => 'Resource ID',
				'column_3' => 'Resource Type',
				'column_4' => 'Resource Title',
				'column_5' => '# Downloads',
				'column_6' => '# Prints',
				'column_7' => 'Total',	
		),
);

$data_item[] = array(
		'slug' 	=> 'played_book',
		'title' => "Most Played eBooks",
		'menu' 		=> "ebook",
		'query' => $wpdb->prepare(
				"SELECT post_id as column_1, ".
				"resource_id as column_2, ". 
				"(SELECT post_title FROM ".$wpdb->prefix."posts WHERE ID = post_id ) as column_3, ".
				"count(post_id) as column_4 ".
				"FROM ".$wpdb->prefix."my_statistics_lessonzone WHERE post_type = %s GROUP BY column_1 ORDER BY column_4 DESC LIMIT %d ",
				'ebook', 20
		),
		'column' => array(
				'column_1' => 'ID',
				'column_2' => 'Resource ID',
				'column_3' => 'Resource Title',
				'column_4' => 'Play Count',
		),
);

$data_item[] = array(
		'slug' 	=> 'user_both',
		'title' => "Top User <br/> (All Resources)",
		'menu' 		=> array('user', 'post'),
		'query' => $wpdb->prepare(
			"SELECT user_id as column_1, ".
			"u.display_name as column_2, u.user_email as column_3, ".
			"( count(*) ) as column_4 ".
			"FROM wp_my_statistics_lessonzone JOIN ".$wpdb->prefix."users u ON user_id = u.ID GROUP BY user_id ORDER BY column_4 DESC LIMIT %d",
			20
		),
		'column' => array(
				'column_1' => 'User ID',
				'column_2' => 'Display Name',
				'column_3' => 'Email',
				'column_4' => 'Downloads/Plays',
		),
);
$data_item[] = array(
		'slug' 	=> 'user_print',
		'title' => "Top User <br/> (Printables)",
		'menu' 		=> array('user', 'post'),
		'query' => $wpdb->prepare(
			"SELECT user_id as column_1, ".
			"u.display_name as column_2, u.user_email as column_3, ".
			"( count(*) ) as column_4 ".
			"FROM wp_my_statistics_lessonzone JOIN ".$wpdb->prefix."users u ON user_id = u.ID WHERE post_type = %s GROUP BY user_id ORDER BY column_4 DESC LIMIT %d",
			'post', 20
		),
		'column' => array(
				'column_1' => 'User ID',
				'column_2' => 'Display Name',
				'column_3' => 'Email',
				'column_4' => 'Downloads/Plays',
		),
);
$data_item[] = array(
		'slug' 	=> 'user_books',
		'title' => "Top User <br/> (eBooks)",
		'menu' 		=> array('user', 'ebook'),
		'query' => $wpdb->prepare(
			"SELECT user_id as column_1, ".
			"u.display_name as column_2, u.user_email as column_3, ".
			"( count(*) ) as column_4 ".
			"FROM wp_my_statistics_lessonzone JOIN ".$wpdb->prefix."users u ON user_id = u.ID WHERE post_type = %s GROUP BY user_id ORDER BY column_4 DESC LIMIT %d",
			'ebook', 20
		),
		'column' => array(
				'column_1' => 'User ID',
				'column_2' => 'Display Name',
				'column_3' => 'Email',
				'column_4' => 'Downloads/Plays',
		),
);

$data_item[] = array(
		'slug' 	=> 'user_print',
		'title' => "Top User <br/> (Printable Variants)",
		'menu' 		=> array('user', 'post'),
		'query' => $wpdb->prepare(
			"SELECT user_id as column_1, ".
			"u.display_name as column_2, u.user_email as column_3, ".
			"( count(*) ) as column_4 ".
			"FROM wp_my_statistics_lessonzone JOIN ".$wpdb->prefix."users u ON user_id = u.ID WHERE post_type = %s GROUP BY user_id ORDER BY column_4 DESC LIMIT %d",
			'variant', 20
		),
		'column' => array(
				'column_1' => 'User ID',
				'column_2' => 'Display Name',
				'column_3' => 'Email',
				'column_4' => 'Downloads/Plays',
		),
);
$data_item[] = array(
		'slug' 	=> 'user_books',
		'title' => "Top User <br/> (eBook Support Materials)",
		'menu' 		=> array('user', 'post', 'ebook'),
		'query' => $wpdb->prepare(
			"SELECT user_id as column_1, ".
			"u.display_name as column_2, u.user_email as column_3, ".
			"( count(*) ) as column_4 ".
			"FROM wp_my_statistics_lessonzone JOIN ".$wpdb->prefix."users u ON user_id = u.ID WHERE post_type = %s GROUP BY user_id ORDER BY column_4 DESC LIMIT %d",
			'support', 20
		),
		'column' => array(
				'column_1' => 'User ID',
				'column_2' => 'Display Name',
				'column_3' => 'Email',
				'column_4' => 'Downloads/Plays',
		),
);

 /* EOF */
 ?>