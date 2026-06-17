<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Echo Content to Page Template
function get_teacher_dashboard() {
	//BuildPage
	$html_content = build_content_html();
	echo $html_content;
}

function build_content_html() {

	$functions_html = teacher_functions_html();

	//Body Wrap
	$content_html = '<div class="container-fluid">';
	// header
        $content_html .= '<div class="row mt15"> <div class="col-xs-12"> <h2 class="glyphicon-heading text-left"> <span class="x2 glyphicon glyphicon-dashboard hidden-xs"></span> <span class="glyphicon-heading-text">Wushka Program Coordinator Dashboard</span> </h2> </div> </div>';
		$content_html .= "<section class='page-section teacher-functions pt15 grad-radial' id='section-functions'><div class='container'><div class='row'>$functions_html</div></div></section>";
	$content_html .= "</div>";

	return $content_html;
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
		$item_html  = '<div class="teacher-functions-box col-xs-12 col-sm-4" id="'.$aFunc['id'].'">';
		$item_html .= '<a href="'.$aFunc['url'].'" class="teacher-functions"><div class="teacher-function wrapper grow">';
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

function generate_teacher_functions() {

	$teacher_functions[] = [
		'id' => 'school-settings',
		'title' => '1. Program Coordinator Settings',
		'url' => home_url().'/school-settings/',
		'points' => [
			'Edit Wushka Program Coordinator details',
			'Add other School contacts'
		]
	];

	$teacher_functions[] = [
		'id' => 'school-teachers',
		'title' => '2. Teacher Users',
		'url' => home_url().'/school-teachers/',
		'points' => [
			'Create Teacher Users',
			'Edit Teacher Users',
			'Remove Teacher Users'
		]
	];

	$teacher_functions[] = [
		'id' => 'school-classes',
		'title' => '3. Classes',
		'url' => home_url().'/school-classes/',
		'points' => [
			'Add, Edit and Remove Classes',
			'Drag and Drop Years',
			'Drag and Drop Teacher Users'
		]
	];

	$teacher_functions[] = [
		'id' => 'school-students',
		'title' => '4. Students',
		'url' => home_url().'/school-students/',
		'points' => [
			'Quickly and easily add Students to Classes',
			'Replace whole Class lists'
		]
	];

	$teacher_functions[] = [
		'id' => 'overview-dashboard',
		'title' => 'Overview Dashboard',
		'url' => home_url().'/school-dashboard-overview/',
		'points' => [
			'Graphical Overview of Classes',
			'Graphical Overview of Students'
		]
	];

	$teacher_functions[] = [
		'id' => 'notifictions',
		'title' => 'Notifications',
		'url' => home_url().'/school-notifications/',
		'points' => [
			'Notifications'
		]
	];

	$teacher_functions[] = [
		'id' => 'helpful-resources',
		'title' => 'Helpful Resources',
		'url' => home_url().'/brochure/',
		'points' => [
			'Print or copy helpful resources'
		]
	];

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
	// 					'Refer your friends to Wushka'
	// 	]
	// ];

	return $teacher_functions;
}
