<?php
  /* Template Name: School Terms and Conditions Template*/
  get_header();
?> 
<div class="terms-wrap">  
	<div class="bubbles">
		<div class="b1">                                                          
			<picture>
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-green-orange.webp" type="image/webp">
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" type="image/jpeg"> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" alt="">
			</picture>
		</div>
		<div class="b2">                                                          
			<picture>
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b2-purple-s2.webp" type="image/webp">
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" type="image/jpeg"> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" alt="">
			</picture>
		</div>
		<div class="b3">                                                          
			<picture>
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-orange.webp" type="image/webp">
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-orange.png" type="image/jpeg"> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-orange.png" alt="">
			</picture>
		</div>
		<div class="b4">                                                          
			<picture>
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-green-orange.webp" type="image/webp">
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" type="image/jpeg"> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" alt="">
			</picture>
		</div>
		<div class="b5">                                                          
			<picture>
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/webp/bubbles-blue.webp" type="image/webp">
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-blue.png" type="image/jpeg"> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-blue.png" alt="">
			</picture>
		</div>
		<div class="b6">                                                          
			<picture>
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b2-purple-s2.webp" type="image/webp">
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" type="image/jpeg"> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b2-purple-s2.png" alt="">
			</picture>
		</div>
		<div class="b7">                                                          
			<picture>
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-orange.webp" type="image/webp">
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-orange.png" type="image/jpeg"> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-orange.png" alt="">
			</picture>
		</div>
		<div class="b8">                                                          
			<picture>
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/webp/bubbles-mix.webp" type="image/webp">
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-mix.png" type="image/jpeg"> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-mix.png" alt="">
			</picture>
		</div>
		<div class="b9">                                                          
			<picture>
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/webp/bubbles-blue.webp" type="image/webp">
				<source srcset="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-blue.png" type="image/jpeg"> 
				<img src="<?php echo get_template_directory_uri(); ?>/img/decodable-library/bubbles-blue.png" alt="">
			</picture>
		</div>
	</div> 
	<div id="hero">
		<div class="container">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<h2 class="hero-title">
						<?php the_title(); ?>
					</h2>
				</div>
			</div>
		</div>
	</div>
	<section class="container-wrapper" id="main-content">
		<div class="container">
			<?php the_content(); ?>
		</div>   
	</section>
</div>

<?php get_footer(); ?>