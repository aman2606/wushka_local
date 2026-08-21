jQuery(document).ready(function($){
	console.log = function() {}
	//Initialise Important Maps Vars
	var o_map;
	var o_window;
	var a_options;
	var i_result_limit = 10;
	//Set Group Station Marker Variables
	var a_markers = [];
	var a_schools = [];

    var preventSubmit = function(event) {
        if(event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    }

    $('#search_field').keypress(preventSubmit);
    $('#search_field').keydown(preventSubmit);
    $('#search_field').keyup(preventSubmit);

	//initialise_map();
	//Activation Events
	$('#activate_search').on('click', function(){
		//Clear Existing
		clear_all_results();
		close_confirmation_window();
		run_search();
	});

	$('#confirm-no').on('click', function(){
		close_confirmation_window();
	});

	$('#confirm-yes').on('click', function(){
		///Hide Window
		close_confirmation_window();
		if ( $('#school-confirmed').hasClass('disabled') ) {
			$('#school-confirmed').removeClass('disabled');
		}
		//Store Current School
		var i_school = $('[id^="selected-school-"]').attr('id').replace('selected-school-', '').trim();
		console.log('I have selected school '+i_school);
		var o_selected = a_schools[i_school];

		//Load School Years
		$('#loading_years').show().fadeTo(300, 1);
		$('input[name="child_school"]').attr('value', o_selected.school.slug ).append();
		$('#school_name').attr('value', o_selected.school.name );
		$('#select_school').empty().append(o_selected.school.name);

		var s_url 	= a_user_script.ajax_url;
		var s_var_1 = a_user_script.validate;
		var s_var_2 = o_selected.school.slug;

		load_school_years_ajax(s_url, s_var_1, s_var_2);

	});

	$("#search_field").keyup(function(e){
		if( e.which == 13 ) {
			//Clear Existing
			clear_all_results();
			close_confirmation_window();
	    	run_search();
	    	return true;
	    }
	   	return false;
	});

	$(document).on('click', '.btn-goTo', function(){
		var i_school = $(this).parents('.panel.school-panel').attr('id').replace('school-', '').trim();
		view_school(i_school);
	});

	function run_search(){
		var raw_input = $('#search_field').val().trim();
		if ( raw_input == null || raw_input == '' || raw_input.length < 3 ) {
			return false;
		}

		o_window.close();
		o_map.setZoom(4);
		o_map.panTo(new google.maps.LatLng(-28.133269, 134.227957));

		//Retrieve Search Results
		a_schools = search_schools(raw_input);
		var s_results = '';

		if ( a_schools.length > 0 ) {
			//Build Results HTML
			var i_hits = a_schools.length;
			//console.log('Found '+i_hits+' Matches');
			var s_matches = '';
			var i_limit = i_result_limit;
			if ( i_hits <= i_limit ) {
				i_limit = i_hits;
				s_matches = i_limit+' '+(( i_hits == 1 ) ? 'result' : 'results');
			} else {
				s_matches = i_limit+' of '+i_hits+' '+(( i_hits == 1 ) ? 'result' : 'results');
			}
				console.log('MARKERS BEFORE DATA LOAD:'+a_markers.length);
				console.log(a_markers)
			$('#section-results .panel-heading').empty().append('<h3 class="panel-title">Showing '+s_matches+' matching "'+raw_input+'"');
			for(var ix = 0; ix < i_limit; ix++) {
				s_results += build_result(ix, a_schools[ix]);
				add_school_marker( ix, a_schools[ix] );
			}

			} else {
				$('#section-results .panel-heading').empty().append('<h3 class="panel-title">0 Results matching "'+raw_input+'"');
				s_results += '<label class="form-control">No Results Found</label>';
				clear_all_results();
			}

			$('#results-wrap').fadeTo(300,0, function() {
				$('#section-results #results-wrap').empty().append(s_results);
			$('#results-wrap').fadeTo(300, 1);
		});

		return true;
	}

	function view_school(i_key) {
		if ( typeof i_key == 'undefined' || i_key == null ) {
			return false;
		}
		console.log('Open Info Window On School-'+i_key);
		//1. Zoom in on Selected School
		//2. Open Info Window
		var o_school = a_schools[i_key];

		var s_window = build_window(i_key, o_school);

		o_map.setZoom(15);
		o_map.panTo(a_markers[i_key].getPosition());

		o_window.setContent(s_window);
		o_window.open(o_map, a_markers[i_key]);

		open_confirmation_window();
	}


	function search_schools(raw_input) {
		//Clean Raw Input
		var input = raw_input.replace(/[^\w]/gi, '').replace(' ', '|').trim();
		//Create RegExp
		var pattern = new RegExp( input,'ig');
		var a_results = [];
		//Run Query
		for(var i_id = 0; i_id < a_terms.length; i_id++) {
			var s_name = a_terms[i_id].school.name.replace(/[^\w]/gi, '').trim();
			if ( pattern.test(s_name) || pattern.test(a_terms[i_id].school.description.trim()) ) {
				a_results.push(a_terms[i_id]);
			}
		}

		return a_results;
	}

	function build_result(i_key, o_school) {
		if ( typeof o_school == 'undefined' || o_school == null ) {
			return null;
		}

		var s_panel = '<div class="panel panel-default school-panel" id="school-'+i_key+'">'+
			'<div class="panel-heading">'+
				o_school.school.name+
				'<div class="pull-right">'+
				'<button type="button" class="btn btn-primary btn-small btn-goTo" title="View This School"><i class="glyphicon glyphicon-target"></i></button>'+
				'</div>'+
			'</div>'+
			'<div class="panel-body">'+
				'<div class="col-xs-12">'+
					'<label>Address: '+o_school.school.description+'</label>'+
					'<input type="hidden" value="'+o_school.school.slug+'"/>'+
				'</div>'+
			'</div>'+
		'</div>';

		return s_panel;
	}

	function build_window(i_key, o_school) {
		if ( typeof o_school == 'undefined' || o_school == null ) {
			return null;
		}

		var s_panel = '<div style="color:#444;" id="selected-school-'+i_key+'">'+
			'<h3>'+o_school.school.name+'</h3>'+
			'<label>Address: '+o_school.school.description+'</label>'+
			'<input type="hidden" value="'+o_school.school.slug+'"/>'+
		'</div>';

		return s_panel;
	}


	/* ----- Searchable Schools with Map ----- */
	/* Process :
	 * 1. User enters search string
	 * 2. Query
	 */
	function is_active ( o_map ) {
		if ( o_map === undefined || typeof o_map == 'undefined' || o_map == null ){
			return false;
		}

		if ( o_map.length > 0 ) {
			return true;
		}

		return false;
	}

	/*
	 * Initialise Google Maps Functionality
	 */
	function initialise_map() {
		if ( is_active( o_map ) ) {
			return false;
		}
		//Set Map Options
		var e_canvas = document.getElementById('map-canvas');

		a_options = {
			center : new google.maps.LatLng(-28.133269, 134.227957),
			zoom : 4,
			mapTypeId : google.maps.MapTypeId.ROADMAP
		};

		//Map Objects
		o_map 	= new google.maps.Map(e_canvas, a_options);
		o_window = new google.maps.InfoWindow();

		return true;
	}

	function add_school_marker(i_key, o_school ) {

		var i_lat = ( typeof o_school.options.school_latitude !== 'undefined' && o_school.options.school_latitude !== null ) ? Number(o_school.options.school_latitude) : 0;
		var i_lng = ( typeof o_school.options.school_longitude !== 'undefined' && o_school.options.school_longitude !== null ) ? Number(o_school.options.school_longitude) : 0;

		if ( i_lat == 0 && i_lng == 0 ) {
			return false;
		}

		var i_pos = new google.maps.LatLng( i_lat, i_lng );

		//New Marker
		var o_marker = new google.maps.Marker({
			position : i_pos,
			map 	 : o_map,
			title 	 : o_school.school.description,
			zIndex   : 1
		});

		google.maps.event.addListener(o_marker, 'click', function() {
			o_window.close();
			o_map.setZoom(15);
			o_map.panTo(o_marker.getPosition());

			o_window.setContent(build_window(i_key, o_school));
			o_window.open(o_map, o_marker);
			open_confirmation_window();
		});

		a_markers[i_key] = o_marker;
		return true;
	}

	//Remove all markers in the passed array
	function clear_all_results() {
		var a_remove = a_markers;
		console.log('Markers to remove: '+a_remove.length);
		console.log(a_remove);
		a_markers = remove_markers(a_remove);
		console.log('Markers left: '+a_markers.length);
		console.log(a_markers);
	}

	function remove_markers(a_remove) {
		for ( var i = a_remove.length - 1; i >= 0 ; i--) {
			console.log('removing marker '+i);
			console.log(a_remove[i]);
			a_remove[i].setMap(null);
			a_remove.splice(i, 1);
		}
		return [];
	}

	function open_confirmation_window() {
		$('.overlay-2').show().fadeTo(500, 1);
	}
	function close_confirmation_window() {
		$('.overlay-2').fadeTo(500, 0, function(){
			$('.overlay-2').hide();
		});
	}

	$('.settings#panel_1').show().fadeTo(300, 1);


	function getRandomInt(min, max) {
        return min + Math.floor(Math.random() * (max - min + 1));
    }

    $('.new-child input').on('change', function () {
        var fname = $('#add-new-child #first_name').val().charAt(0);
        var lname = $('#add-new-child #last_name').val().charAt(0);
        if (fname || lname) {
        	var random_number = getRandomInt(1000, 99999);
            $('#username').val(fname + lname + '-' + random_number);
        }
    });

	function load_school_years_ajax(s_url, s_var_1, s_var_2) {
        $.ajax({
			type: "POST",
            dataType: "json",
            url: s_url,
            data: {
            	action  : 'wushka_load_school_years',
             	s_var_1 : JSON.stringify(s_var_1),
            	s_var_2 : JSON.stringify(s_var_2)
            },
            success: function (return_data) {
            	console.log('Ajax Success:');
				console.log('Status = '+return_data.status);
				console.log('Message = '+return_data.message);
				if ( validate_ajax_return(return_data) === false ) {
					//console.log('Error Found in Return Data');
					return false;
            	} else {
            		$('#child_year').empty().append(return_data.html);
            		$('#loading_years').fadeTo(300, 0, function(){
            			$('#child_year').parents('.form-group').show().fadeTo(300, 1);
            			$('#loading_years').hide();
                	});
    			}
            	return true;
            },
            error: function(jqXHR, textStatus, errorThrown ) {
            	console.log(jqXHR);
            	console.log(textStatus);
            	console.log(errorThrown);
            },
        });
	}

	function validate_ajax_return(a_return) {
		//Undefined Check
		if ( typeof a_return == 'undefined' || a_return === undefined ) {
			return false;
		}
		//Empty Check
		if ( a_return == null || a_return.length <= 0 ) {
			return false;
		}
		//Return Status Field Check
		if ( a_return.status == 0 ) {
			return false;
		}

		return true;
	}

	//Navigation
	var a_navigation = [
	    null,
	    {'id' : 1, 'prev' : 0, 'next' : 2}, //Panel 1
	    {'id' : 2, 'prev' : 1, 'next' : 3}, //Panel 2
	    {'id' : 3, 'prev' : 2, 'next' : 6}, //Panel 3
	    {'id' : 4, 'prev' : 2, 'next' : 5}, //Panel 4
	    {'id' : 5, 'prev' : 4, 'next' : 6}, //Panel 5
	    {'id' : 6, 'prev' : 1, 'next' : 0}, //Panel 6
	];

	$('.btn-next').on('click', function(){
		var i_no = parseInt($(this).parents('.settings').attr('id').replace('panel_', '').trim());

		var a_panel = a_navigation[i_no];
		var panel_in = a_panel.next;

		if ( $(this).hasClass('to-finish') ) {
			panel_in = 6;
		}
		panel_fadeout(a_panel.id, 'next');
		panel_fadein(panel_in, 'next');

		if ( panel_in === 6 ) {
			view_info();
		}
	});

	$('.btn-prev').on('click', function(){
		var i_no = parseInt($(this).parents('.settings').attr('id').replace('panel_', '').trim());
		var a_panel = a_navigation[i_no];
		panel_fadeout(a_panel.id, 'prev');
		panel_fadein(a_panel.prev, 'prev');
	});

	$('.btn-start').on('click', function(){
		var i_no = parseInt($(this).parents('.settings').attr('id').replace('panel_', '').trim());
		var a_panel = a_navigation[i_no];
		panel_fadeout(a_panel.id, 'prev');
		panel_fadein(a_panel.prev, 'prev');

		$('#panel_2').removeClass('fadeLeft').addClass('fadeRight');
		$('#panel_3').removeClass('fadeLeft').addClass('fadeRight');
		$('#panel_4').removeClass('fadeLeft').addClass('fadeRight');
		$('#panel_5').removeClass('fadeLeft').addClass('fadeRight');
		$('#panel_6').removeClass('fadeLeft').addClass('fadeRight');
	});

	$('.btn-home').on('click', function(){
		panel_fadeout(2, 'next');
		panel_fadein(3, 'next');
	});

	$('.btn-school').on('click', function(){
		panel_fadeout(2, 'next');
		panel_fadein(4, 'next');
		initialise_map();
	});

	function panel_fadeout(i_id, s_type) {
		var panel = $('.settings#panel_'+i_id);
		var s_class = (s_type == 'next' ) ? 'fadeLeft' : 'fadeRight';

		panel.addClass(s_class);
		panel.fadeTo(200, 0, function(){
			panel.hide();
		});
		return true;
	}

	function panel_fadein(i_id, s_type) {
		var panel = $('.settings#panel_'+i_id);

		var s_class = (s_type == 'next' ) ? 'fadeRight' : 'fadeLeft';

		panel.show().fadeTo(200, 1);
		panel.removeClass(s_class);

		return true;
	}

	function view_info() {
		//First Name
		var fname = $('input[name="first_name"]').val().trim();
		$('#confirm-first_name').empty().append(fname);
		//Last Name
		var lname = $('input[name="last_name"]').val().trim();
		$('#confirm-last_name').empty().append(lname);
		//Date of Birth
		var dob_d = $('input[name="dob_day"]').val().trim();
		var dob_m = $('input[name="dob_month"]').val().trim();
		var dob_y = $('input[name="dob_year"]').val().trim();
		if ( dob_d.length > 0 && dob_m.length > 0 && dob_y.length > 0 ) {
			$('#confirm-dob').empty().append(dob_d+'/'+dob_m+'/'+dob_y);
		}
		//School Name
		var s_school = $('#school_name').val().trim();
		if ( s_school.length > 0 ) {
			$('#confirm-school').empty().append(s_school);
		} else {
			$('#confirm-school').empty().append('Home Schooled');
		}
		//School Year
		var year = $('select[name="child_year"] :selected').text().trim();
		if ( year.length > 0 ) {
			$('#confirm-year').empty().append(year);
		} else {
			$('#confirm-year').empty().append('No Year Selected');
		}

		var s_school_name = $('#school_username').val().trim();
		if ( s_school_name.length > 0 ) {
			$('#confirm-school_username').empty().append(s_school_name);
		} else {
			$('#confirm-school_username').empty().append('No Student User Linked');
		}
	}

	function get_school_by_slug(i_slug) {
		for(var i = 0; i < a_terms.length; i++) {
			if ( a_terms[i].school.slug == i_slug ) {
				return a_terms[i];
			}
		}
		return null;
	}

    function getRandomInt(min, max) {
        return min + Math.floor(Math.random() * (max - min + 1));
    }

    $('#first_name, #last_name').on('change', function () {
        var fname = $('#first_name').val().charAt(0);
        var lname = $('#last_name').val().charAt(0);
        if (fname || lname) {
            $('#username').val(fname + lname + '-' + random_number);
        }
    });

    random_number = getRandomInt(1000, 99999);


    //If student username recieves input
    //Check user account.
    $(document).on('click', '#check-student-acc', function(){
    	console.log('Checking Student Account');
    	load_glyph_run();
    	$('#link-load-label').fadeTo(200, 1, function(){
    		if ( ! validate_student_params() ) {
    			console.log('Student Details Failed Validation');
    			load_glyph_fail('Please enter the username and password provided');
    			return false;
    		}
			console.log('Student Details Valid, Seek and Link');
		   	var s_name 	=  $('#school_username').val().trim();
	    	var s_pwd 	=  $('#school_password').val().trim();
    		link_student_account( a_user_script.ajax_url, a_user_script.validate, s_name, s_pwd);
    	});

    });

    function validate_student_params() {
    	var s_name 	=  $('#school_username').val();
    	var s_pwd 	=  $('#school_password').val();
    	if ( typeof s_name == 'undefined' || typeof s_pwd == 'undefined' ) {
    		return false;
    	}

    	if ( s_name == null || s_pwd == null ) {
    		return false;
    	}

    	if ( s_name.length <= 0 || s_pwd.length <= 0 ) {
    		return false;
    	}

    	return true;
    }

    function load_glyph_run() {
    	$('#link-load-label').parent().removeClass('has-success');
    	$('#link-load-label').parent().removeClass('has-error');
    	if ( ! $('#link-load-glyph').hasClass('loading') ) {
    		$('#link-load-glyph').addClass('loading');
    	}
    	$('#link-load-label p').empty().append('Loading...');
    	$('#link-load-glyph').addClass('glyphicon-cogwheel');
    	if ( $('#link-load-glyph').hasClass('glyphicon-circle-remove') ) {
    		$('#link-load-glyph').removeClass('glyphicon-circle-remove');
    	}
    	if ( $('#link-load-glyph').hasClass('glyphicon-circle-ok') ) {
    		$('#link-load-glyph').removeClass('glyphicon-circle-ok');
    	}
    }

    function load_glyph_fail(s_msg) {
    	$('#link-load-label').parent().addClass('has-error');
    	if ( $('#link-load-glyph').hasClass('loading') ) {
    		$('#link-load-glyph').removeClass('loading');
    	}
    	$('#link-load-label p').empty().append(s_msg);
    	if ( $('#link-load-glyph').hasClass('glyphicon-cogwheel') ) {
    		$('#link-load-glyph').removeClass('glyphicon-cogwheel');
    	}
    	$('#link-load-glyph').addClass('glyphicon-circle-remove');
    }

    function load_glyph_success(s_msg) {
    	if ( $('#link-load-glyph').hasClass('loading') ) {
    		$('#link-load-glyph').removeClass('loading');
    	}
    	$('#link-load-label p').empty().append(s_msg);
    	if ( $('#link-load-glyph').hasClass('glyphicon-cogwheel') ) {
    		$('#link-load-glyph').removeClass('glyphicon-cogwheel');
    	}
    	$('#link-load-glyph').addClass('glyphicon-circle-ok');
    	$('#link-load-label').parent().addClass('has-success');
    }

    function link_student_account(s_url, s_val, s_name, s_pwd) {
        $.ajax({
			type: "POST",
            dataType: "json",
            url: s_url,
            data: {
            	action  : 'wushka_check_student_link',
            	s_val : JSON.stringify(s_val),
             	s_name : JSON.stringify(s_name),
            	s_pwd : JSON.stringify(s_pwd),
                s_date: JSON.stringify(new Date().getTime())
            },
            success: function (return_data) {
            	console.log('Ajax Success:');
				console.log('Status = '+return_data.status);
				console.log('Message = '+return_data.message);
				if ( validate_ajax_return(return_data) === false ) {
					//console.log('Error Found in Return Data');
					load_glyph_fail(return_data.message);
					return false;
            	} else {
            		load_glyph_success(return_data.message);
            		console.log(return_data.data);
            		var a_school = get_school_by_slug(return_data.data.s_id);
            		$('#child_school').attr('value', a_school.school.slug);
            		$('#school_name').attr('value', a_school.school.name);
            		$('#panel_1 .btn.btn-next').empty().append('Confirm Details');
            		$('#panel_1 .btn.btn-next').addClass('to-finish');
            		$('#child_year').empty().append('<option value="'+return_data.data.s_year.slug+'" selected="selected">'+return_data.data.s_year.name+'</option>')
    			}
            	return true;
            },
            error: function(jqXHR, textStatus, errorThrown ) {
            	console.log(jqXHR);
            	console.log(textStatus);
            	console.log(errorThrown);
            },
        });
	}

    $('#add-new-child').on('submit', function(){

    	console.log('form data');
    	console.log($(this));

    	return false;
    });


});