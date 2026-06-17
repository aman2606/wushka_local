<?php
  /* Template Name: Reading Level Chart*/
  get_header();
?>
<div class="reading-wrap">
    <div class="bubbles">
        <div class="b1">
            <picture>
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-green-orange.webp" type="image/webp">
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" type="image/jpeg">
                <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" alt="bubbles">
            </picture>
        </div>
    </div>
    <div id="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="hero-title">Reading Level Chart</h2>
                </div>
            </div>
        </div>
    </div>
    <section class="container-wrapper">
        <div class="container">
            <div class="correlation-chart row">
                <div class="col-sm-12 col-md-3 col-lg-3">
                    <picture>
                        <source srcset="<?php echo get_template_directory_uri(); ?>/img/reading-level-correlation-chart.webp" type="image/webp">
                        <source srcset="<?php echo get_template_directory_uri(); ?>/img/reading-level-correlation-chart.png" type="image/jpeg">
                        <img src="<?php echo get_template_directory_uri(); ?>/img/reading-level-correlation-chart.png" alt="Reading Level Correlation Chart" class="reading-thumb">
                    </picture>
                </div>
                <div class="col-sm-12 col-md-9 col-lg-9">
                    <h2 class="sub-heading">Reading Level Correlation Chart</h2>
                    <p class="para">Wushka’s carefully levelled digital readers align with all common reading levelling
                        systems. Initially based on the New Zealand Colour Wheel levels, the chart below provides
                        correlations to other levelling systems, such as Reading Recovery.</p>
                    <a href="javascript:void(0);" onclick="javascript:window.open('<?php echo get_template_directory_uri(); ?>/img/wushka-reading-level-correlation-chart.pdf','_blank')" class="btn btn-blue download-btn">
                      <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/download.svg" alt="" class="download-icon"/> Download</a>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>