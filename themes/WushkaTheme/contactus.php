<?php
  /* Template Name: Contact Template*/
  get_header();
?>
<div class="contact-wrap">
    <div id="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="hero-title">Contact Us</h2>
                </div>
            </div>
        </div>
    </div>
    <section class="contact-wrapper container-wrapper contact-form-container" id="main-content">
        <div class="container">
            <h2 class="sr-only">Contact Us</h2>
            
            <?php   get_template_part('template-parts/content', 'contactform');    ?>

            <?php
            /*
            if ( have_posts() ) : while ( have_posts() ) : the_post();
            $content = get_the_content();
            $content = do_shortcode($content);
            $content = preg_replace( "/type=['\"]text\/(javascript|css)['\"]/", '', $content );
            echo $content;
            endwhile; endif;  
            */
            ?>
        </div>
    </section>
</div>

<?php get_footer(); ?>