<?php get_header(); ?>
	<div class="singlepage">
		<?php while (have_posts()) : the_post(); ?>
		<div id="post-<?php the_ID(); ?>" <?php post_class('page-wrapper'); ?>>
			<?php wp_reset_query(); ?>
			<div class="page-content">
				<div class="container mt60">
					<div class="row">
						<div class="col-xs-12 col-md-9">
						<div class="row">
							<div class="col-xs-12">
								<?php the_title( '<h2 class="site-heading strong underline mb15">', '</h2>' ); ?>
							</div>
							<div class="col-xs-12">
								<?php
									the_content();
									edit_post_link(__('Edit Page', 'lessonzone'),'<div class="edit-link"><p class="text-center">','</p></div>');
								?>
								<div class="text-center mb15">
<?php if ( is_user_logged_in() ) { ?>
	<a href="/stories" rel="bookmark" title="<?php the_title_attribute(); ?>" class="btn btn-tertiary"> &larr; More Stories</a>
<?php } else { ?>
	<a href="/stories" rel="bookmark" title="<?php the_title_attribute(); ?>" class="text-link"> &larr; More Stories</a>
<?php } ?>
								</div>
							</div>
						</div>
						</div>
						<div class="col-xs-12 col-md-3 hidden">
							<?php include("share-story-box.php") ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endwhile; ?>
	</div>
<?php
include 'dashboard_options.php';
get_footer();
?>