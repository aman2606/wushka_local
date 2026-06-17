<?php

/* ------------------------------------------------------ *
 *
 *			  --------- My Favourites ---------
 *
 * ------------------------------------------------------ */
class Student_Favourites {
	// --- Constants --- \\
	private $_i_student_id;
	private $_i_teacher_id;
	private $_a_books;
	public  $_b_status;

	//Loads Class
	//Stores Current Teacher ID (MUST BE PASSED TO CONSTRUCTOR)
	//If Table doesn't exist, create it.
	public function __construct( $i_student_id = NULL ) {
		$this->_i_students_id = NULL;
		if ( ! is_user_logged_in() || ! user_can( $i_student_id, 'student') || ! isset($i_student_id) ) {
			error_log('ERROR: No Student ID Passed to Class');
		}
		$this->_i_student_id = $i_student_id;
		$this->_i_teacher_id = get_user_meta($i_student_id, 'teacher_id', true);
		$this->_b_status = $this->get_student_favourites();
	}

	public function load_stylesheets() {
		$tmp_dir =  get_template_directory_uri().'/functions/favourites/';
		$a_stylesheets[] = '<link type="text/css" rel="stylesheet" href="'.$tmp_dir.'css_my-favourites.css" />';

		echo implode('', $a_stylesheets);
	}

	public function load_page() {
		$a_body_html[] = '<div class="container page-wrap padding-y">';
			$a_body_html[] = '<div class="row page-content-wrap">';
				$a_body_html[] = '<div class="col-xs-12"><h1 class="title-heading">My Favourites</h1></div>';
				$a_body_html[] = $this->load_favourites();
			$a_body_html[] = '</div>';
		$a_body_html[] = '</div>';

		echo implode('', $a_body_html);
	}

	/* ---------- Get Student Favourites ---------- */
	// Returns Array of Post IDs of Top 12 Student Books
	private function get_student_favourites() {
		global $wpdb;
		//Check Teacher ID was passed
		if ( ! isset($this->_i_student_id) ) {
			error_log('No Student ID Stored, Abort');
			return FALSE;
		}
		//Run Query To Gather Collection Books
		$s_query = 'SELECT pm.post_id, ra.essis_resource_id as res_id, p.post_title, count(ra.read_id) as total_books '.
			'FROM '.$wpdb->prefix.'lessonzone_reading_analytics_reading_instance ra '.
			'LEFT JOIN '.$wpdb->prefix.'postmeta pm ON pm.meta_key = %s AND pm.meta_value = ra.essis_resource_id '.
			'LEFT JOIN '.$wpdb->prefix.'posts p ON p.ID = pm.post_id '.
			'WHERE ra.user_id = %d AND ra.completed = %d '.
			'GROUP BY ra.essis_resource_id ORDER BY total_books DESC LIMIT %d,%d';

		$a_params = array('esiss_resource_id', $this->_i_student_id, 1, 0, 12);

		$o_results = $wpdb->get_results( $wpdb->prepare($s_query, $a_params) );

		// ----- Query Error ----- \\
		if ( $o_results === NULL || $o_results === FALSE ) {
			error_log('---------- My Favourites Error ----------');
			error_log('An Error occured getting Student Favourites');
			error_log('-----------------------------------------');
			return FALSE;
		}

		error_log('Found '.count($o_results).' Books');
		$a_results = array();

		foreach($o_results as $idx => $book ) {
			$a_results[] = array(
				'ID' 			=> $book->post_id,
				'post_title' 	=> $book->post_title,
				'res_id'		=> $book->res_id,
				'total_books' 	=> $book->total_books
			);
		}

		$this->_a_books = $a_results;
		return TRUE;
	}

	private function load_favourites() {
		if ( $this->_b_status !== TRUE || empty( $this->_a_books ) ) {
			$a_html[] = '<div class="collect error-msg">';
				$a_html[] = '<p>You Haven\'t Read Any Books Yet. To read some now go to our Reading Boxes by clicking the button above.</p>';
			$a_html[] = '</div>';
			return implode('', $a_html);
		}

		$a_books_html = array();
		foreach ( $this->_a_books as $i_key => $a_book ) {

			$s_permalink = get_permalink($a_book['ID']);
			$s_post_image = get_post_meta($a_book['ID'], 'post_image', true);

	 		$a_book_html[] = '<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 student-favourite book-wrap" id="book-'.$a_book['ID'].'">';
	 			$a_book_html[] = '<input type="hidden" id="res-id" value="'.$a_book['res_id'].'" />';
	 			$a_book_html[] = '<a href="'.$s_permalink.'">';
					$a_book_html[] = '<img src="'.$s_post_image.'" title="'.$a_book['post_title'].'" class="book-thumb img-responsive" />';
				$a_book_html[] = '</a>';
			$a_book_html[] = '</div>';
			//Save to Array
			$a_books_html[] = implode('', $a_book_html);
			unset($a_book_html);

		}

		$a_html[] = '<div class="favourites-wrap">';
			$a_html[] = implode('', $a_books_html);
		$a_html[] = '</div>';

		return implode('', $a_html);
	}

}
