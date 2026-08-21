<?php
if( ! defined('ABSPATH') ) {
    exit(); // Exit if accessed directly
}
/* ------------ Free Sample Books --------------- */
class Wushka_Carousel {
    private $_a_finished;
    public function __construct( $a_finished = array() ) {
        if (!isset($_SESSION)) {
            session_start();
        }

        $this->_a_finished = $a_finished;
    }
    public function get_free_samples() {
        $args          = array(
            'post_type'      => 'ebook',
            'orderby'        => 'post_title',
            'posts_per_page' => 4,
            'meta_key'       => 'esiss_free_sample',
            'meta_value'     => 'Y',
            'post_status'    => 'publish'
        );
        $a_sample_html = NULL;
        $o_samples     = get_posts($args);
        if( isset($o_samples) || ! empty($o_samples) ) {
            return $o_samples;
        }
        return NULL;
    }
    public function get_free_homepage_samples() {
        $args          = array(
            'post_type'      => 'ebook',
            'orderby'        => 'post_title',
            'posts_per_page' => 4,
            'meta_key'       => 'homepage_free_sample',
            'meta_value'     => 'Y',
            'post_status'    => 'publish'
        );
        $a_sample_html = NULL;
        $o_samples     = get_posts($args);
        if( isset($o_samples) || ! empty($o_samples) ) {
            return $o_samples;
        }
        return NULL;
    }
    public function get_free_decodable_samples() {
        $args          = array(
            'post_type'      => 'ebook',
            'orderby'        => 'post_title',
            'posts_per_page' => 4,
            'meta_key'       => 'decodable_free_sample',
            'meta_value'     => 'Y',
            'post_status'    => 'publish'
        );
        $a_sample_html = NULL;
        $o_samples     = get_posts($args);
        if( isset($o_samples) || ! empty($o_samples) ) {
            return $o_samples;
        }
        return NULL;
    }
    public function get_free_levelled_samples() {
        $args          = array(
            'post_type'      => 'ebook',
            'orderby'        => 'post_title',
            'posts_per_page' => 4,
            'meta_key'       => 'levelled_free_sample',
            'meta_value'     => 'Y',
            'post_status'    => 'publish'
        );
        $a_sample_html = NULL;
        $o_samples     = get_posts($args);
        if( isset($o_samples) || ! empty($o_samples) ) {
            return $o_samples;
        }
        return NULL;
    }
    public function get_carousel_books( $a_books = array() ) {
        if( ! isset($a_books) || empty($a_books) ) {
            return array();
        }
        $args = array(
            'include'        => $a_books,
            'post_type'      => 'ebook',
            'orderby'        => 'date',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'post__in'
        );
        $o_samples = get_posts($args);
        if( isset($o_samples) || ! empty($o_samples) ) {
            return $o_samples;
        }
        return array();
    }
    public function build_sample_carousel( $a_books = array(), $i_per_page = 4 ) {
        $a_response = array();
        $a_items    = $this->sort_books($a_books, $i_per_page);
        $i_current = isset($_SESSION['carousel-taxo-samples']) ? $_SESSION['carousel-taxo-samples'] : 0;
        $a_items_html = array();
        if( isset($a_items) && ! empty($a_items) ) {
            foreach( $a_items as $i_pos => $a_item ) {
                $s_active = ($i_current == $i_pos) ? 'active' : NULL;
                $a_item_books = array();
                foreach( $a_item as $o_book ) {
                    $s_book         = $this->single_book($o_book, 'sample');
                    $a_item_books[] = $s_book;
                    unset($s_book);
                }
                $a_item_html[] = '<div class="item ' . $s_active . '">';
                $a_item_html[] = '<div class="row">';
                $a_item_html[] = implode('', $a_item_books);
                $a_item_html[] = '</div>';
                $a_item_html[] = '</div>';
                $a_items_html[] = implode('', $a_item_html);
                unset($a_item_html, $a_item_books);
            }
        } else {
            $a_items_html[] = $this->empty_item();
        }
        $a_response[] = '<div class="carousel-inner">';
        $a_response[] = implode('', $a_items_html);
        $a_response[] = '</div><!--END INNER-->';
        if( count($a_items) > 1 ) {
            $a_response[] = $this->pagination('samples');
        }
        $a_items_html = null;
        $a_items = null;
        return $a_response;
    }
    public function build_carousel( $i_id = 0, $a_books = array(), $i_per_page = 6 ) {
        $a_response = array();
        $a_items    = $this->sort_books($a_books, $i_per_page);
        $i_current = isset($_SESSION[ 'carousel-taxo-' . $i_id ]) ? $_SESSION[ 'carousel-taxo-' . $i_id ] : 0;
        $a_carousel_books = array();
        $a_expanded_books = array();
        if( isset($a_items) && ! empty($a_items) ) {
            foreach( $a_items as $i_pos => $a_item ) {
                $s_active = ($i_current == $i_pos) ? 'active' : NULL;
                $a_item_books = array();
                foreach( $a_item as $o_book ) {
                    $s_book             = $this->single_book($o_book);
                    $a_item_books[]     = $s_book;
                    $a_expanded_books[] = $s_book;
                    unset($s_book);
                }
                $a_item_html[] = '<div class="item ' . $s_active . '">';
                $a_item_html[] = '<div class="row">';
                $a_item_html[] = implode('', $a_item_books);
                $a_item_html[] = '</div>';
                $a_item_html[] = '</div>';
                $a_carousel_books[] = implode('', $a_item_html);
                unset($a_item_html, $a_item_books);
            }
        }
        $a_carousel = array();
        $a_expanded = array();
        //Collapsed Carousel
        $a_carousel[] = '<div class="carousel-inner">';
        $a_carousel[] = (empty($a_carousel_books)) ? $this->empty_item() : implode('', $a_carousel_books);
        $a_carousel[] = '</div><!--END INNER-->';
        if( count($a_items) > 1 ) {
            $a_carousel[] = $this->pagination($i_id);
        }
        //Expanded
        $a_expanded[] = '<div class="carousel-inner">';
        $a_expanded[] = (empty($a_expanded_books)) ? $this->empty_item() : implode('', $a_expanded_books);
        $a_expanded[] = '</div><!--END INNER-->';
        $a_response['carousel'] = $a_carousel;
        $a_response['expanded'] = $a_expanded;
        return $a_response;
    }
    private function sort_books( $a_books = array(), $i_per_page = 6 ) {
        $a_items = array();
        $i_item  = 0;
        $i_count = 1;
        foreach( $a_books as $o_book ) {
            $a_items[ $i_item ][] = $o_book;
            if( $i_count >= $i_per_page ) {
                $i_item++;
                $i_count = 1;
            } else {
                $i_count++;
            }
        }
        return $a_items;
    }
    public function single_book( $o_book = NULL, $s_type = 'group' ) {
        if( ! isset($o_book) || empty($o_book) ) {
            return NULL;
        }
        $i_id      = $o_book->ID;
        $s_classes = 'col-xs-4 col-sm-2';
        $s_width   = '200px';
        $s_height  = '284px';
        $s_id      = 'book-' . $o_book->ID;
        $s_link    = '<a href="#">';
        switch( $s_type ) {
            case 'group' :
                $s_classes  = 'col-xs-4 col-sm-2';
                $s_link     = '<a href="' . get_permalink($i_id) . '" data-id="' . $i_id . '" class="item-detail link-' . $i_id . '" aria-label="'. get_the_title($i_id) .'">';
                $size       = get_post_meta($o_book->_thumbnail_id, 'size_info', TRUE);
                if(!$size)
                {
                    $size['width'] = rtrim($s_width, 'px'); 
                    $size['height'] = rtrim($s_height, 'px'); 
                }
                $i_multiple = $size['width'] * $size['height'];
                $s_height   = '284px';
                if( $i_multiple > 0 ) {
                    $s_height = round(200 / $i_multiple) . 'px';
                }
                break;
            case 'sample' :
                $s_link    = '<a href="#" id="wushka-sample-' . $i_id . '" class="item-detail link-' . $i_id . ' wushka-sample" data-toggle="modal" data-target="#ereader-modal">';
                $s_classes = 'col-xs-4';
                $s_width   = 'auto';
                $s_height  = 'auto';
                break;
        }
        $s_code = wushka_load_sample_reader_urls($i_id);
        $s_img  = $o_book->post_image;
        //Read?
        $b_read = FALSE;
//      if ( in_array($o_book->esiss_resource_id, $this->_a_finished ) ) {
        if( isset($this->_a_finished[ $o_book->esiss_resource_id ]) ) {
            $b_read = TRUE;
        }
        //Thumbnail
        $size       = get_post_meta($o_book->_thumbnail_id, 'size_info', TRUE);
        if(!$size)
        {
            $size['width'] = rtrim($s_width, 'px'); 
            $size['height'] = rtrim($s_height, 'px'); 
        }
        $i_multiple = $size['width'] * $size['height'];
        $new_height = 284;
        if( $i_multiple > 0 ) {
            $new_height = round(200 / $i_multiple);
        }
        $a_book[] = '<div class="thumb accordion-shelf-book ' . $s_classes . ' text-center">';
        $a_book[] = $s_link;
        $a_book[] = '<input type="hidden" class="wsh_a" value="' . $o_book->esiss_resource_id . '" />';
        $a_book[] = '<input type="hidden" class="wsh_b" value="' . $s_code . '" />';
        $a_book[] = '<span class="bookshelf-item-wrapper">';
        // $a_book[] = '<span class="glyphicon glyphicon-play-button x3 btn-glyphicon-sample-play"></span>';
        $a_book[] = ($b_read) ? '<img src="//cdn6.wushka.com.au/Resources/wk-sash-read.png" width="65" height="65" alt="" class="sash-ebook-readit">' : NULL;
        $a_book[] = '<input type="hidden" class="img-source" value="'.$s_img.'" />';
        $a_book[] = '<img class="img-responsive img-rounded" style="width:' . $s_width . ';height:' . $s_height . ';" alt="' . $o_book->post_title . '" src="'.$s_img.'"/>';
        $a_book[] = ($b_read) ? '<span class="times-read bottom pull-right">' . $this->_a_finished[ $o_book->esiss_resource_id ] . 'x</span>' : NULL;
        $a_book[] = '</span>';
        $a_book[] = '</a>';
        if( $s_type == 'sample' ) {
            //Get Book Reading Level
            $a_args  = array(
                'orderby' => 'slug',
                'order'   => 'ASC'
            );
            $a_terms = get_terms('reading-level', $a_args);
            if( isset($a_terms) && ! empty($a_terms) ) {
                foreach( $a_terms as $idx => $o_term ) {
                    if( has_term($o_term->term_id, 'reading-level', $o_book->ID) ) {
                        $a_book[] = '<div class="' . $o_term->slug . ' shelf-book-details level-details">';
                        $a_book[] = ucwords($o_term->name);
                        $a_book[] = '</div>';
                    }
                }
            }
        }
        $a_book[] = '</div>';
        return implode('', $a_book);
    }
    private function empty_item() {
        $a_empty[] = '<div class="item active">';
        $a_empty[] = '<div class="row" style="margin:0;text-align:center;">';
        $a_empty[] = '<label>This Carousel is Empty</label>';
        $a_empty[] = '</div>';
        $a_empty[] = '</div>';
        return implode('', $a_empty);
    }
    private function pagination( $s_tax_id = 0 ) {
        $s_pagination = '<a class="left carousel-control bg-frontpage" href="#carousel-taxo-' . $s_tax_id . '" data-slide="prev">' .
            '<span class="arrow-left-wrapper">
                <span class="glyphicon glyphicon-chevron-left x2 library-arrow left"></span>
            </span>' .
            '</a>' .
            '<a class="right carousel-control bg-frontpage" href="#carousel-taxo-' . $s_tax_id . '" data-slide="next">' .
            '<span class="arrow-right-wrapper">
                <span class="glyphicon glyphicon-chevron-right x2 library-arrow right"></span>
            </span>' .
            '</a>';
        return $s_pagination;
    }
    //Optimization
    public function generate_carousel($i_id,$o_group,$a_carousel,$slug,$i_percentage)
    {
        $slug0 = '';
        if(isset($slug[0])){
            $slug0 = ucwords($slug[0]);
        }

        $a_library =
            '<div class="shelf-wrapper">'.
            '<div class="container-fluid">'.
            '<div class="row">'.
            '<div class="col-sm-12">'.
            '<div id="expand-group-'.$i_id.'" class="wk-panel-sheld expand">'.
            '<div class="panel panel-default panel-'.$i_id.' panel-reading-group">'.
            '<div class="carousel slide" id="carousel-taxo-'.$i_id.'">'.
            '<div class="panel-heading">'.
            '<i class="glyphicon glyphicon-inbox bookshelf-glyphicon"></i> Reading Group: '.ucwords($o_group->group_name).
            '<span class="pull-right">'.
            '<a role="button" tabindex="0" class="btn btn-small btn-shelf-expand" href="#collapse-group-'.$i_id.'" data-toggle="collapse" data-parent="#accordion">'.
            '<span class="book-count sr-only hidden-xs hidden-sm">Open this Box</span>'.
            '<span class="glyphicon glyphicon-circle-plus bookshelf-glyphicon "></span>'.
            '</a>'.
            '</span>'.
            '<span class="clearfix"></span>'.
            '</div>'.
            '<div class="panel-body">'.
            implode('', $a_carousel['carousel']).
            '</div>'.
            '<div class="panel-footer">'.
            '<div class="progress-label">'.$slug0.' Readers Completed</div>'.
            '<div class="progress">'.
            '<div class="progress-bar reading-group" role="progressbar" aria-label="reading-group" aria-valuenow="'.$i_percentage.'" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: '.$i_percentage.'%">'.$i_percentage.'%</div>'.
            '</div>'.
            '</div>'.
            '</div>'.
            '</div>'.
            '</div>'. //END EXPANDED
            '<div id="collapse-group-'.$i_id.'" class="wk-panel-sheld collapse">'.
            '<div class="panel panel-default panel-'.$i_id.' panel-reading-group">'.
            '<div class="panel-heading">'.
            '<i class="glyphicon glyphicon-inbox bookshelf-glyphicon"></i>'.ucwords($o_group->group_name).
            '<span class="pull-right">'.
            '<a role="button" tabindex="0" class="btn btn-small btn-shelf-close-bottom" href="#collapse-group-'.$i_id.'" data-toggle="collapse" data-parent="#accordion">'.
            '<span class="book-count hidden-xs hidden-sm">Close this Box</span>'.
            '<span class="glyphicon glyphicon-circle-minus bookshelf-glyphicon "></span>'.
            '</a>'.
            '</span>'.
            '<span class="clearfix"></span>'.
            '</div>'.
            '<div class="panel-body">'.
            implode('', $a_carousel['carousel']).
            '</div>'.
            '<div class="panel-footer">'.
            '<div class="progress-label">'.$slug0.' Readers Completed</div>'.
            '<div class="progress">'.
            '<div class="progress-bar reading-group" role="progressbar" aria-valuenow="'.$i_percentage.'" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: '.$i_percentage.'%">'.$i_percentage.'%</div>'.
            '</div>'.
            '</div>'.
            '</div>'.
            '</div>'.
            '</div>'.
            '</div>'.
            '</div>'.
            '</div>';
        return $a_library;
    }
    public function get_book($a_groups,$i_group,$read_books)
    {
        if(! empty($a_groups) ) {
            //Reading Groups
            include_once 'reading-groups/class_reading-groups.php';
            $c_rg = new Reading_Groups();
            $library_html = NULL;
            foreach ( $a_groups as $idx => $o_group ) {
                if ( $idx > 0 ) {
                    continue;
                }
                $i_id = $o_group->ID;
                $a_books = array();
                if(( $x_books = $c_rg->get_books($o_group->ID) ) !== FALSE ) {
                    foreach( $x_books as $o_bk ){
                        if ( (int)$o_bk->active == 1 ) {
                            $a_books[] = $o_bk->post_id;
                        }
                    }    
                }
                $a_posts = $this->get_carousel_books($a_books);
                $a_carousel = $this->build_carousel($i_id, $a_posts, 6);
                $i_count  = 0;
                $i_finished     = 0;
                $i_percentage   = 0;
                if(!empty($a_posts)){
                    $i_count = count($a_posts);
                    foreach( $a_posts as $ix => $o_post ) {
                        if (isset($this->_a_finished[$o_post->esiss_resource_id])){
                            $i_finished++;
                        }
                    }
                }
                $i_percentage =  (!$i_count)? $i_count : round(($i_finished / $i_count) * 100);
                if( is_nan($i_percentage) ) $i_percentage = 0; 
                $library_html = $this->generate_carousel($i_id,$o_group,$a_carousel,$i_finished,$i_percentage);
            }
            return $library_html;
        }
    }
    public function get_reading_groups()
    {
        global $wpdb;
        $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $country = strtoupper(end(explode('.', $url)));
        /*
        if ($url == 'COM') {
            $country = 'US';
            $results = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "lessonzone_languagecode WHERE NAME_CODE = '" . $country . "'");
        }
        */
        $results = $wpdb->get_row("SELECT * FROM " . $wpdb->prefix . "lessonzone_languagecode WHERE NAME_CODE = '" . $country . "'");
        $args = array(
            'post_type' => 'ebook',
            'post_status' => 'publish',
            //      'reading-level' => $slug[1],
            'posts_per_page' => -1
        );
        $posts = get_posts($args);
        return $posts;
    }
}
/* ----- END OF FILE ----- */