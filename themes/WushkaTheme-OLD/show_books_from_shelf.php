<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['selected_reading_group'])){
    $thisPost = '';
    $teacherId  =  $current_user->ID;
    $selected_group = $_POST['selected_reading_group']. '_books';
    $term = get_term_by('term_taxonomy_id', $_POST['id'],'reading-level',ARRAY_A);
    
    if($term['slug']) {
    $args = array(
        'post_type' => 'ebook',
        'reading-level' => $term['slug'],
        'orderby' => 'date',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );
    $the_query = new WP_Query($args);
    $current_work = get_user_meta( $teacherId, $selected_group,true);
    
    if ($the_query->get_posts()) {
       foreach($the_query->get_posts() as $idx => $post ) { 
           $imgsrc = get_post_meta($post, 'post_image', true);
           if($current_work && in_array($post, $current_work)){
                   $thisPost .= '<div id="book-'. $post.'" class="level-books thumb added" >';
                 
            } else {
               $thisPost .= '<div id="book-'. $post.'" class="level-books thumb" >';
            }
            $excerpt = lzSphinx_content_to_excerpt(get_post_field('post_content', $post),get_permalink());
           $thisPost .= '<div class="accordion-shelf-book col-xs-4 col-sm-2">';
         // $thisPost .=   '<div class=""><input type="checkbox" name="select" class="post-'. $post.'" value=""/></div> <br/>';
         //  $thisPost .= '<a href="' . get_permalink() . '" class="item-detail link-'. $post .'">';
           $thisPost .= '<div class="thumb-holder"><img class="img-responsive" alt="'. get_the_title($post) .'" src="' . $imgsrc .'" style="width:200px; height:267px;"/></div>';
           $thisPost .= '<div class="accordion-shelf-book-content"><a href="' . get_permalink() . '"><div class="thumbtitle">'. get_the_title($post) .'</div></a>';
           //$thisPost .= get_post_field('post_content', $post) ;
           $thisPost .= $excerpt;
           $thisPost .= '<div class="masonry-meta"><div class="masonry-meta-year-level">' . get_the_term_list($post, 'year-level', '<span>Year: </span> ', ', ', '') .'</div>';
           $thisPost .= '<div class="masonry-meta-reading-level">'. get_the_term_list($post, 'reading-level', '<span>Reading Level: </span> ', ', ', '').'</div>';
           $thisPost .= '<div class="masonry-meta-fiction"><span>Fiction/Nonfiction: </span>'. get_post_meta($post, 'esiss_fiction', true) .'</div>';
           $thisPost .= '<div class="masonry-meta-page-count"><span>Page Count: </span>'. get_post_meta($post, 'esiss_page_count', true). '</div>';
           $thisPost .= '<div class="masonry-meta-series">'. get_the_term_list($post, 'series', '<span>Series: </span> ', ', ', '').'</div></div></div>';
          
           $thisPost .=  '</a></div> </div>';
       } 
    }    
//    $thisPost .= '<div id="navigation">';
//    $thisPost .= '<ul class="pager">';
//    $thisPost .= '<li id="navigation-next"><a href="http://dev2.lessonzone.com.au/reading-group/2"></a></li>';
//    $thisPost .= '<li id="navigation-previous">'. get_previous_posts_link(__('&laquo; Previous', 'lessonzone')) .'</li>';
//    $thisPost .= '</ul></div>';
    //     error_log('** $meta_key ** ' . print_r( get_next_posts_link('', $the_query->max_num_pages ),true));
    echo $thisPost;
    //   wp_reset_postdata();

}
}
    ?>