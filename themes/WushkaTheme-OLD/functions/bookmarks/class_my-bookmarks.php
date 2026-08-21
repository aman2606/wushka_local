<?php

/* ------------------------------------------------------ *
 *
 *			  --------- My Bookmarks ---------
 *
 * ------------------------------------------------------ */

class Wushka_Bookmarks {

    private $_s_table;
    private $_i_user_id;
    private $_i_link;
    private $_s_type;
    private $_a_books;
    private $a_ids;
    public  $_b_status;

    //Loads Class
    //Stores Current User ID (MUST BE PASSED TO CONSTRUCTOR)
    //Stored What Type of Of User: Teacher or Student
    //If Table doesn't exist, create it.
    public function __construct( $i_user_id = NULL, $s_format = NULL ) {
        $this->_i_user_id = NULL;
        $this->_i_link    = NULL;
        $this->_s_type    = NULL;
        $this->a_ids      = array();
        if( is_user_logged_in() && (user_can($i_user_id, 'teacher') || user_can($i_user_id, 'student')) ) {
            $user = get_user_by('id', $i_user_id);
            if( $user !== FALSE ) {
                $this->_i_user_id = $user->ID;
                if( isset($user->child_link_id) && ! empty($user->child_link_id) ) {
                    $this->_i_link = $user->child_link_id;
                }
                if( isset($user->student_link_id) && ! empty($user->student_link_id) ) {
                    $this->_i_link = $user->student_link_id;
                }
            }


        }
        if( $s_format == 'teacher' || $s_format == 'student' ) {
            $this->_s_type = $s_format;
        }

        $this->initialise_system();
        $this->_b_status = $this->get_user_bookmarks();
    }

    public function load_stylesheets() {
        $tmp_dir         = get_template_directory_uri() . '/functions/bookmarks/';
        $a_stylesheets[] = '<link type="text/css" rel="stylesheet" href="' . $tmp_dir . 'css_my-bookmarks.css" />';
        $a_stylesheets[] = '<script>';
        $a_stylesheets[] = 'var temp_fle_drctry = "' . $tmp_dir . '";';
        $a_stylesheets[] = '</script>';
        $a_stylesheets[] = '<script src="' . $tmp_dir . 'js_my-bookmarks.js"></script>';

        echo implode('', $a_stylesheets);
    }

    public function load_page() {
        $s_glyph = 'bookmark';
        $s_title = 'My Bookmarks';
        if( $this->_s_type == 'student' ) {
            $s_glyph = 'star';
            $s_title = 'My Favourites';
        }

        $a_body_html[] = '<div class="container-fluid">';
        $a_body_html[] = '<div class="row mt15">';
        $a_body_html[] = '<div class="col-xs-12"> <h2 class="glyphicon-heading text-left"> <span class="x2 glyphicon glyphicon-' . $s_glyph . ' hidden-xs"></span> <span class="glyphicon-heading-text">' . $s_title . '</span> </h2> </div>';
        $a_body_html[] = '</div>';
        $a_body_html[] = '</div>';

        $a_body_html[] = '<div class="container-fluid">';
        $a_body_html[] = '<div class="row"><div class="col-xs-12">';
        $a_body_html[] = '<div id="bookmarks-panel" class="panel panel-default">';
        $a_body_html[] = '<div class="panel-heading">';
        $a_body_html[] = '<i class="glyphicon glyphicon-bookmark"></i>';
        $a_body_html[] = '<div class="pull-right">';
        $a_body_html[] = '<div class="form-group btn-group delete-group" role="group">';
        $a_body_html[] = '<div class="btn-group">';
        $a_body_html[] = '<button type="button" data-toggle="modal" data-target="#remove-confirmation-modal" class="btn btn-primary btn-newest">Remove All Bookmarks</button>';
        $a_body_html[] = '</div>';
        $a_body_html[] = '</div>';
        $a_body_html[] = '<div class="form-group btn-group bookmark-group" role="group">';
        $a_body_html[] = '<div class="btn-group">';
        $a_body_html[] = '<button type="button" class="btn btn-filter btn-newest selected" value="ASC">Newest</button>';
        $a_body_html[] = '<button type="button" class="btn btn-filter btn-oldest" value="DESC">Oldest</button>';
        $a_body_html[] = '</div>';
        $a_body_html[] = '</div>';        
        $a_body_html[] = '</div>';
        $a_body_html[] = '</div>';
        $a_body_html[] = '<div class="panel-body">';
        $a_body_html[] = $this->load_bookmarks();
        $a_body_html[] = '</div>';
        $a_body_html[] = '</div><!-- End My Bookmarks Panel -->';
        $a_body_html[] = $this->add_delete_confirmation_modal();
        $a_body_html[] = '</div></div>';
        $a_body_html[] = '</div>';


        echo implode('', $a_body_html);
    }

    private function add_delete_confirmation_modal() {

        //Create HTML for a Confirmation Modal
        $a_modal[] = '<div class="modal fade" id="remove-confirmation-modal" tabindex="-1" role="dialog" aria-labelledby="ulc-label"
         aria-hidden="true">';
        $a_modal[] = '<div class="modal-dialog">';
        $a_modal[] = '<div class="modal-content">';
        $a_modal[] = '<div class="modal-header">';
        $a_modal[] = '<h3 class="modal-title" id="ulc-label">';
        $a_modal[] = 'Remove All Bookmarks';
        $a_modal[] = '<span class="pull-right">';
        $a_modal[] = '<button type="button" class="close close-xl" data-dismiss="modal" aria-label="Close">';
        $a_modal[] = '<span aria-hidden="true">&times;</span>';
        $a_modal[] = '</button>';
        $a_modal[] = '</span>';
        $a_modal[] = '</h3>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="modal-body">';
        $a_modal[] = '<div class="row">';
        $a_modal[] = '<div class="col-xs-12">';
        $a_modal[] = '<label>Doing this will remove all existing bookmarks from your account. Are you sure?</label>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="col-xs-5 col-xs-offset-1">';
        $a_modal[] = '<button data-dismiss="modal" class="btn btn-default btn-block">Cancel</button>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="col-xs-5">';
        $a_modal[] = '<button id="remove-bookmarks" data-dismiss="modal" class="btn btn-primary btn-block">Remove All Bookmarks</button>';
        $a_modal[] = '</div>';
        $a_modal[] = '<div class="clear"></div>';
        $a_modal[] = '</div><!-- END Modal Body -->';
        $a_modal[] = '</div><!-- END Modal Content -->';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';
        $a_modal[] = '</div>';

        return implode('', $a_modal);
    }

    public function get_book_list() {
        return $this->a_ids;
    }

    /* ---------- Initialise System ---------- */
    private function initialise_system() {
        global $wpdb;

        // ----- Step 1 ----- \\
        $this->_s_table = $wpdb->prefix . 'wushka_bookmarks';
        //Verify Table is installed, if not setup table
        if( $this->_s_table != $wpdb->get_var("SHOW TABLES LIKE '" . $this->_s_table . "'") ) {
            //table is not created. you may create the table here.
            $structure = 'CREATE TABLE IF NOT EXISTS ' . $this->_s_table .
                '(`ID` INT(13) NOT NULL AUTO_INCREMENT, ' .
                '`user_id` INT(13) NULL, ' .
                '`post_id` INT(13) NULL, ' .
                '`date_added` DATETIME NULL ' .
                'UNIQUE KEY `id` (ID) );';
            $wpdb->query($structure);
        }
    }

    /* ---------- Get User Bookmarks ---------- */
    // Returns Array of Post IDs of Books that are in the teachers collection
    private function get_user_bookmarks() {
        global $wpdb;
        //Check Teacher ID was passed
        if( ! isset($this->_i_user_id) ) {
            return FALSE;
        }

        $a_id[] = 'esiss_resource_id';
        $a_id[] = 'post_image';

        $a_id[]  = $this->_i_user_id;
        $s_param = '%d';
        if( isset($this->_i_link) && ! empty($this->_i_link) ) {
            $a_id[] = $this->_i_link;
            $s_param .= ',%d';
        }

        //Run Query To Gather Collection Books
        $s_query = 'SELECT bm.*, pm.meta_value as resource_id, pm2.meta_value as post_image ' .
            'FROM ' . $this->_s_table . ' bm ' .
            'LEFT JOIN '.$wpdb->prefix.'postmeta pm ON pm.post_id = bm.post_id AND pm.meta_key = %s ' .
            'LEFT JOIN '.$wpdb->prefix.'postmeta pm2 ON pm2.post_id = bm.post_id AND pm2.meta_key = %s ' .
            'WHERE bm.user_id IN(' . $s_param . ') ORDER BY bm.date_added DESC';

        $a_results = $wpdb->get_results($wpdb->prepare($s_query, $a_id));

        error_log('Found ' . count($a_results) . ' Bookmarks for User ' . $this->_i_user_id);

        $this->_a_books = array();
        $this->a_ids    = array();

        if( ! empty($a_results) ) {
            $this->_a_books = $a_results;
            foreach( $a_results as $idx => $o_book ) {
                $this->a_ids[] = $o_book->post_id;
            }
        }

        return TRUE;
    }

    /* ---------- Toggle Book to Wushka Bookmarks ---------- */
    // Add or Remove a Book from User's Bookmarks
    public function toggle_book_to_bookmarks( $i_book_id = NULL ) {
        if( ! isset($this->_i_user_id, $i_book_id) ) {
            return FALSE;
        }
        error_log('------------- My Bookmarks --------------');
        error_log('Toggle book in Wushka Bookmarks');
        //Check Book isn't Already in Collection
        if( in_array($i_book_id, $this->a_ids) ) {
            $b_delete = $this->remove_book($i_book_id);
            if( $b_delete === FALSE ) {
                error_log('----- Wushka Bookmarks Error -----');
                error_log('Book Removal Query Failed');
                error_log('--------------------------------');

                return FALSE;
            }
        } else {
            $b_insert = $this->add_book($i_book_id);
            if( $b_insert === FALSE ) {
                error_log('----- Wushka Bookmarks Error -----');
                error_log('Book Insert Query Failed');
                error_log('--------------------------------');

                return FALSE;
            }
        }
        //Return TRUE on success
        error_log('Successfully toggled Book from Bookmarks');
        error_log('-----------------------------------');

        return TRUE;
    }

    private function add_book( $i_book_id = NULL ) {
        if( ! isset($this->_i_user_id, $i_book_id) ) {
            return FALSE;
        }
        global $wpdb;

        $d_now  = new DateTime('NOW', new DateTimeZone('UTC'));
        $s_time = $d_now->format('Y-m-d G:i:s');

        //Book Isn't in Collection: Add
        error_log('Function: Add Book');
        $x_insert = $wpdb->insert(
            $this->_s_table,
            array(
                'user_id'    => $this->_i_user_id,
                'post_id'    => $i_book_id,
                'date_added' => $s_time
            ),
            array(
                '%d',
                '%d',
                '%s'
            )
        );

        return $x_insert;
    }

    private function remove_book( $i_book_id = NULL ) {
        if( ! isset($this->_i_user_id, $i_book_id) ) {
            return FALSE;
        }
        global $wpdb;
        //Book Is already in Collection: Remove
        error_log('Function: Remove Book from Bookmarks');

        $x_delete = $wpdb->delete(
            $this->_s_table,
            array(
                'user_id' => $this->_i_user_id,
                'post_id' => $i_book_id
            ),
            array(
                '%d',
                '%d'
            )
        );

        return $x_delete;
    }

    private function remove_all_books() {
        if( ! isset($this->_i_user_id, $i_book_id) ) {
            return FALSE;
        }

        if( ! empty($this->a_ids) ) {
            foreach( $this->a_ids as $idx => $book_id ) {
                $this->toggle_book_to_bookmarks($book_id);
            }
        }

        return TRUE;
    }


    public function add_button( $i_book_id = NULL ) {
        if( ! isset($this->_i_user_id, $i_book_id) ) {
            return FALSE;
        }

        $s_bookmark_value = 'Bookmark';
        $s_unmark_value   = 'Remove';

        $s_icon_class = in_array($i_book_id, $this->a_ids) ? 'starred' : NULL;

        $s_icon  = '<i class="glyphicon glyphicon glyphicon-bookmark ' . $s_icon_class . ' bookmark-glyph"></i>';
        $s_title = 'Bookmarks';
        $s_type  = NULL;
        if( $this->_s_type == 'student' ) {
            $s_bookmark_value = 'Favourite';
            $s_unmark_value   = 'Remove';
            $s_icon           = '<i class="glyphicon glyphicon glyphicon-star ' . $s_icon_class . ' bookmark-glyph"></i>';
            $s_title          = 'Favourites';
            $s_type           = 'favourite';
        }

        if( in_array($i_book_id, $this->a_ids) ) {
            $s_book_html = '<button type="button" class="btn btn-default btn-block btn-bookmark marked ' . $s_type . '" id="btn-bookmark-' . $i_book_id . '" title="Remove from your ' . $s_title . '">';
            $s_book_html .= $s_icon;
            $s_book_html .= '<span class="bookmark-label">' . $s_unmark_value . '</span>';
            $s_book_html .= '</button>';
        } else {
            $s_book_html = '<button type="button" class="btn btn-default btn-block btn-bookmark ' . $s_type . '" id="btn-bookmark-' . $i_book_id . '" title="Add to your ' . $s_title . '">';
            $s_book_html .= $s_icon;
            $s_book_html .= '<span class="bookmark-label">' . $s_bookmark_value . '</span>';
            $s_book_html .= '</button>';
        }

        $a_book_html[] = '<div class="my-bookmarks-wrap">';
        $a_book_html[] = $s_book_html;
        $a_book_html[] = '</div>';

        return implode('', $a_book_html);
    }

    public function add_overlay_button( $i_book_id = NULL ) {
        if( ! isset($this->_i_user_id, $i_book_id) ) {
            return FALSE;
        }

        $s_bookmark_value = 'Bookmark';
        $s_unmark_value   = 'Remove';

        $s_icon_class = in_array($i_book_id, $this->a_ids) ? 'starred' : NULL;

        $s_icon  = '<i class="glyphicon glyphicon glyphicon-bookmark ' . $s_icon_class . ' bookmark-glyph"></i>';
        $s_title = 'Bookmarks';
        $s_type  = NULL;
        if( $this->_s_type == 'student' ) {
            $s_bookmark_value = 'Favourite';
            $s_unmark_value   = 'Remove';
            $s_icon           = '<i class="glyphicon glyphicon glyphicon-star ' . $s_icon_class . ' bookmark-glyph"></i>';
            $s_title          = 'Favourites';
            $s_type           = 'favourite';
        }

        if( in_array($i_book_id, $this->a_ids) ) {
            $s_book_html = '<button type="button" class="btn btn-default btn-block btn-bookmark marked ' . $s_type . '" id="btn-bookmark-' . $i_book_id . '" title="Remove from your ' . $s_title . '">';
            $s_book_html .= $s_icon;
            $s_book_html .= '<span class="bookmark-label">' . $s_unmark_value . '</span>';
            $s_book_html .= '</button>';
        } else {
            $s_book_html = '<button type="button" class="btn btn-default btn-block btn-bookmark ' . $s_type . '" id="btn-bookmark-' . $i_book_id . '" title="Add to your ' . $s_title . '">';
            $s_book_html .= $s_icon;
            $s_book_html .= '<span class="bookmark-label">' . $s_bookmark_value . '</span>';
            $s_book_html .= '</button>';
        }

        $a_book_html[] = '<div class="my-bookmarks-wrap overlay-wrap">';
        $a_book_html[] = $s_book_html;
        $a_book_html[] = '</div>';

        return implode('', $a_book_html);
    }

    private function load_bookmarks() {
        if( $this->_b_status !== TRUE ) {
            return FALSE;
        }

        if( empty($this->_a_books) ) {
            $a_html[] = '<div class="bookmarks error-msg">';
            $a_html[] = '<p>It appears you don\'t have any bookmarks. Go to our Reading Boxes and select a reader to bookmark a reader.</p>';
            $a_html[] = '</div>';

            return implode('', $a_html);
        }

        // ----- Query Empty ------ \\
        if( empty($this->_a_books) ) {
            $a_html[] = '<div class="bookmarks error-msg">';
            $a_html[] = '<p>It appears you haven\'t yet marked any Readers. Go to our Reading Boxes and browse the Readers we have to offer!</p>';
            $a_html[] = '</div>';
        } else {
            foreach( $this->_a_books as $i_key => $o_book ) {
                $s_time = isset($o_book->date_added) ? strtotime($o_book->date_added) : '0';

                $a_book[] = '<div class="col-xsl-12 col-xs-6 col-sm-4 col-md-3 col-lg-2 wushka-bookmarks book-wrap" id="book-' . $o_book->post_id . '" data-added="' . $s_time . '">';
                $a_book[] = '<div class="book-wrap cover-wrap">';
                //	$a_book[] = '<input type="button" class="btn-cover" value="View" />';
                $a_book[] = $this->add_button($o_book->post_id);
                $a_book[] = '</div>';
                $a_book[] = '<input type="hidden" id="res-id-' . $o_book->resource_id . '" class="res-id" value="' . $o_book->resource_id . '" />';
                $a_book[] = '<a href="' . get_permalink($o_book->post_id) . '">';
                $a_book[] = '<img src="' . $o_book->post_image . '" class="book-thumb img-responsive" alt="' . get_the_title($o_book->post_id) . '"/>';
                $a_book[] = '</a>';
                $a_book[] = '</div>';
                //Save to Array
                $a_html[] = implode('', $a_book);
                unset($a_book);
            }

            array_unshift($a_html, '<div class="bookmark-wrap">');
            $a_html[] = '</div>';

        }

        return implode('', $a_html);
    }
}

/* --- END OF FILE --- */