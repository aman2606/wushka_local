<?php get_header(); ?>
	<div class="singlepage">
		<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('page-wrapper'); ?>>
			<?php wp_reset_query(); ?>
			<div class="page-content">
				<?php
					the_content();
					wp_link_pages( array( 'before' => '<p><strong>' . __('Pages:', 'lessonzone') . '</strong>', 'after' => '</p>' ) );
					edit_post_link(__('Edit Page', 'lessonzone'),'<div class="edit-link"><p class="text-center">','</p></div>');
				?>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
<?php
include 'dashboard_options.php';
get_footer();
?>