<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Echo Content to Page Template
function get_teacher_dashboard() {
	//BuildPage
	$html_content = build_content_html();
	echo $html_content;
}

function build_content_html() {

	//Get Body Components
//	$static_banner_html  	= static_banner_html();
//	$banner_html  	= banner_html();
//	$button_html  	= student_select_html();
	$functions_html = teacher_functions_html();
//	$carousel_html  = get_carousel_html();

	//Body Wrap
	$content_html = '<div class="container-fluid">';
	// header
	$content_html .= '<div class="row mt15"> <div class="col-xs-12"> <h2 class="glyphicon-heading text-left"> <span class="x2 glyphicon glyphicon-dashboard hidden-xs"></span> <span class="glyphicon-heading-text">Teacher Dashboard</span> </h2> </div> </div>';
	//Add Static Banner
	// $content_html .= "<section class='page-section static_banner-section' id='section-static_banner'><div class='container'><div class='row'>$static_banner_html</div></div></section>";

	//Add Banner
	//$content_html .= "<section class='page-section banner' id='section-banner'><div class='container'><div class='row'>$banner_html</div></div></section>";

	// Add Carousel
	// $content_html .= $carousel_html;

	// Student Select
	// $content_html .= "<section class='page-section padding-y-sm select-button' id='select-button'><div class='container'><div class='row'><div class='col-xs-12 col-sm-6 col-sm-offset-3'>$button_html</div></div></div></section>";

	// Features
	$content_html .= "<section class='page-section teacher-functions pt15 grad-radial' id='section-functions'><div class='container'><div class='row'>$functions_html</div></div></section>";
	$content_html .= "</div>";

	return $content_html;
}

// Add Static Banner Html to page
function static_banner_html() {

	//Add Banner Wrap
	$static_banner_html = '<div class="static_banner_wrap dashboard padding-y" id="static_banner">';
	//Banner List
	$static_banner_html .= '<div class="static_banner">Banner</div>';
	$static_banner_html .= '<div class="clearfix"></div>';

	return $static_banner_html;
}

// Add Banner Html to page
function banner_html() {
	//Pull Data To add to Banner
	//TODO: Build Function for collecting banner items
	$banner_items = generate_banner_items();
	//Build Html for Banner Items
	$list_items = build_items_html($banner_items);

	//Add Banner Wrap
	$banner_html = '<div class="banner_wrap dashboard padding-y" id="banner">';
	//Banner List
	$banner_html .= '<div class="banners">';
	//Any Items? If so, add to wrap html
	if ( $list_items !== FALSE && count($list_items) > 0 ) {
		$banner_html .=  implode($list_items);
	}
	$banner_html .= '</div>';
	//Next / Previous Buttons
	$banner_html .= '<input type="button" class="banner pagination previous" title="Previous School Reader" value="<" />';
	$banner_html .= '<input type="button" class="banner pagination next" title="Next School Reader" value=">" />';
	$banner_html .= '<div class="clearfix"></div></div>';

	return $banner_html;
}

// Add Page Wide Studet Select Button.
function student_select_html() {
	//TODO: Determine User is on a free trial or not
	$free_trail = FALSE;

	//Is on free trail?
	$button_text = ( $free_trail == TRUE ) ? 'Subscribe Now' : 'Select Student';
	$button_link = ( $free_trail == TRUE ) ? '#' : '#';

	//Return Button HTML
	return "<a href='$button_link'><input type='button' class='btn btn-default btn-block btn-lg' id='student_select' value='$button_text' />";
}

function teacher_functions_html() {

	$teacher_functions = generate_teacher_functions();
	$teacher_functions_html = build_teacher_functions_html($teacher_functions);


	$functions_html = '<div class="teacher-functions">';
	//Add Teacher Function Elements
	if ( $teacher_functions_html !== FALSE && count($teacher_functions_html) > 0 ) {
		$functions_html .= implode($teacher_functions_html);
	}

	$functions_html .= '</div>';

	return $functions_html;
}

function generate_banner_items() {
	return [
		['id' => 1, 'title' => 'Banner 1', 'url' => '#' ],
		['id' => 2, 'title' => 'Banner 2', 'url' => '#' ],
		['id' => 3, 'title' => 'Banner 3', 'url' => '#' ],
		['id' => 4, 'title' => 'Banner 4', 'url' => '#' ],
	];
}

function build_items_html( $banner_items = [] ) {
	if ( ! $banner_items || count($banner_items) < 1 ) {
		error_log("Banner Items Failed to Build");
		return FALSE;
	}

	$list_items = null;
	//Single Item Html
	foreach( $banner_items as $banner_item ) {
		$item_html  = '<div class="cols-xs-6 col-sm-3"><div class="banner">';
		$item_html .= '<a href="'.$banner_item['url'].'" class="banner item">';
		$item_html .= '<p class="list-item label">'.$banner_item['title'].'</p>';
		$item_html .= '</a>';
		$item_html .= '</div></div>';

		//Add Item to items array
		$list_items[] = $item_html;
	}

	return $list_items;
}

function build_teacher_functions_html ( $aFunctions = [] ) {
	if ( ! $aFunctions || count($aFunctions) < 1 ) {
		error_log("No Teacher Functions to Display");
		return FALSE;
	}
	$aFunctions_html = null;

	//Build the HTML elements for each Teacher Functions
	foreach( $aFunctions as $aFunc ) {
		//URL Link
		$item_html  = '<div class="teacher-functions-box col-xs-12 col-sm-6 col-md-4" id="'.$aFunc['id'].'">';
		$item_html .= '<a href="'.$aFunc['url'].'" class="teacher-functions '.$aFunc['class'].'"><div class="teacher-function wrapper grow">';
		//Heading
		$item_html .= '<div class="teacher-function heading-wrapper"><span class="glyphicon teacher-function icon"></span><h2 class="teacher-function heading">'.$aFunc['title'].'</h2></div>';
		//Dot Points
		$item_html .= '<div class="teacher-function content"><p class="teacher-function sub-heading">Here you can:</p><ul class="teacher-function text-items">';
		foreach( $aFunc['points'] as $point ) {
			$item_html .= '<li class="teacher-function text-item"><span class="glyphicon glyphicon-play bullet-arrow"></span>'.$point.'</li>';
		}
		$item_html .= '</ul></div>';
		$item_html .= '</div></a></div>';

		//Add Item to items array
		$aFunctions_html[] = $item_html;
	}

	return $aFunctions_html;
}

function get_carousel_html() {

	$a_carousel_inner = get_carousel_inner_html();

	$a_carousel_html[] = '<section class="page-section carousels padding-y" id="section-carousels">';
	$a_carousel_html[] = '<div class="container">';
	$a_carousel_html[] = '<div class="row">';
	$a_carousel_html[] = '<div class="col-xs-12 carousel-type-selection">';
	$a_carousel_html[] = '<div class="col-xs-12 carousel-type-selection padding-y-sm">';
	$a_carousel_html[] = '<a href="#" class="btn btn-default">My Latest School Readers</a>';
	$a_carousel_html[] = '<a href="#" class="btn btn-primary">Popular School Readers</a>';
	$a_carousel_html[] = '</div>';
	$a_carousel_html[] = '</div>';
	$a_carousel_html[] = '</div><!--- END row --->';
	$a_carousel_html[] = '<div class="row">';
	$a_carousel_html[] = '<div class="col-xs-12 carousel-type-selection">';
	$a_carousel_html[] = '<div id="carousel-id" class="carousel slide" data-ride="carousel">';
	$a_carousel_html[] = '<ol class="carousel-indicators">';
	$a_carousel_html[] = $a_carousel_inner['indicators'];
	$a_carousel_html[] = '</ol>';
	$a_carousel_html[] = '<div class="carousel-inner">';
	$a_carousel_html[] = $a_carousel_inner['rows'];
	$a_carousel_html[] = '</div>';
	$a_carousel_html[] = '<a class="left carousel-control bg-frontpage" href="#carousel-id" data-slide="prev"> <span class="arrow-left-wrapper"> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" class="arrow-left" viewBox="0 0 45 73.8" enable-background="new 0 0 45 73.8" xml:space="preserve"> <g id="border_2_"> <path fill="#FFFFFF" d="M40.8 0c-1.4 0-2.9 0.7-4.4 2L4 28.1l-0.1 0.1l-0.1 0.1C1.4 30.6 0 33.7 0 37s1.3 6.4 3.8 8.7l0.1 0.1 L4 45.9L36.4 72c1.7 1.6 3.3 1.9 4.3 1.9c1.5 0 2.8-0.7 3.6-1.9c0.9-1.4 1-3.3 0.2-5.3l-7.4-19.4c-2.5-6.6-2.5-14 0-20.6l7.4-19.5 l0 0l0 0c1-2.8 0.2-4.6-0.4-5.4C43.4 0.6 42.1 0 40.8 0L40.8 0z"></path> </g> <path id="background_3_" class="background-color" fill="#FFDE15" d="M40.8 3c1.1 0 1.6 1.2 0.9 3.1l-7.4 19.5c-2.8 7.3-2.8 15.4 0 22.7l7.4 19.5 c0.7 1.8 0.2 3-1 3c-0.6 0-1.5-0.4-2.3-1.2L5.8 43.5C2 39.9 2 34 5.8 30.4L38.4 4.3C39.3 3.4 40.1 3 40.8 3"></path> <linearGradient id="shadow_1_" gradientUnits="userSpaceOnUse" x1="697.2" y1="44.8" x2="663.7" y2="87.6" gradientTransform="matrix(-1 0 0 -1 717.0637 100)"> <stop offset="0" style="stop-color:#000000;stop-opacity:0.25"></stop> <stop offset="0" style="stop-color:#000000;stop-opacity:0.5"></stop> <stop offset="3.585083e-02" style="stop-color:#000000;stop-opacity:0.4552"></stop> <stop offset="0.4" style="stop-color:#000000;stop-opacity:0"></stop> </linearGradient> <path id="shadow_3_" fill="url(#shadow_1_)" d="M40.8 3c1.1 0 1.6 1.2 0.9 3.1l-7.4 19.5c-2.8 7.3-2.8 15.4 0 22.7l7.4 19.5 c0.7 1.8 0.2 3-1 3c-0.6 0-1.5-0.4-2.3-1.2L5.8 43.5C2 39.9 2 34 5.8 30.4L38.4 4.3C39.3 3.4 40.1 3 40.8 3"></path> </svg></span> </a>';
	$a_carousel_html[] = '<a class="right carousel-control bg-frontpage" href="#carousel-id" data-slide="next"> <span class="arrow-right-wrapper"> <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" class="arrow-right" x="0px" y="0px" viewBox="0 0 45 73.8" enable-background="new 0 0 45 73.8" xml:space="preserve"><g id="border"><path fill="#FFFFFF" d="M4.3 0C2.9 0 1.7 0.6 0.9 1.7C0.3 2.5-0.5 4.2 0.5 7.1l0 0l0 0l7.4 19.5c2.5 6.6 2.5 14 0 20.6L0.5 66.6 c-0.8 1.9-0.7 3.9 0.2 5.3c0.8 1.2 2.1 1.9 3.6 1.9c1 0 2.6-0.3 4.3-1.9L41 45.8l0.1-0.1l0.1-0.1c2.4-2.3 3.8-5.4 3.8-8.7 s-1.3-6.4-3.8-8.7l-0.1-0.1L41 28L8.6 2C7.2 0.7 5.7 0 4.3 0L4.3 0z"></path></g><path id="background_2_" class="background-color" fill="#FFDE15" d="M4.3 3c0.6 0 1.5 0.4 2.4 1.3l32.5 26.1c3.8 3.6 3.8 9.5 0 13.1L6.7 69.6 c-0.9 0.8-1.7 1.2-2.3 1.2c-1.2 0-1.7-1.2-1-3l7.3-19.5c2.8-7.3 2.8-15.4 0-22.7L3.3 6.1C2.7 4.2 3.2 3 4.3 3"></path><linearGradient id="shadow_2_" gradientUnits="userSpaceOnUse" x1="25.2" y1="44.8" x2="-8.2" y2="87.5" gradientTransform="matrix(1 0 0 -1 0 100)"><stop offset="0" style="stop-color:#000000;stop-opacity:0.25"></stop><stop offset="0" style="stop-color:#000000;stop-opacity:0.5"></stop><stop offset="3.585083e-02" style="stop-color:#000000;stop-opacity:0.4552"></stop><stop offset="0.4" style="stop-color:#000000;stop-opacity:0"></stop></linearGradient><path id="shadow_1_" fill="url(#shadow_2_)" d="M4.3 3c0.6 0 1.5 0.4 2.4 1.3l32.5 26.1c3.8 3.6 3.8 9.5 0 13.1L6.7 69.6 c-0.9 0.8-1.7 1.2-2.3 1.2c-1.2 0-1.7-1.2-1-3l7.3-19.5c2.8-7.3 2.8-15.4 0-22.7L3.3 6.1C2.7 4.2 3.2 3 4.3 3"></path></svg> </span> </a>';
	$a_carousel_html[] = '<div class="teacher-dash-carousel-shelf"></div>';
	$a_carousel_html[] = '</div>';
	$a_carousel_html[] = '</div>';
	$a_carousel_html[] = '</div><!-- END row --->';
	$a_carousel_html[] = '</div><!-- END container --->';
	$a_carousel_html[] = '</section><!-- END section --->';

	return implode('', $a_carousel_html);
}
function get_carousel_inner_html() {

	$a_carousel = array('indicators' => [], 'rows' => []);

	$a_shelves = get_carousel_shelves();
	$a_indicators = array();

	foreach($a_shelves as $i_shelf => $books ) {
		$s_active = ( $i_shelf == 0 ) ? 'active' : NULL;

		$s_indicator = '<li data-target="#carousel-id" data-slide-to="'.$i_shelf.'" class="'.$s_active.'"></li>';

		$a_shelf[] = '<div class="item '.$s_active.'">';
		$a_shelf[] = '<div class="row">';
		//Add books
		$a_shelf[] = implode('', $books);
		$a_shelf[] = '</div>';
		$a_shelf[] = '</div>';


		$a_indicators[] = $s_indicator;
		$a_rows[] = implode($a_shelf);
		unset($s_indicator, $a_shelf);
	}

	$a_carousel['indicators'] = implode('', $a_indicators);
	$a_carousel['rows'] = implode('', $a_rows);

	return $a_carousel;
}


function get_carousel_shelves() {
	global $wpdb;

	$s_query  = 'SELECT p.ID, p.post_title, p.post_type, ';
	$s_query .= '( SELECT pm.meta_value FROM '.$wpdb->prefix.'postmeta pm WHERE pm.post_id = p.ID AND meta_key = %s ) as esiss_resource_id, ';
	$s_query .= '( SELECT pm.meta_value FROM '.$wpdb->prefix.'postmeta pm WHERE pm.post_id = p.ID AND meta_key = %s ) as post_image ';
	$s_query .= 'FROM '.$wpdb->prefix.'posts p WHERE p.post_type = %s AND p.post_status = %s GROUP BY p.ID LIMIT 48';

	$a_params = array(
		'esiss_resource_id',
		'post_image',
		'ebook',
		'publish'
	);
	$a_shelves = array();
	$a_results = $wpdb->get_results( $wpdb->prepare( $s_query, $a_params) );

	if ( $a_results == null || empty($a_results) ) {
		$a_shelves[] = array('<div>There was an error in The Query</div>');
		return $a_shelves;
	}

	$i_shelf_count = 0;
	$i_count = 0;

	foreach ( $a_results as $idx => $book ) {
		$s_permalink = get_permalink($book->ID);
		$a_book[] = '<div class="thumb accordion-shelf-book col-xs-4 col-sm-2">';
		$a_book[] = '<a href="'.$s_permalink.'" class="item-detail" id="book-'.$book->ID.'">';
		$a_book[] = '<img class="img-responsive img-rounded" src="'.$book->post_image.'" title="'.$book->post_title.'" style="width:200px; height:284px;">';
		$a_book[] = '</a>';
		$a_book[] = '</div>';

		$a_shelves[$i_shelf_count][] = implode('', $a_book);
		unset($a_book);
		$i_count++;
		if ( $i_count >= 6 ) {
			$i_shelf_count++;
			$i_count = 0;
		}
	}

	return $a_shelves;
}

function generate_teacher_functions() {
	$disable = '';
	if(isset($_SESSION['wushka_decodable_teacher']) && $_SESSION['wushka_decodable_teacher']){
			$disable = 'disable-dashboard-block';	
	}

	//Manage Class List
	$teacher_functions[] = [
		'id' => 'class-list',
		'title' => 'Manage Class List',
		'url' => home_url().'/manage-class/',
		'points' => [
			'Add or archive students',
			'Set password for whole class',
			'Allocate Students to Reading Groups'
		],
		'class' => $disable
	];

	//Manage Reading Groups
	$teacher_functions[] = [
		'id' => 'reading-groups',
		'title' => 'Manage Reading Groups',
		'url' => home_url().'/manage-reading-groups/',
		'points' => [
			'Allocate School Readers to groups',
			'View students within groups'
		],
		'class' => $disable
	];

	//Manage Students
	$teacher_functions[] = [
		'id' => 'manage-students',
		'title' => 'Student Login',
		'url' => home_url().'/class-login/',
		'points' => [
			'Select a student to login'
		],
		'class' => $disable
	];

	//Student Information and Statistics
	$teacher_functions[] = [
		'id' => 'class-statistics',
		'title' => 'Class Statistics',
		'url' => home_url().'/class-statistics/',
		'points' => [
			'View the average School Readers completed',
			'View class statistics'
		],
		'class' => $disable
	];

	//Student Information and Statistics
	$teacher_functions[] = [
		'id' => 'student-statistics',
		'title' => 'Student Statistics',
		'url' => home_url().'/student-statistics/',
		'points' => [
			'View the average School Readers completed',
			'View individual statistics'
		],
		'class' => $disable
	];


	//My Bookmarks
	$teacher_functions[] = [
		'id' => 'my-bookmarks',
		'title' => 'My Bookmarks',
		'url' => '/my-bookmarks/',
		'points' => [
			'View your bookmarks'
		],
		'class' => ''
	];


//Library
	$teacher_functions[] = [
		'id' => 'levelled-library',
		'title' => 'Levelled Reading Boxes',
		'url' => '/levelled/',
		'points' => [
			'View all Levelled Reading Boxes'
		],
		'class' => $disable
	];

	$teacher_functions[] = [
		'id' => 'decodable-library',
		'title' => 'Decodable Reading Boxes',
		'url' => '/decodable/',
		'points' => [
			'View all Decodable Reading Boxes'
		],
		'class' => ''
	];

	//Home User Site Licence
//	$teacher_functions[] = [
//		'id' => 'home-user-site-licence',
//		'title' => 'Wushka Site Licence',
//		'url' => home_url().'/home-user-site-licence/',
//		'points' => [
//			'Read about the Wushka Site Licence'
//		]
//	];

	$teacher_functions[] = [
		'id' => 'reader-records',
		'title' => 'Reader Records',
		'url' => home_url().'/reader-records/',
		'points' => [
			'View detailed student and class reading history'
		],
		'class' => $disable
	];

	//Helpful Resources
	$teacher_functions[] = [
		'id' => 'helpful-resources',
		'title' => 'Helpful Resources',
		'url' => home_url().'/brochure/',
		'points' => [
			'Print or copy helpful resources'
		],
		'class' => ''
	];

	
//My Badges
	/*$teacher_functions[] = [
        'id' => 'badges',
        'title' => 'Badges',
        'url' => '/badges-features/',
        'points' => [
            'View your badges'
        ]
    ];*/

        //Class Stories
  //      $teacher_functions[] = [
  //          'id' => 'class-stories',
  //          'title' => 'Class Stories',
  //          'url' => '/class-stories',
  //          'points' => [
  //              'View featured class stories',
  //              'View marked & unmarked work',
  //              'Provide feedback & comments'
  //          ]
  //      ];

  //Stories
  // $teacher_functions[] = [
	// 	'id' => 'stories',
	// 	'title' => 'Stories',
	// 	'url' => home_url().'/stories/',
	// 	'points' => [
	// 		'Tell us your Wushka Stories'
	// 	]
	// ];

	// $teacher_functions[] = [
	// 	'id' => 'tips-tricks',
	// 	'title' => 'Tips &amp; Tricks',
	// 	'url' => home_url().'/tips-tricks/',
	// 	'points' => [
	// 					'Learn helpful Tips and Tricks on how to use Wushka'
	// 	]
	// ];

	// $teacher_functions[] = [
	// 	'id' => 'referrals',
	// 	'title' => 'Referrals',
	// 	'url' => home_url().'/referrals/',
	// 	'points' => [
	// 					'Refer your friend to Wushka'
	// 	]
	// ];

	return $teacher_functions;
}
