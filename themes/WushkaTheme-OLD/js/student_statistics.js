jQuery(document).ready(function($) {
	/* -------------------------------
	 * 		Student Statistics JS
	 * ------------------------------- */
	console.log = function() {}
	//Important Display Elements
	var page_section_overview 	= $('.page-section.overall-statistics');
	var page_section_details	= $('.page-section.student-statistics');
	var page_section_loading 	= $('.page-section.loading-screen');
	//Elements For Next/Previous Student Buttons
	var parent_index			= 0;
	var student_total			= page_section_overview.find('[data-id="total-student-count"]').attr('value');
	var button_student_prev		= $('.btn.student-btn.prev#prev_student');
	var button_student_next		= $('.btn.student-btn.next#next_student');

	//Student Data Variables
	var student_piechart_1 		= $('#section-1-chart');
	var student_piechart_2 		= $('#section-2-chart');
	var pie_1_percent 			= 0;
	var pie_2_percent 			= 0;
	var student_books_completed = 0;
	var student_books_fiction 	= 0;
	var student_books_narrated 	= 0;
	var student_books_unique	= 0;
	var student_books_new 		= 0;
	var student_books_reread	= 0;
	var student_books_favourite = 0;

	var student_graph_data = null;
	var current_student = {
		id : null,
		wpn : null,
		col: null,
		day: null,
		date: null,
		graph: null
	};

	//Elements for Popup Window Buttons (Badges/Stories/Quizes)
	var page_popup_window		= $('.page-section.popup-window#popup-window');
	var button_popup_badge		= $('.student-btn.top-section#student-badges');
	var button_popup_story		= $('.student-btn.top-section#student-stories');
	var button_popup_quiz		= $('.student-btn.top-section#student-results');
	//Favourite Book Thumbnail
	var student_favourite_div	= $('.statistic-section.top.right').find('a.student-most-read');

	var popup_content_badge 	= $('.popup-window.content-wrap#badge-content');
	var popup_content_story 	= $('.popup-window.content-wrap#stories-content');
	var popup_content_quiz 		= $('.popup-window.content-wrap#quiz-content');
	var popup_content_graph		= $('.popup-window.content-wrap#graph-content');

	var badge_page_buttons 		= popup_content_badge.find('.buttons-content-wrap .badge-button');
	var badge_page_numbers 		= popup_content_badge.find('.buttons-content-wrap .badge-numbers');
	var badge_paging_current 	= 1;
	var badge_paging_total 		= badge_page_numbers.find('span[id="total"]').text();
	var single_badge_items		= popup_content_badge.find('div[id^="badge-"].badge-wrap');

	var badge_display_window 	= popup_content_badge.find('.badge-display-window');

	//Page Ready: Load Current Student
	load_single_student();



	/* View Student Details Button Click Event */
	$('.view-details-btn').on('click', open_student_details);

	/* Close Error Message Window */
	$('#close-error-message-btn').on('click', close_loading_screen);

	/* Close Student Details Page */
	$('input[type="button"]#overall_students').on('click', close_details_screen);

	/* Click Event for Next/Previous Student Buttons */
	button_student_prev.on('click', function(){
		load_new_student('prev');
	});
	button_student_next.on('click', function(){
		load_new_student('next');
	});
	/* Click Event For Popup Window Buttons */
	button_popup_badge.on('click', function(){
		open_popup_window('badge');
	});
	button_popup_story.on('click', function(){
		open_popup_window('story');
	});
	button_popup_quiz.on('click', function(){
		open_popup_window('quiz');
	});
	/* Open Student Graph Window */
	$('#student-line-graph').on('click', 'input[type="button"].view-more', function(e){
		e.preventDefault();
		e.stopPropagation();

		console.log('Student Line Graph Clicked');
		var this_col = $(this).attr('id').replace('view-', '').trim();
		console.log('Find Book Details for This Day: '+this_col);
		if ( typeof this_col == 'undefined' || this_col == null ) {
			console.log('No Day Found, abort ajax function');
			return false;
		}
		var this_day = $(this).parent().find('.x-day').attr('value');
		var this_date = $(this).parent().find('.x-date').attr('value');
		current_student.col = this_col;
		current_student.day = this_day;
		current_student.date = this_date;
		current_student.graph = 'line';
		open_popup_window('graph');
	});

	/* Open Student Graph Window */
	$('#student-bar-graph').on('click', 'input[type="button"].view-more', function(e){
		e.preventDefault();
		e.stopPropagation();

		console.log('Student Bar Graph Clicked');
		var this_col = $(this).attr('id').replace('view-', '').trim();
		console.log('Find Book Details for This Week: '+this_col);
		if ( typeof this_col == 'undefined' || this_col == null ) {
			console.log('No Week Found, abort ajax function');
			return false;
		}
		var this_day = $(this).parent().find('.x-day').attr('value');
		var this_date = $(this).parent().find('.x-date').attr('value');
		current_student.col = this_col;
		current_student.day = this_day;
		current_student.date = this_date;
		current_student.graph = 'bar';
		open_popup_window('graph');
	});

	/* Click Badge to bring up display window */
	single_badge_items.on('click', function(e) {
		e.preventDefault();
		load_badge_display($(this));
	});

	/* Click Event for Next/Previous Student Badges */
	badge_page_buttons.on('click', function(){
		load_badge_pagination($(this));
	});

	//Close Popup Window
	$('input[type="button"].popup-window.close-btn').on('click', close_popup_window);
	$('input[type="button"].badge-window.close-btn').on('click', close_badge_window);

	//Close Loading Screen Element
	function close_loading_screen() {
		page_section_loading.fadeTo(200, 0).hide();
		page_section_overview.show().fadeTo(200, 1);
	}

	//Close Student Details Page
	function close_details_screen() {
		page_section_details.fadeTo(200, 0).hide();
		page_section_overview.show().fadeTo(200, 1);
	}

	/* View Student Details Click Function */
	function open_student_details() {
		var student_id = 0;

		//Step 1 - FadeOut Overview Table
		page_section_overview.fadeTo(200, 0.4);
		//Step 2 - Display Loading Element
		page_popup_window.find('#window-content').empty();
		page_section_loading.show().fadeTo(200, 1);

		//Step 3- Get parent Element
		var parent_element = $(this).parents('tr[id^="student-"]');
		//Step 4 - Get Parents Index in student table
		parent_index = parent_element.index();
		console.log('Pareant Element Index: '+ parent_index);
		//Step 5 - Get Parent Element ID containing student ID
		var element_id = parent_element.attr('id');
		//Step 6 - Remove first part of String to get ID only.
		student_id = element_id.replace('student-', '').trim();
		//Step 7 - Gather Student Security Nonce
		var student_wpn = $(this).parent().find('._student_wpn').attr('value');
		//Step 8 - Student ID collected, Prepare to Load Student Detail Window
		load_student_details(student_id, student_wpn);
	}

	/* Prepare Browser For Student Data via Ajax */
	function load_student_details(student_id, student_wpn) {
		if ( typeof student_id == 'undefined' || typeof student_wpn == 'undefined' || student_id == 0 || student_wpn == null ) {
			console.log('Load Student Details: Failed to load (missing ID).');
			display_ajax_error(0);
			return false;
		}
		//Step 8 - Gather Student Favourite Book ID
		student_books_favourite = $('#student-'+student_id).find('#books_favourite').attr('value');
		var student_favourite = 0;
		if ( typeof student_books_favourite !== 'undefined' && student_books_favourite !== null && student_books_favourite.length > 0 ) {
			student_favourite = student_books_favourite;
		}
		console.log('Student to Load: '+student_id);

		/* Loading Process:
		 * 		Step 1 - FadeOut Overview Table
		 * 		Step 2 - Display Loading Element
		 * 		Step 3 - Run Ajax Function for Gathering Student Data
		 * 		Step 4 - Inject Ajax Data into Student Window
		 * 		Step 5 - Complete Loading Process / Window Animations
		 */
		//Hide Pie Charts for new Data creation
		$('.pie-chart.chart#section-1-chart').fadeTo(200,0);
		$('.pie-chart.chart#section-2-chart').fadeTo(200,0);
		student_books_completed = 0;
		student_books_fiction 	= 0;
		student_books_narrated 	= 0;
		student_books_unique	= 0;
		student_books_new 		= 0;
		student_books_reread	= 0;
		student_books_favourite = 0;

		//Step 3 - Run Ajax Function for Gather Student Data
		$.ajax( get_student_ajax_params(student_id, student_wpn, student_favourite) );

	}

	function get_student_ajax_params(student_id, student_wpn, student_favourite) {
		if ( typeof student_id == 'undefined' || typeof student_wpn == 'undefined' || student_id == 0 || student_wpn == null ) {
			console.log('Student Ajax Params Error: Failed to load (missing ID). ');
			display_ajax_error(0);
			return false;
		}

		current_student.id = student_id;
		current_student.wpn = student_wpn;

		//Create and Return Ajax Params Object
		return {
			url : thm_tmp_fnc_pth + '/functions/ajax_student-statistics.php',
			type: 'post',
			dataType: 'json',
	        data: {
	        	'student_id' : student_id,
	        	'student_wpn': student_wpn,
	        	'student_favourite': student_favourite
	        },
	        error: function(){
	        	console.log('Ajax Failed to Return');
	    		display_ajax_error(0);
	        },
	        success: function(student_details) {
	        	console.log('Ajax Return Success');

	        	student_graph_data = student_details.graph_data;


	        	ajax_student_success(student_details);
	        },
	        complete: function(){
	        	$('#student-line-graph').empty();
	        	$('#student-bar-graph').empty();
	        	Morris.Line({
	        		element: 'student-line-graph',
				  	data: student_graph_data.line,
				  	xkey: 'x_axis',
				  	xLabels : 'Day',
				  	ykeys: ['count'],
				  	labels: ['Day', 'Books'],
				  	parseTime: false,
				  	resize: true,
				  	hoverCallback: function (index, options, content, row) {
				  		var hover_window = '<div class="graph hover-content">';
				  		hover_window += '<p class="column-heading">'+row.day+' '+row.date+'</p>';
					  	hover_window += '<p class="row-text">Readers read: '+row.count+'</p>';
					  	hover_window += '<input type="hidden" class="x-date" value="'+row.date+'" />';
					  	hover_window += '<input type="hidden" class="x-day" value="'+row.day+'" />';
					  	hover_window += '<input type="button" class="view-more hidden-sm hidden-xs" id="view-'+row.col+'" value="More" />';
					  	hover_window += '</div>';
					  	return hover_window;
				  	}
	        	});
	        	Morris.Bar({
	        		element: 'student-bar-graph',
				  	data: student_graph_data.bar,
				  	xkey: 'x_axis',
				  	xLabels : 'Week',
				  	ykeys: ['count'],
				  	labels: ['Week', 'Books'],
				  	parseTime: false,
				  	resize: true,
				  	hoverCallback: function (index, options, content, row) {
				  		var hover_window = '<div class="graph hover-content">';
					  	hover_window += '<p class="column-heading">'+row.x_axis+'</p>';
					  	hover_window += '<p class="row-text">Readers read: '+row.count+'</p>';
					  	hover_window += '<input type="hidden" class="x-date" value="'+row.date+'" />';
					  	hover_window += '<input type="hidden" class="x-day" value="'+row.day+'" />';
					  	hover_window += '<input type="button" class="view-more hidden-sm hidden-xs" id="view-'+row.col+'" value="More" />';
					  	hover_window += '</div>';
					  	return hover_window;
				  	}
	        	});
	        }
		};
	}

	function ajax_student_success(student_details) {
		if ( typeof student_details['request_error'] == 'undefined' ) {
			console.log('undefined request error no');
			display_ajax_error(0);
			return false;
		} else if ( student_details['request_error'] !== 0 ) {
			console.log('Ajax request_error: '+student_details['request_error']);
			display_ajax_error(student_details['request_error']);
			return false;
		}

		console.log('DETAILS SUCCESSFULLY RETURNED');
		reset_badge_window();
		display_student_data(student_details);
		complete_loading_cycle();

	}

	function display_ajax_error(error_no) {
		console.log('Display Error Message');
		var ajax_error_msg = get_ajax_message(error_no);
		page_section_loading.find('.loading-screen.loading-gif').fadeTo(400, 0).hide();
		page_section_loading.find('.loading-screen.messages').empty().append(ajax_error_msg);
		page_section_loading.find('.loading-screen.btn-wrap').fadeTo(400, 1);

	}

	function get_ajax_message(error_id) {
		if ( typeof error_id == 'undefined' ) {
			return 'Our Apologies, A technical error has occured, Please Contact the Administrator Immediately.<br/>';
		} else if ( error_id == 0 ) {
			return 'Our Apologies, An error occured while attempting to gather this student\'s details.<br/>'+
			'Please contact the Administrator Immediately';
		} else if ( error_id == 1 ) {
			return 'Our Apologies, The Connection to our Database has failed<br/>'+
			'Please contact the Administrator Immediately';
		} else if ( error_id == 2 ) {
			return 'Our Apologies, There appears to be an error in your student\'s details,<br/>'+
			'Please contact the Administrator Immediately';
		} else if ( error_id == 3 ) {
			return 'Our Apologies, An error occured loading your student\'s details,<br/>'+
			'Please contact the Administrator Immediately.';
		} else if ( error_id == 4 ) {
			return 'No Teacher Is Located to This Student';//location.reload();
		} else {
			return 'Our Apologies, An error has occured, Please Contact the Administrator Immediately.<br/>';
		}
	}

	//This Function Loads the returned Data into the Student Window
	function display_student_data(student_details) {
		/* --- Reading History Details --- */
		var user_data = student_details.user_data.data;
		var user_meta = student_details.user_meta;

		student_favourite_div.hide().empty();
		/*if ( student_details.favourite_thumbnail !== null ) {
			//Add Link
			student_favourite_div.append('<a class="student-most-read" title="My Favourite Book" '+
				'href="'+student_details.favourite_thumbnail['post_url']+'"></a>');

			student_favourite_div.find('a').append('<img class="student-favourite-thumbnail" '+
				'src="'+student_details.favourite_thumbnail['thumb_src']+'" title="Student\'s Favourite Book" />');

			student_favourite_div.show();
		}*/

		//Load Student ID into Quiz results value
		var quiz_btn = $('.statistic-section.top.middle').find('input[type="hidden"][id="quiz_user"]');
		quiz_btn.attr('value', student_details.hash_id);

		//Licence Key
		$('.glyphicon-heading #student_name').empty().append(user_meta.first_name+'\'s page!');
		$('.student-details.top-books').empty().append(student_details.top_books);
		//$('.reading-history#student-username').empty().append(user_data.user_login);
		//$('.reading-history#student-pw').empty().append(user_meta.show_user_pwd);

		//Load Reading Level Data
		$('.page-bottom-right').empty().append(student_details.reading_level.levels);
		//Load Book Counter
		$('#section-1-block p span').empty().append(student_details.reading_level.total_read);

		//Load Quiz Result Data
		$('.quiz-result-total').empty().append(student_details.quiz_results.total);
		$('.quiz-result-average').empty().append(student_details.quiz_results.average+'%');

		//Load Student Stat Data From Reading Analytics
		var this_student = $('tr[id="student-'+student_details.hash_id+'"]');

		student_books_completed = Number(this_student.find('td.overall_read').text().trim());
		student_books_fiction 	= Number(this_student.find('input[type="hidden"][id="books_fiction"]').attr('value'));
		student_books_narrated 	= Number(this_student.find('input[type="hidden"][id="books_narrated"]').attr('value'));
		student_books_unique	= Number(this_student.find('input[type="hidden"][id="books_unique"]').attr('value'));
		student_books_new 		= Number(this_student.find('input[type="hidden"][id="books_new"]').attr('value'));
		student_books_reread	= Number(this_student.find('input[type="hidden"][id="books_reread"]').attr('value'));

		//Load Badge Data into Badge Variable
		if ( student_details.student_badges ) {
			//student_badge_content = student_details.badges;
			load_student_badge_data(student_details.student_badges);
		}

	}

	//Run This Function when ajax returns successfully:
	//Close Loading Screen
	//Open Student Details Page
	function complete_loading_cycle() {
		page_section_loading.fadeTo(200, 0).hide();
		page_section_overview.fadeTo(200, 0).hide();
		page_section_details.show().fadeTo( 200, 1, callback_student_details );
	}

	//Load Previous Student
	function load_new_student(direction_type) {
		if ( typeof direction_type == 'undefined' || ( direction_type !== 'prev' && direction_type !== 'next') ) {
			console.log('Next/Previous Student Buttons Broken.');
			return false;
		}
		var student_id = 0;
		var new_index = parent_index;
		console.log('current Index: '+new_index);
		//Alter Parent Index for Prev/Next Value
		if ( direction_type == 'prev') {
			--new_index;

			if ( new_index < 0 ) {
				return false;
			}
		} else if (direction_type == 'next') {
			++new_index;
			if ( new_index >= student_total ) {
				return false;
			}
		}
		console.log('new Index: '+new_index);
		++new_index;
		console.log('Plus One for nth-child: '+new_index);
		//Get Parent Element with new parent index
		var parent_element = $('tr.student-table-row:nth-child('+new_index+')');

		//Step 1 - FadeOut Overview Table
		page_section_details.fadeTo(200, 0.4);
		//Step 2 - Display Loading Element
		page_section_loading.show().fadeTo(200, 1);
		//Step 3 - Get Parent Element ID containing student ID
		parent_index = parent_element.index();
		var element_id = parent_element.attr('id');
		//Step 4 - Remove first part of String to get ID only.
		student_id = element_id.replace('student-', '').trim();
		//Step 5 - Gather Student Security Nonce
		var student_wpn = parent_element.find('._student_wpn').attr('value');
		//Step 6 - Student ID collected, Prepare to Load Student Detail Window
		load_student_details(student_id, student_wpn);
	}

	/*-------------------------------------------------------
	 * 					POPUP WINDOW FUNCTIONS
	 *------------------------------------------------------- */
	function open_popup_window(window_name) {
		if ( typeof window_name == 'undefined' || ( window_name !== 'badge' && window_name !== 'story' && window_name !== 'quiz' && window_name !== 'graph') ) {
			console.log('Popup Window: No/incorrect window type defined.');
			return false;
		}

		if ( $(this).hasClass('disabled') || window_name == 'quiz' ) {
			return false;
		}

		console.log('The '+window_name+' popup window was opened');
		display_window_content(window_name);

		//Fade Student Details
		page_section_details.fadeTo(200, 0.4);
		//Show Popup Window
		page_popup_window.show().fadeTo(200, 1);
		return true;
	}

	function close_popup_window() {
		//Show Student Details
		page_section_details.fadeTo(200, 1);
		//Hide Popup Window
		page_popup_window.fadeTo(200, 0).hide();
		return true;
	}

	function close_badge_window() {
		//Hide Popup Window
		badge_display_window.fadeTo(200, 0).hide();
		return true;
	}

	function display_window_content(window_name) {
		popup_content_badge.hide();
		popup_content_story.hide();
		popup_content_quiz.hide();
		popup_content_graph.hide();
		console.log('Display Window Content For: '+window_name);
		if ( window_name == 'badge') {
			console.log('Open Badge Content');
			page_popup_window.find('h2[id="window-heading"]').empty().append('Student Badges');
			popup_content_badge.show();
			popup_content_story.hide();
			popup_content_quiz.hide();
			popup_content_graph.hide();
		} else if (window_name == 'story') {
			console.log('Open Story Content');
			page_popup_window.find('h2[id="window-heading"]').empty().append('Student Stories');
			popup_content_badge.hide();
			popup_content_story.show();
			popup_content_quiz.hide();
			popup_content_graph.hide();
		} else if (window_name == 'quiz') {
			console.log('Open Quiz Content');
			page_popup_window.find('h2[id="window-heading"]').empty().append('Student Quiz Results');
			popup_content_badge.hide();
			popup_content_story.hide();
			popup_content_quiz.show();
			popup_content_graph.hide();
		} else if (window_name == 'graph') {
			console.log('Open Graph Content');
			var s_header = null;
			if ( current_student.graph == 'line' ) {
				s_header = 'Readers Read on '+current_student.day+' '+current_student.date;
			} else {
				s_header = 'Readers Read during the week starting '+current_student.day+' '+current_student.date;
			}
			page_popup_window.find('h2[id="window-heading"]').empty().append(s_header);
			popup_content_badge.hide();
			popup_content_story.hide();
			popup_content_quiz.hide();
			popup_content_graph.show();
			popup_content_graph.empty();
			$.ajax( ajax_load_day_books() );

		} else {
			window_content = 'No '+window_name+' information found. Please contact the administrator immediately.';
		}
	}

	function ajax_load_day_books() {
		return {
			url : thm_tmp_fnc_pth + '/functions/ajax_student-graph.php',
			type: 'post',
			dataType: 'json',
	        data: {
	        	'student_id'  : current_student.id,
	        	'student_wpn' : current_student.wpn,
	        	'day'		  : current_student.col,
	        	'graph' 	  : current_student.graph
	        },
	        error: function(){
	        	console.log('Ajax Failed to Start');
	        	popup_content_graph.empty().append('No information could be found on the readers read on the chosen day.');
	        },
	        success: function(graph_data) {
	        	console.log('Ajax Return Success');
	        	if ( graph_data.request_error !== null) {
	        		console.log('Ajax Returned an Error: '+graph_data.request_error);
	        		console.log(current_student);
	        		popup_content_graph.empty().append('An error occurred loading the book information for this graph.'+
	        			'Please contact the administrator immediately.'
	        		);
	        		return false;
	        	}
	        	if ( graph_data.content == null ) {
	        		popup_content_graph.empty().append('No Books were read during this time');
	        	} else {
	        		popup_content_graph.empty().append(graph_data.content);
	        	}
	        }
		};
	}

	function load_student_badge_data(badge_data){
		if ( typeof badge_data == 'undefined' || badge_data == null ) {
			return false;
		}

		//Check Student Based Badge Info ( total_points, active )
		if ( badge_data['student']['student'] ) {
			var badge_footer = popup_content_badge.find('.badge-window-footer');
			//Total Points Earned
			if ( badge_data['student']['total_points'] !== null ) {
				badge_footer.find('.badge-points span').empty().append(badge_data['student']['total_points']);
			}

		}

		//Find All Badges in Window, then Check to see if Student has unlocked any
		popup_content_badge.find('div[class="badge-wrap"][id^="badge-"]').map(function () {
			console.log('Search for User unlocked badges');
			//Get This Badge's ID
			var badge_id = $(this).attr('id').replace('badge-', '').trim();
			console.log( 'found badge: '+badge_id );
			//Check for Badges Student has Earned and Activate them.
			for ( var ii = 0; ii < badge_data['badges'].length; ii++ ) {
				console.log('Loop Student Badges: '+ badge_data['badges'][ii]['ID']);
				if ( badge_data['badges'][ii]['ID'] == badge_id ) {
					console.log( 'Found Match: Badge: ' + badge_data['badges'][ii]['ID'] + ' Has been Earned.');

					//Toggle earned class
					if ( ! $(this).hasClass('earned') ) {
						$(this).addClass('earned');
					}

					//Add
					$(this).find('.badge-earn-date-wrap span[id="badge-date"]').empty().append( badge_data['badges'][ii]['earn_date'] );
					$(this).find('.badge-earn-text-wrap').empty().append( badge_data['badges'][ii]['earn_text'] );
				} else {
					continue;
				}
			}
		});
	}

	function load_badge_pagination(this_button) {
		//Stop going before group 1 and after last group
		if ( this_button.hasClass('end') ) {
			return false;
		}

		//Was the Next or Previous Button pressed?
		var direction = 'next';
		if ( this_button.hasClass('down') ) {
			//Previous button pressed
			direction = 'prev';
		}
		//Find Current Group Num Via group Wrapper id
		var current = badge_paging_current;
		var total = badge_paging_total;

 		change_badge_group(direction, current, total);
	}

	function change_badge_group(direction, current, total) {
		//Do Nothing
		console.log('Run Change Group Function');

		--current;
		--total;

		//Determine New Group from current group and direction
		var new_current = get_new_current( direction, current );

		process_new_current( new_current );

		//Adjust on screen pagination counter
		calculate_counter( new_current +1 );

		//remove end class names if not first/last group selected
		toggle_end_group_classes( direction, current, total );
	}

	function toggle_end_group_classes( direction, current, total ) {
		var button_prev = $('.badge-button.down');
		var button_next = $('.badge-button.up');

		$('div[id^="badge-group-"]').removeClass('end');

		//Calculate New Current Group Number
		if ( current == 0 && direction == 'next' ) {
			if ( button_prev.hasClass('end') ) {
				button_prev.removeClass('end');
			}
		}
		if ( current == 1 && direction == 'prev' ) {
			if ( ! button_prev.hasClass('end') ) {
				button_prev.addClass('end');
			}
		}
		if ( current == total && direction == 'prev' ) {
			if ( button_next.hasClass('end') ) {
				button_next.removeClass('end');
			}
		}
		if ( current == (total -1) && direction == 'next' ) {
			if ( ! button_next.hasClass('end') ) {
				button_next.addClass('end');
			}
		}
	}

	function get_new_current( direction, current ) {
		var new_current = current;

		if ( direction == 'prev' ) {
			--new_current;
			//Update Current Var
			--badge_paging_current;

		} else if ( direction == 'next' ){
			++new_current;
			//Update Current Var
			++badge_paging_current;

		}

		return new_current;
	}

	function process_new_current( new_current ) {
		console.log( 'Changing To Group: '+ new_current );

		//Toggle Class On Badge Groups ( Give current class to current group )
		var current_id = 'badge-group-'+new_current;

		$('div[id^="badge-group-"].badge-pagination').map( function() {
			if ( $(this).attr('id') == current_id ) {
				if ( ! $(this).hasClass('current') ) {
					$(this).addClass('current');
				}
			} else {
				if ( $(this).hasClass('current') ) {
					$(this).removeClass('current');
				}
			}
		});
	}

	function calculate_counter(counter) {
		console.log('Group Changed - update counter');
		//Display Change to Screen
		badge_page_numbers.find('span[id="current"]').empty().append(counter);
	}

	function reset_badge_window() {
		//Return Badge Pagination to 1, student specific data to default values
		if ( popup_content_badge.find('div[id^="badge-group-"].badge-pagination').hasClass('current') ) {
			$('div[id^="badge-group-"].badge-pagination').removeClass('current');
		}
		$('div[id^="badge-group-0"].badge-pagination').addClass('current');

		//Counter Number
		badge_paging_current = 1;
		badge_page_numbers.find('span[id="current"]').empty().append(1);

		//Remove Earned Class From Badge Elements
		popup_content_badge.find('div[class="badge-wrap"][id^="badge-"]').map(function () {
			//Toggle earned class
			if ( $(this).hasClass('earned') ) {
				$(this).removeClass('earned');
			}
		});


		//Add/Remove End Classes From Student Page Window
		popup_content_badge.find('.badge-button.down').addClass('end');
		if ( badge_paging_total == 1 ) {
			if ( ! popup_content_badge.find('.badge-button.up').hasClass('end') ) {
				popup_content_badge.find('.badge-button.up').addClass('end');
			}
		} else {
			if ( popup_content_badge.find('.badge-button.up').hasClass('end') ) {
				popup_content_badge.find('.badge-button.up').removeClass('end');
			}
		}
	}

	function load_badge_display(this_badge) {
		console.log('Load Extended Badge Data');

		//Load Heading
		var badge_heading = this_badge.find('h3').text();
		badge_display_window.find('h3').empty().append(badge_heading);

		//Load Earned Date
		if (this_badge.hasClass('earned') ) {
			//Display Earn Date
			var badge_earn_date = this_badge.find('.badge-earn-date-wrap span').text();
			badge_display_window.find('.badge-earn-date-wrap span').empty().append(badge_earn_date);
			badge_display_window.find('.badge-earn-date-wrap').show();

			//Display Congratulations Text
			var badge_earn_text = this_badge.find('.badge-earn-text-wrap').text();
			badge_display_window.find('badge-earn-text-wrap').empty().append(badge_earn_text);
			badge_display_window.find('badge-earn-text-wrap').show();

		} else {
			badge_display_window.find('.badge-earn-date-wrap').hide();
			badge_display_window.find('badge-earn-text-wrap').hide();
		}

		//Load Badge Thumbnail
		var badge_thumbnail = this_badge.find('.badge-thumbnail-wrap').clone();
		badge_display_window.find('.badge-thumbnail-wrap').replaceWith(badge_thumbnail);

		//Load Badge Excerpt
		var badge_excerpt = this_badge.find('.badge-excerpt-wrap').text();
		badge_display_window.find('.badge-excerpt-wrap').empty().append(badge_excerpt);

		//Load Congratulations Text
		var badge_award_text = this_badge.find('.badge-earn-text-wrap').text();
		badge_display_window.find('.badge-earn-text-wrap').empty().append(badge_award_text);

		badge_display_window.show().fadeTo(200, 1);
	}

	function callback_student_details() {
		//Animate Pie Chart
		student_piechart_1.fadeTo(200, 0).empty();
		student_piechart_2.fadeTo(200, 0).empty();

		console.log('calculate percent of anrrated books');
		console.log('Student Books Narated ('+student_books_narrated+ ') / student readers read ('+student_books_completed+')');
		var narrated_percent = 0;
		if ( student_books_completed !== 0 ) {
			narrated_percent = student_books_narrated / student_books_completed * 100;
		}
		console.log('narrated percent: '+narrated_percent);
		var narrated_time = 1500 / 100 * narrated_percent;
		$('#section-2-block').find('p.narrated span').empty().append(0).animateNumber({number: narrated_percent}, narrated_time );

		pie_1_fragment = 0;
		pie_1_remainder = 0;
		pie_2_fragment = 0;
		pie_2_remainder = 0;

		console.log('UNIQUE BOOKS NUMBER = '+student_books_unique );

		if ( Number(student_books_unique) !== 0 ) {
			console.log('unique books is not 0');
			pie_1_fragment = Number( student_books_new / student_books_unique * 100 );
			Math.round(pie_1_fragment);
			pie_1_remainder = Math.round(100 - pie_1_fragment);
		}

		if ( Number(student_books_completed) !== 0 ) {
			console.log('completed books is not 0');
			pie_2_fragment = Number( student_books_fiction / student_books_completed * 100 );
			Math.round(pie_2_fragment);
			pie_2_remainder = Math.round(100 - pie_2_fragment);
		}

		//Pie Chart 1
		if ( pie_1_fragment == 0 && pie_1_remainder == 0 ) {
			student_piechart_1.append('<p>No Readers Read</p>');
		} else {
			Morris.Donut({
				element: 'section-1-chart',
                                resize: 'true',
				data : [
			        {label: 'New Books', value: pie_1_fragment},
			        {label: 'Re-read Books', value: pie_1_remainder},
			    ]

			});
		}

		//Pie Chart 2
		if ( pie_2_fragment == 0 && pie_2_remainder == 0 ) {
			student_piechart_2.append('<p>No Readers Read</p>');
		} else {
			Morris.Donut({
				element: 'section-2-chart',
                                resize: 'true',
				data : [
			        {label: 'Fiction', value: pie_2_fragment},
			        {label: 'Non-Fiction', value: pie_2_remainder},
			    ]
			});
		}
		//Animate Pie Chart
		student_piechart_1.fadeTo(200, 1);
		student_piechart_2.fadeTo(200, 1);
	}

	function load_single_student() {
		//Load A single student for the "My Page" statistics
		var student_id = 0;

		//Step 1 - FadeOut Overview Table
		//page_section_overview.fadeTo(200, 0.4);
		//Step 2 - Display Loading Element
		page_popup_window.find('#window-content').empty();
		page_section_loading.show().fadeTo(200, 1);

		//Step 3- Get parent Element
		var parent_element = $('tr.student-table-row.row-odd');
		//Step 4 - Get Parents Index in student table
		parent_index = parent_element.index();
		console.log('Pareant Element Index: '+ parent_index);
		//Step 5 - Get Parent Element ID containing student ID
		var student_id = parent_element.attr('id').replace('student-', '').trim();
		//Step 7 - Gather Student Security Nonce
		var student_wpn = parent_element.find('._student_wpn').attr('value');
		//Step 8 - Student ID collected, Prepare to Load Student Detail Window
		load_student_details(student_id, student_wpn);
	}

});