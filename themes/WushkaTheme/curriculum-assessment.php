<?php
  /* Template Name: Curriculum and Assessment Template*/
  get_header();
?>
<div class="curriculum-wrap">
    <div class="bubbles">
        <div class="b1">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/webp/b-green-orange.webp"
                    type="image/webp">
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png"
                    type="image/jpeg">
                <img src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/b-green-orange.png" alt="">
            </picture>
        </div>
        <div class="b2">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/webp/orange-oval.webp"
                    type="image/webp">
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/orange-oval.png"
                    type="image/jpeg">
                <img src="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/orange-oval.png" alt="">
            </picture>
        </div>
    </div>
    <div id="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <h2 class="hero-title">Curriculum and Assessment</h2>
                </div>
            </div>
        </div>
    </div>
    <section class="container-wrapper">
        <div class="container">
            <div class="curriculum-assessment">
                <div class="single-curriculum row"> 
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <picture>
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/webp/national-curriculum-links.webp"
                                type="image/webp">
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/national-curriculum-links.jpg"
                                type="image/jpeg">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/national-curriculum-links.jpg"
                                alt="" class="curriculum-thumb">
                        </picture>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <h2 class="sub-heading">National Curriculum Links</h2>
                        <p class="para">In this document we have highlighted the threads within each sub-strand of the
                            National English Curriculum that are covered by the readers and support resources in the
                            Wushka Levelled Library.</p>
                        <a href="#"
                            onclick="javascript:window.open('<?= getCdnLink(); ?>/Resources/National-English-Curriculum-Links.pdf', '_blank'); return false;"
                            class="btn btn-blue download-btn"><img
                                src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/download.svg"
                                alt="" class="download-icon" /> Download</a>
                    </div>
                </div>
                <div class="single-curriculum row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <picture>
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/webp/literacy-progress-markers-reading-box.webp"
                                type="image/webp">
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/literacy-progress-markers-reading-box.jpg"
                                type="image/jpeg">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/literacy-progress-markers-reading-box.jpg"
                                alt="" class="curriculum-thumb">
                        </picture>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <h2 class="sub-heading">Literacy Progress Markers by Reading Box</h2>
                        <p class="para">This document details the progress markers students should be achieving before
                            moving on to succeeding reading levels in the Wushka Levelled Library. Progress markers are
                            included for Print Concepts, Reading, Vocabulary Knowledge and Comprehension.</p>
                        <a href="#"
                            onclick="javascript:window.open('<?= getCdnLink(); ?>/Resources/Literacy-Progress-Markers-by-Reading-Box.pdf', '_blank');  return false;"
                            class="btn btn-blue download-btn"><img
                                src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/download.svg"
                                alt="" class="download-icon" /> Download</a>
                    </div>
                </div>
                <div class="single-curriculum row">
                    <div class="col-sm-12 col-md-3 col-lg-3">
                        <picture>
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/webp/support-materials-reading-assessments.webp"
                                type="image/webp">
                            <source
                                srcset="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/support-materials-reading-assessments.jpg"
                                type="image/jpeg">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/support-materials-reading-assessments.jpg"
                                alt="" class="curriculum-thumb">
                        </picture>
                    </div>
                    <div class="col-sm-12 col-md-9 col-lg-9">
                        <h2 class="sub-heading">Support Materials for Reading Assessments</h2>
                        <p class="para">This document details the support resources available in the Wushka Levelled
                            Library that can be used for assessment, such as reading records, comprehension assessments,
                            sequence Strips and word cards.</p>
                        <a href="#"
                            onclick="javascript:window.open('<?php echo get_template_directory_uri(); ?>/img/curriculum-assessment/support-materials-for-reading-assessments.pdf', '_blank'); return false;"
                            class="btn btn-blue download-btn"><img
                                src="<?php echo get_template_directory_uri(); ?>/img/helpful-resources/download.svg"
                                alt="" class="download-icon" /> Download</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php get_footer(); ?>