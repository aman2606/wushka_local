jQuery(document).ready(function($) {
	var console = {};
	console.log = function(){};
	
/**
 *	Too many inconsistencies between download buttons across site: 
 *	- For now create separate click events for each type:
 *    standardise/unify them later after framework is complete (stamper/postattach updates)
 **/	

	
	/* ========================================================================
	 * 		PRINT/DOWNLOAD/PLAY BUTTONS ON SINGLE / ITEM DETAIL PAGE
	 * ======================================================================== */
	$(document).on('click', '.lz-res', function(e) {
		//Get Clicked Button Element
		var this_btn  		= $(this);
		//Declare Variables for stat gathering
		var res_type  		= null;
		var btn_type  		= null;
		var post_id 		= 0;
		var attachment_id 	= 0;		
		var event_location  = 'single'; 
		
		//Determine Button & Resource Type
		if( this_btn.hasClass('btn-ebook') || this_btn.hasClass('lzkids_ebook') ) {
			//Ebook Type
			res_type = 'ebook';
			btn_type = 'play';
		} else if( this_btn.hasClass('btn-attach') ) {
			//Attachment/Language Variant Types
			if( this_btn.hasClass('support')) {
				//Ebook Teacher Suppoer Material Type
				res_type = 'support';
			} else if( this_btn.hasClass('variant') ) {
				//Printable Language Variant Type
				res_type = 'variant';
			}
		} else if ( this_btn.hasClass('pdf_btn') ) {
			event_location = 'collection'; 
			//My Area Collection Buttons
			if ( this_btn.hasClass('ebook') ) {
				res_type = 'ebook';
				btn_type = 'play';
			} else if (this_btn.hasClass('post') ) {
				res_type = 'post';
			}
		} else {
			//Printables Type
			res_type = 'post';
		}
			
		if( this_btn.hasClass('print') ) {
			btn_type = 'print';
		} else if (this_btn.hasClass('download') ) {
			btn_type = 'download';
		}	

		//Gather Post ID depending on res_type
		if( res_type !== null ) {
			//Get Post ID for single page buttons
			if ( event_location == 'single' ) {
				console.log('res type found for single, get postid');
				if ( (post_id = my_statistics_single_page_id('single', this_btn)) === false ) {
					console.log('My Stats - event click fail - no single post id.');
					return true;
				}
				//Check for Attachments, and if so get attachment id
				if( res_type == 'support' ) {
					attachment_id = this_btn.parent().find('#lzPA-ebookID').val();
				} else if (res_type == 'variant' ) {
					attachment_id = this_btn.parent().find('#lzPA_pdfFileID').val();
				}	
			} else if ( event_location == 'collection' ) {
				console.log('res_type found for collection, get postid');
				if ( (post_id = my_statistics_single_page_id('collection', this_btn)) === false ) {
					console.log('My Stats - event click fail - no collection post id.');
					return true;
				}
				
			}
			//Resource: POST type
		} else {
			console.log('No res_type found for this button.');
			return true;
		}
		
		//Check required Stats:
		console.log('post_id 		= '+post_id);
		console.log('attachment_id 	= '+attachment_id);
		console.log('res_type 		= '+res_type);
		console.log('btn_type 		= '+btn_type);
		console.log('event location = '+event_location);
		//Stats Good, pass to function
		console.log('stats good. -> pass to function');
		my_statistics_event_tracker(post_id, attachment_id, res_type, btn_type, event_location);
		return true;
	});
		
	// ---------------- GATHER ID FROM SINGLE POST/EBOOK PAGE ----------------- \\
	// 		- variable: page_type (string) - Add accepted returns to array		
	function my_statistics_single_page_id( page_type, this_btn ) {
		var allowed_types = ["single", "collection"];
		if( typeof page_type == 'undefined' || jQuery.inArray(page_type, allowed_types) == -1 ) {
			return false;
		}
		var post_id = 0;
		
		if ( page_type == 'single' ) {
			//Div element of single post page
			var single_post = $(".status-publish.post-wrapper");  
			if (single_post.length > 0) {
				//Pull Post ID
				var id_elem = single_post.attr('id');
				var elem_split = id_elem.split('-');
				post_id = elem_split[1];
				console.log('stored single post id: '+post_id);
				return post_id;
			} else {
				//Couldn't Find post wrapper
				console.log('NO single post wrapper found');
				return false;
			}
		} else if ( page_type == 'collection' ) {
			//Old My Area
			var btn_parent = this_btn.parents('div[class="singleGroupList"][id^="groupResource-"]');
			if ( btn_parent.length > 0 ) {
				var elem_split = btn_parent.attr('id').split('-');
				post_id = elem_split[1];
				console.log('stored collection post id: '+post_id);
				return post_id;
			} else {
				console.log('NO collection post wrapper found');
				return false;
			}
			
			//New My Area
			/*var post_id = this_btn.parent().find('#lzPA_resourceID').val();
			if ( post_id.length > 0 ) {
				console.log('stored collection post id: '+post_id);
				return post_id;
			} else {
				console.log('NO collection post id found');
				return false;
			}*/
		}
		
		return false;
	}
	
	// --------------------------- AJAX CALL FROM TRACKING CURRENT EVENT ---------------------------\\
	function my_statistics_event_tracker( post_id, attachment_id, res_type, btn_type, event_location ) {
		console.log('my statistics (event tracker function) - activated.');
		console.log('Check Passed Variables:');
		
		if ( post_id == 0 || res_type == null || btn_type == null || event_location == null ) {
			console.log('my statistics (event tracker function) - ERROR (null value passed).');
			return false;
		} 
		console.log('my statistics (event tracker function) - passed stats good. continue.');
	
		//Run Ajax Event for tracking Lesson Zone resource Click
		$.ajax({
			url			: mystat_ajax.ajax_url,
			type		: 'POST',
			dataType	: 'json',
			data		: {
				'my_stats_event' : 'click_event',
				'post_id'		 : post_id,
				'attachment_id'  : attachment_id,
				'res_type'	   	 : res_type,
				'btn_type'		 : btn_type,
				'event_location' : event_location,
				'data_check'	 : mystat_ajax.data_check,
			},
			error : function(error_1, error_2, error_3) {
				console.log('my statistics (Ajax) - RETURN ERROR');
				console.log('Ajax Error: '+error_1.status);
				console.log('Ajax Error: '+error_3);
			},
			success : function(return_data) {
				if ( typeof return_data != 'undefined' ) {
					console.log('my statistics (Ajax) - returned :'+return_data);
					if ( return_data == 0 ) {
						console.log('my statistics (Ajax) - INSERT ERROR');
					} else if ( return_data == 1 ) { 
						console.log('my statistics (Ajax) - INSERT SUCCESS');
					}
				} else {
					console.log('my statistics (Ajax) - Undefined Return');
				}
			},
		});
		
		return true;
	}
	
	
//----- EOF ----- \\
});