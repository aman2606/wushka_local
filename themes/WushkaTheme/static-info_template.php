<?php
/*
Template Name: Static info
*/
?>
<?php get_header(); ?>
<div class="container">
  <div class="row whitesmoke">
    <?php while (have_posts()) : the_post(); ?>
    	<div id="post-<?php the_ID(); ?>" class="<?php post_class('post-wrapper'); ?>" >
      		<div class="post-content">
        		<?php the_content(); ?>
      		</div>
    	</div>
    <?php endwhile; ?>
  </div>
</div>
<?php
include 'dashboard_options.php';
get_footer();
?>