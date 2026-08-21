<?php
    get_header();

    add_filter( 'wp_lazy_loading_enabled', '__return_false' );
  
    add_filter( 'max_srcset_image_width', function ($max_width){
        return false; 
    } );

    add_filter( 'wp_calculate_image_srcset', function ( $sources ) {
        return false;
    } );

?>
<script>
    $(function() {
        $('.product-main-content img').each(function(){
            let img_width = $(this).attr('width');
            let img_height = $(this).attr('height');
            $(this).css({
                width: img_width,
                height: img_height
            });
        });

        if(jQuery('.product-main-content table').length > 0){

            jQuery('.product-main-content table').addClass("table");
            jQuery('.product-main-content table img').addClass("img-responsive");

        }
        
    });
</script>
<style>
.product-main-content table p {
    font-size: 1.6rem !important;
}

</style>
<div class="product-release-container">
    <div id="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="hero-title"><?= the_title(); ?></h2> 
                </div>
            </div>
        </div>
    </div>
    <div class="product-release-content mt100 mb100">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <?php 
                        if(has_post_thumbnail()){
                            echo '<div class="row">';
                                echo '<div class="col-md-8 col-md-offset-2">';
                                    $featured_image_url = esc_url( remove_query_arg( ['AWSAccessKeyId', 'Expires', 'Signature'], get_the_post_thumbnail_url(get_the_ID(), 'full') ) );
                                    echo '<img src="'.$featured_image_url.'" class="img-responsive center-block product-release-img" alt="">';
                                echo '</div>';
                            echo '</div>';
                        }
                    ?>
                    <div class="social-share">
                        <script>
                            $(document).ready(function() {
                                $(".social-share-link a").click(function() {
                                    var url = $(this).attr('href');
                                    window.open( url, '_blank','width=600, height=350, top=250, left= 400'); 
                                    return false;
                                });
                            });
                        </script>
                        <ul class="social-share-link">
                            <li><a href="<?=  share_link('facebook'); ?>" class="social-icons" target="_blank" rel="noopener noreferrer"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                            <li><a href="<?=  share_link('twitter'); ?>" class="social-icons" target="_blank" rel="noopener noreferrer"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                            <li><a href="<?=  share_link('linkedin'); ?>" class="social-icons" target="_blank" rel="noopener noreferrer"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            <li><a href="<?=  share_link('pinterest'); ?>" class="social-icons" target="_blank" rel="noopener noreferrer"><i class="fa fa-pinterest" aria-hidden="true"></i></a></li>
                            <li><a href="<?=  share_link('email'); ?>" class="social-icons" target="_blank" rel="noopener noreferrer"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
                        </ul>
                    </div>
                    <div class="product-main-content">
                        <?php
                            the_content();
                        ?>                    
                    </div>   
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <ul class="page-changer clearfix">
                        <?php
                            if(isset(get_next_post()->post_title)){ 
                                if (strlen(get_next_post()->post_title) > 0) {                  
                        ?>
                        <li class="float-left">
                            <a href="<?= get_permalink(get_adjacent_post(false,'',false)); ?>" rel="prev">
                                <span class="sr-only">Previous Post</span>
                                <span aria-hidden="true" class="nav-subtitle">Previous</span> 
                                <span class="nav-title">
                                    <span class="nav-title-icon-wrapper">
                                        <i class="fa fa-long-arrow-left"></i>
                                    </span>
                                    <?= shorten_string(get_next_post()->post_title, 5); ?>
                                </span>
                            </a>
                        </li>
                        <?php 
                                }
                            }
                            if(isset(get_previous_post()->post_title)){
                                if (strlen(get_previous_post()->post_title) > 0) {
                        ?>
                        <li class="float-right">
                            <a href="<?= get_permalink(get_adjacent_post(false,'',true)); ?>" rel="next">
                                <span class="sr-only">Next Post</span>
                                <span aria-hidden="true" class="nav-subtitle">Next</span> 
                                <span class="nav-title">
                                    <?= shorten_string(get_previous_post()->post_title, 5); ?>
                                    <span class="nav-title-icon-wrapper">
                                        <i class="fa fa-long-arrow-right"></i>
                                    </span>
                                </span>
                            </a>                    
                        </li>
                        <?php 
                            }
                        }
                        ?>
                    </ul> <!-- /.page-changer -->
                </div>
            </div>
        </div>
    </div>
</div>
                     
<?php get_footer();
