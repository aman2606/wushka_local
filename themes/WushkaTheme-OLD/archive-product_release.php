<?php
  get_header();
  $posts_per_page = get_option('posts_per_page');
?>

<div class="product-release-container">
    <div id="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="hero-title">What&apos;s New in Wushka</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="product-release-list mt100 mb100">
        <div class="container">
            <div class="row">
                <?php 
                    if ( get_query_var('paged') ) {
                        $paged = get_query_var('paged');
                    } else if ( get_query_var('page') ) {
                        $paged = get_query_var('page');
                    } else {
                        $paged = 1;
                    }
                    $args = array( 
                        'post_type' => 'product_release',
                        'orderby' => 'publish_date',
                        'order'   => 'DESC',
                        'posts_per_page' => $posts_per_page,
                        'paged' => $paged
                                );
                    $query = new WP_Query( $args );
                    if($query->have_posts()) : 
                        while ($query->have_posts()) : $query->the_post();  

                        $product_title = get_the_title();
                        $product_content = get_the_content();
                        $product_permalink = get_the_permalink();
                ?> 
                <div class="col-xsl-12 col-xs-6 col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <a href="<?= $product_permalink; ?>" title="<?= $product_title; ?>">
                            <?php
                                if(has_post_thumbnail()){
                                    $featured_image_url = esc_url( remove_query_arg( ['AWSAccessKeyId', 'Expires', 'Signature'], get_the_post_thumbnail_url(get_the_ID(), 'full') ) );
                                    echo '<img src="'.$featured_image_url.'" class="img-responsive" alt="">';
                                }else{
                                    echo '<img src="https://cdn.nonprod.wushka.com.au/public/no-image-found.jpg" class="img-responsive" alt="">';
                                }
                            ?>
                            
                        </a>
                        <div class="caption">
                            <h3><?= $product_title; ?></h3>
                            <p>
                                <?=   shorten_string(wp_strip_all_tags($product_content),20);    ?>
                            </p>                            
                            <a href="<?= $product_permalink; ?>" title="Read more">Read more</a>
                        </div>
                    </div>
                </div>
                <?php  
                    endwhile;
                    wp_reset_postdata();
                    else:
                ?>
                <p>No Data Found</p>
                <?php
                    endif;
                ?>
            </div>
            
            <?php 
            if ( function_exists('bootstrap_pagination') ) {
                bootstrap_pagination( $query );
            } 
            ?> 
            
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $(".product-release-container .caption").equalHeights();
    });
</script>

<?php get_footer();