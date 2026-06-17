<?php
/* Template Name: - Website Template */
?>
<?php get_header(); ?>
	<div class="singlepage">
		<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('page-wrapper'); ?>>
			<div class="page-content">
				<?php the_content(); ?>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
<?php get_footer(); ?>