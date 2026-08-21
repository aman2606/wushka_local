<?php
  /* Template Name: Parent resources */
  get_header();
  
?>
<link rel="stylesheet" href="/wp-content/themes/WushkaTheme/css/modaal.min.css" type="text/css" />


<main class="parent-resources-container">
    <div class="hero flex-center">
        <h2 class="text-center">
            <span class="heading-break">Using Wushka </span>
            <span class="heading-break">at Home</span>
        </h2>
    </div>

    <div class="main container">
        <p class="intro text-center">
            Wushka is a cloud-based digital reading program, which offers over 1000
            carefully levelled books to support students learning to read. Wushka’s
            highly regarded readers have been digitised with purpose-built features to
            enhance students’ reading experience. Wushka can be accessed on any
            device, making it perfect for school and home reading.
        </p>
 
        <section class="article-content">
            <div class="article-text">
                <h2>Student Dashboard</h2>
                <p>
                    The Student Dashboard is a simple dashboard featuring the reading
                    boxes and readers that have been assigned to the student by their
                    teacher. The dashboard also shows the readers that the student has
                    completed and the results of quizzes. Teachers set up a reading
                    profile for each student in their class and manage each student’s
                    Wushka account closely, assigning readers at the appropriate level
                    and tracking progress. Students log in to Wushka using the username
                    and password provided by their teacher. Teachers can switch reading
                    supports - such as narration - on or off depending on the needs of
                    individual students.
                </p>
            </div>
            <div class="flex-center">
                <picture>
                    <source
                        srcset="<?php echo get_template_directory_uri(); ?>/img/parent-resources/webp/student-dashboard.webp"
                        type="image/webp" />
                    <source
                        srcset="<?php echo get_template_directory_uri(); ?>/img/parent-resources/student-dashboard.png"
                        type="image/png" />
                    <img src="<?php echo get_template_directory_uri(); ?>/img/parent-resources/student-dashboard.png"
                        alt="Student Dashboard" data-name="student-dashboard" />
                </picture>
            </div>
        </section>

        <hr />

        <section class="resources">
            <h2 class="text-center">Resources</h2>
            <div class="resources-content">
                <a href="https://www.youtube.com/embed/jUbhVwx2fFw" id="support-child" class="video">
                    <div class="image-play flex-center">
                        <picture>
                            <source srcset="<?php echo get_template_directory_uri(); ?>/img/parent-resources/webp/digital-reading.webp" type="image/webp" />
                            <source srcset="<?php echo get_template_directory_uri(); ?>/img/parent-resources/digital-reading.png" type="image/png" />
                            <img src="<?php echo get_template_directory_uri(); ?>/img/parent-resources/digital-reading.png" alt="Supporting Your Child's Digital Reading" />
                        </picture>
                        <div class="play-icon">
                            <img src="<?php echo get_template_directory_uri(); ?>/img/parent-resources/play-icon.svg" alt="play" />
                        </div>
                    </div>
                </a>
                <div class="resources-text">
                    <h3>Supporting Your Child’s Digital Reading</h3>
                    <p>
                        This video explains how to log in to Wushka, how to open and read
                        books and how to keep track of your child’s reading progress. It
                        also offers advice on how to support your child at home, before,
                        during and after reading.
                    </p>
                    <a href="#" onclick="event.preventDefault(); javascript:$('#support-child').click();" class="btn-cta">
                        Press Play
                    </a>
                </div>
            </div>
            <div class="resources-content">
                <div class="flex-center">
                    <picture>
                        <source
                            srcset="<?php echo get_template_directory_uri(); ?>/img/parent-resources/webp/tips.webp"
                            type="image/webp" />
                        <source srcset="<?php echo get_template_directory_uri(); ?>/img/parent-resources/tips.png"
                            type="image/png" />
                        <img src="<?php echo get_template_directory_uri(); ?>/img/parent-resources/tips.png"
                            alt="" />
                    </picture>
                </div>
                <div class="resources-text">
                    <h3>Tips for Parents</h3>
                    <p>
                        We’ve created a printable Tips for Parents flyer which provides a
                        quick reference on how to log in and offers advice on reading with
                        students at home.
                    </p>
                    <a onclick="javascript:window.open('<?= getCdnLink(); ?>/Resources/tips-for-parents-wushka.pdf','_blank')" href="javascript:void(0);" class="btn-cta"> 
                        <span class="download-icon"></span>
                        Download
                    </a>
                </div>
            </div>
        </section>


        <section class="faqs">
            <div>
                <h2>FAQs for Parents</h2>
                <p>Click below to access FAQs for Parents</p>
                <a href="/frequently-asked-questions/?parent" class="btn-cta">Find Out More</a>
            </div>
            <div class="faqs-images">
                <picture>
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/parent-resources/extra/faqs-combined.webp" type="image/webp" />
                    <source srcset="<?php echo get_template_directory_uri(); ?>/img/parent-resources/extra/faqs-combined.png" type="image/png" />
                    <img src="<?php echo get_template_directory_uri(); ?>/img/parent-resources/extra/faqs-combined.png" alt="" />
                </picture>
            </div>

            <div class="clearfix"></div>
        </section> 
    </div>

    <div class="bubbles">
        <div class="b1">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/bubbles-green.webp"
                    type="image/webp" />
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-green.png"
                    type="image/png" />
                <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-green.png"
                    alt="" />
            </picture>
        </div>

        <div class="b2">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/bubbles-orange-small.webp"
                    type="image/webp" />
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-orange-small.png"
                    type="image/png" />
                <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-orange-small.png"
                    alt="" />
            </picture>
        </div>

        <div class="b3">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/bubbles-purple.webp"
                    type="image/webp" />
                <source srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-purple.png"
                    type="image/png" />
                <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-purple.png"
                    alt="" />
            </picture>
        </div>

        <div class="b4">
            <picture>
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/webp/bubbles-orange-large.webp"
                    type="image/webp" />
                <source
                    srcset="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-orange-large.png"
                    type="image/png" />
                <img src="<?php echo get_template_directory_uri(); ?>/img/how-it-works/extra/bubbles-orange-large.png"
                    alt="" />
            </picture>
        </div>
    </div>

    <div class="faqs-bg"></div>
</main>

<script src="/wp-content/themes/WushkaTheme/js/modaal.min.js"></script>
<script>
jQuery(document).ready(function() {
    jQuery(".video").modaal({
        type: "video",
    });
    if(window.location.hash === '#support-child'){
        jQuery('#support-child').click();
    }
});
</script>
<?php get_footer(); ?>