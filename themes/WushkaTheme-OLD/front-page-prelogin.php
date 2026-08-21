<?php
/* Pre Login Front Page */
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly
include_once 'functions/wushka_carousels.php';
?> 
<?php /* CONDITIONAL HOMEPAGE DEPENDING ON COUNTRY Start --------------------------------------------------------*/
$classes = get_body_class();
if (in_array('page-wushka-australia-learning-read', $classes) || in_array('page-wushka-new-zealand-learning-read', $classes) || in_array('page-trial', $classes)) { ?>
    <!-- Start AUSTRALIA Front Page Copy ============================================================================ -->
    <section class="hero-image">
        <div class="container-fluid header-img">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>Digital Reading <br>Program for Beginning <br>to Fluent Readers</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-bubble-1" id="main-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-section-center">
                        <?php
                            $extension = pathinfo($_SERVER['SERVER_NAME'], PATHINFO_EXTENSION);  
                        ?>    
                        <p  class="intro-text">
                            Wushka is a<?php if($extension != "nz"){ ?>n Australian-developed,<?php } ?> cloud-based digital reading program, accessible from all
                            common browsers and devices.</p>
                        <p class="intro-text">Wushka offers two specialised digital reading libraries:</p>  
                    </div>
                </div>
                <div class="reading-library-container">
                    <div class="reading-libraries"> 
                        <a href="/levelled-library" title="Levelled Library">
                            <div class="row reading-library ">
                                <div class="col-md-10 col-lg-11 width-fix">
                                    <h2 class="text-center">
                                        <img src="<?=  getCdnLink(); ?>/Resources/Levelled-Library.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1600406226&Signature=P9dAE1SQ8CkHSyymRGi85JDA2zU%3D"
                                                alt=""> Levelled Library
                                    </h2>
                                    <hr>
                                    <p class="text-center">688 levelled digital readers</p>
                                </div>
                                <div class="col-md-2 chevron-container">
                                    <div class="chev">
                                        <img class="chevron-image chevron-grey" src="<?php echo get_template_directory_uri(); ?>/images/chevron.svg" alt>
                                        <img class="chevron-image chevron-white" src="<?php echo get_template_directory_uri(); ?>/images/chevron-white.svg" alt>
                                        <div class="circle-container">
                                            <div class="circle"></div>
                                        </div>

                                    </div>
                                </div>
                            </div> 
                        </a> 
                </div>
                <div class="reading-libraries">
                    <a href="/decodable-library">
                    <div class="row reading-library">

                        <div class="col-md-10 col-lg-11">
                            <h2 class="text-center">
                                <img src="<?=  getCdnLink(); ?>/Resources/Decodable-Library-smaller.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1600741525&Signature=QRUtxCEnFZaF3WI3TBWG8cjeUNM%3D"
                                        alt=""> Decodable Library
                            </h2>
                            <hr>
                            <p class="text-center">408 decodable digital readers</p>
                        </div>
                        <div class="col-md-2 chevron-container">
                            <div class="chev">
                                <img class="chevron-image" src="<?php echo get_template_directory_uri(); ?>/images/chevron.svg" alt>
                                <div class="circle-container">
                                    <div class="chev">
                                        <img class="chevron-image chevron-grey" src="<?php echo get_template_directory_uri(); ?>/images/chevron.svg" alt>
                                        <img class="chevron-image chevron-white" src="<?php echo get_template_directory_uri(); ?>/images/chevron-white.svg" alt>
                                        <div class="circle-container">
                                            <div class="circle"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    </a>
                </div>
            </div>

            <div class="col-md-12">
                <div class="trial-button">
                    <a class="home-popover navbar-btn btn btn-primary btn-free-trial-home subscription-offer" data-toggle="modal"
                       data-target="#wk-form-modal" href="#"><?= wushka_cta_button_text(); ?></a>
                </div>
            </div>
        </div>
        </div>
    </section>
    <section class="section-grey section-bubble-2">
        <div class="container">
            <div class="row digital-reading">
                <div class="col-md-12">
                    <h2 class="site-heading strong mb10">Complete Digital Reading Solution</h2>
                </div>
                <div class="col-md-4 reading-solution">
                    <img class="img-reading-solution center-block"
                         src="<?=  getCdnLink(); ?>/Resources/Wushka-Libraries.png" alt="">
                    <hr>
                    <h3 class="digital-title text-center"><strong>Wushka Libraries</strong></h3>
                    <p>Within our libraries, our <a href="/levelled" >Reading Boxes <span class="sr-only"> - Levelled Library </span></a> organise readers for students and teachers in the same
                        way readers would be
                        organised within classrooms - by reading level or by phonics phase - and books can be selected
                        and assigned to students
                        and reading groups. Students can be assigned readers within the 'just right' reading boxes and
                        levels for home reading,
                        providing supports such as optional highlighted text and narration.</p>
                </div>
                <div class="col-md-4 reading-solution">
                    <img class="img-reading-solution center-block"
                         src="<?=  getCdnLink(); ?>/Resources/Comprehensive-Teaching-Support.png" alt="">
                    <hr>
                    <h3 class="digital-title text-center"><strong>Comprehensive Teaching Support</strong></h3>
                    <p><a href="/resources"> Support materials</a> are provided for every reader including online comprehension quizzes, printable
                        lesson plans, literacy
                        activities, blackline masters, sequencing templates, discussion cards and printable take-home
                        books, both complete and wordless.
                        Assessment materials are also provided in the form of reading records.</p>
                </div>
                <div class="col-md-4 reading-solution">
                    <img class="img-reading-solution center-block"
                         src="<?=  getCdnLink(); ?>/Resources/Reading-Management-System.png" alt="">
                    <hr>
                    <h3 class="digital-title text-center"><strong>Reading Management System</strong></h3>
                    <p>Teachers can manage their own class and set individual student reading profiles for school and
                        reading, easily editing settings
                        such as reading levels, phonic phases, reading groups, access permissions and quiz and narration
                        options. Ongoing reading statistics
                        are available at individual student and whole-class level and provide detailed insights into
                        students' level of interaction,
                        comprehension and progression, both at school and at home.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="section-bubble-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="site-heading strong mb10">Sample Readers</h2>
                </div>

                <div class="col-xs-12 book-cards-container">
                    <a href="#" id="wushka-sample-797" class="item-detail link-797 wushka-sample book-card "
                       data-toggle="modal" data-target="#ereader-modal" title="Book - Look at my face">
                        <input type="hidden" class="wsh_a" value="200953">
                        <input type="hidden" class="wsh_b" value="200953E02">
                        <div class="book-wrapper">
                            <img src="<?=  getCdnLink(); ?>/Resources/look-at-my-face@2x.jpg"
                                 alt="Book - Look at my face" class="book no-hover">
                            <img src="<?=  getCdnLink(); ?>/Resources/play-icon.svg"
                                 alt="" class="play-icon">
                        </div>
                        <div class="card yellow">
                            <strong>Levelled Reader</strong>
                            <p class="card-title">Look at My Face</p>
                            <hr>
                            <div class="level-container">
                                <i class="fa fa-signal fa-lg"></i>

                                <p class="level-copy m0">
                                    Yellow - Levels 6-8
                                </p>
                            </div>
                        </div>
                    </a>

                    <a href="#" id="wushka-sample-293" class="item-detail link-293 wushka-sample book-card"
                       data-toggle="modal" data-target="#ereader-modal" title="Book - Dog on a Mat" aria-label="Book - Dog on a Mat">
                        <input type="hidden" id="wsh_a" value="300087">
                        <input type="hidden" id="wsh_b" value="300087E02">
                        <div class="book-wrapper">
                            <img src="<?=  getCdnLink(); ?>/Resources/dog-on-a-mat-2x.png"
                                 alt="Book - Dog on a Mat" class="book book-no-shadow">
                            <img src="https://dev.wushka.com.au/wp-content/themes/WushkaTheme/img/pages/play-icon.svg"
                                 alt="" class="play-icon">
                        </div>
                        <div class="card orange">
                            <strong>Decodable Reader</strong>
                            <p class="card-title">Dog on a Mat</p>
                            <hr>
                            <div class="level-container">
                                <i class="fa fa-signal fa-lg"></i>
                                <p class="level-copy m0">
                                    Phase 2 Set 1: s, a, t, p, i, n, m, d, g, o, c, k, ck, e, u, r, h, b, f, ff, l, ll, ss</p>
                            </div>
                        </div>
                    </a>


                </div>


            </div>
        </div>
    </section>
    <section class="section-blue section-join">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="site-heading strong mb10">Join the Thousands of Schools Already Using Wushka</h2>
                </div>
                <div class="icon-container row">
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="blu-icon-left">
                            <img src="<?=  getCdnLink(); ?>/Resources/students.png"
                                 alt="">
                        </div>
                        <div class="icon-text-left">
                            <h3>220k+</h3>
                            <p>students</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="blu-icon-left">
                            <img src="<?=  getCdnLink(); ?>/Resources/teachers.png"
                                 alt="">
                        </div>
                        <div class="icon-text-left">
                            <h3>15k+</h3>
                            <p>teachers</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="blu-icon-left">
                            <img src="<?=  getCdnLink(); ?>/Resources/4m-logins.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1600754623&Signature=22mYl1d7rsj4bl3vnb7jfYgoO0A%3D"
                                 alt="">
                        </div>
                        <div class="icon-text-left">
                            <h3>4m+</h3>
                            <p>student logins in 2020</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <div class="blu-icon-left">
                            <img src="<?=  getCdnLink(); ?>/Resources/calendar.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1600754626&Signature=PIvcgT7It0Gt3iebyPFo8KLI6Mg%3D"
                                 alt="">
                        </div>
                        <div class="icon-text-left">
                            <h3>120 years</h3>
                            <p>of reading in 12 months</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="trial-button">
                        <a class="home-popover navbar-btn btn btn-primary btn-free-trial-home subscription-offer" data-toggle="modal"
                           data-target="#wk-form-modal" href="#"><?= wushka_cta_button_text(); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-bubble-4">
        <div class="container-fluid ">
            <div class='row'>
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 text-center">
                            <h2 class="site-heading strong mb10">Testimonials</h2>
                        </div>
                    </div>
                    <div class="row">
                        <div class='col-md-12 text-center m-b-lg'>

                            <div class="testimonials">
                                <div class="testimonials-carousel">
                                    <div class="testimonial hidden">
                                        <img class="testimonial__logo"
                                             src="<?= getCdnLink(); ?>/Resources/testimonials-120.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1601365962&Signature=tC4KfKs8k6GcnTXjgKAdfRC9OVI%3D"
                                             alt="Profile Icon">
                                        <h2 class="testimonial__title">Lyn J</h2>
                                        <p>Wushka levelled reading is an excellent cloud based program that is being
                                            enjoyed by my 6/5 composite class. The program is easy to set up and
                                            provides me as the class teacher excellent information on what and how the
                                            students are reading. The variety of text is impressive and the lesson plans
                                            and blackline masters associated with the text are great.
                                            Students in my class are asking to do reading groups so they can logon to
                                            Wushka.</p>
                                    </div>
                                    <div class="testimonial active">
                                        <img class="testimonial__logo"
                                             src="<?= getCdnLink(); ?>/Resources/testimonials-120.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1601365962&Signature=tC4KfKs8k6GcnTXjgKAdfRC9OVI%3D"
                                             alt="Profile Icon">
                                        <h2 class="testimonial__title">Pauline H</h2>
                                        <p>Staff are really happy with the program as it has encouraged students
                                            disengaged in reading to be more enthusiastic in reading groups and during
                                            independent reading activities. The program also allows for students to
                                            choose from a very wide range of texts and genres.</p>
                                    </div>
                                    <div class="testimonial hidden">
                                        <img class="testimonial__logo"
                                             src="<?= getCdnLink(); ?>/Resources/testimonials-120.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1601365962&Signature=tC4KfKs8k6GcnTXjgKAdfRC9OVI%3D"
                                             alt="Profile Icon">
                                        <h2 class="testimonial__title">Tanya H</h2>
                                        <p>I personally like the way you can select books that are appropriate to each
                                            child’s reading level and they can check their own level of understanding of
                                            a text through receiving a score for questions answered after reading the
                                            book.</p>
                                    </div>
                                </div>

                                <div class="testimonials-controls">
                                    <input alt="previous" class="previous" type="image"
                                           src="<?= getCdnLink(); ?>/Resources/testimonial-arow-left.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1601367149&Signature=EeT%2F9UOU6zh3nO903NaaTWFNwj0%3D">

                                    <input alt="next" class="next" type="image"
                                           src="<?= getCdnLink(); ?>/Resources/testimonial-arow-right.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1601367150&Signature=YKLdH4Jj%2B5Zy4V7Vj8kLZz9aa3k%3D">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Start INTERNATIONAL Front Page Copy ==================================================================== -->
<?php } else if (in_array('page-wushka-international-learning-read', $classes)) { ?>
    <main class="tracks-wrapper">
        <div class="container-fluid">
            <div class="row no-gutter">
                <div class="col-sm-12">
                    <article class="track-wrapper school">
                        <svg id="wushka-logo-white-tagline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 95.2">
                            <style>.wk-tl0 {
                                    fill: #EC2027;
                                }

                                .wk-tl1 {
                                    fill: #FFFFFF;
                                }

                                .wk-tl2 {
                                    fill: #A8CE39;
                                }

                                .wk-tl3 {
                                    fill: #F79421;
                                }

                                .wk-tl4 {
                                    fill: #0854A3;
                                }

                                .wk-tl5 {
                                    fill: #7D287D;
                                }

                                .wk-tl6 {
                                    fill: #1EBAED;
                                }</style>
                            <path class="wk-tl0"
                                  d="M59.4 57c.5 2.7-2.8 5.6-7.3 6.3l-34.5 5.8c-4.5.7-8.5-.9-8.9-3.6l-8.2-49c-.5-2.7 2.8-5.6 7.3-6.3l34.5-5.8c4.5-.7 8.5.9 8.9 3.6l8.2 49z"/>
                            <path class="wk-tl1"
                                  d="M27.9 46c-.5 2.5-1 4.5-1.4 5.8-.4 1.2-.6 2.1-1 2.8-.2.5-.5 1-.7 1.4-.7 1-1.7 1.6-2.8 1.7-1 .1-2 0-2.7-.4-.9-.4-1.5-1-2-1.7s-1-1.9-1.2-3.1c0-.1-.1-1-.4-2.5-.1-.6-.2-1-.2-1.2l-4.2-11-3.8-9.9c-.4-1-.4-1.9 0-2.8s1.1-1.6 2-2c1-.4 1.9-.4 2.8 0 .9.4 1.6 1.1 2 2.1l6.7 18 .1-.2 1.5-7.3c.2-1.2.5-2.1.6-2.7.1-.4.2-.7.4-1.1.1-.6.4-1 .7-1.4.7-1.1 1.9-1.6 3.1-1.7 1.5 0 2.6.6 3.5 1.9.4.6.6 1.4.9 2.2.1.5.1.9.2 1.1 0 .4.1.6.1.7 0 .1 0 .2.1.4l3.2 9.2.5 1.2c.9-2.3 2.2-6.2 4-11.6 1.7-5.4 2.6-8.4 2.8-8.8.4-1 1-1.6 1.9-2.1s1.9-.5 2.8-.2c1 .4 1.6 1 2.1 1.9s.5 1.9.1 2.8c-.1.5-.9 2.7-2.1 6.7l-3.6 11c-1.1 3.3-2.1 5.8-2.8 7.2-.6 1.2-1.1 2-1.4 2.3-1.4 1.9-3.1 2.6-5.1 2.3-.9-.1-1.6-.5-2.2-1.2-.2-.2-.4-.4-.5-.6-.1-.2-.4-.5-.5-.9l-.9-2c-.5-1.4-1.2-3.2-2.1-5.6l-.5-.7z"/>
                            <path class="wk-tl2"
                                  d="M198.1 63.8c-.4 2.5-3.8 3.8-7.8 3.3L160 62.5c-4-.6-6.8-3.1-6.4-5.4l6.6-43c.4-2.5 3.8-3.8 7.8-3.3l30.3 4.7c4 .6 6.8 3.1 6.4 5.4l-6.6 42.9z"/>
                            <path class="wk-tl1"
                                  d="M176.6 46.4V57c0 1-.4 2-1.1 2.7-.7.7-1.6 1.1-2.7 1.1-1 0-2-.4-2.7-1.1S169 58.1 169 57v-9.6c0-3.2-.1-7.3-.2-12s-.1-14-.1-15.7c0-1 .4-2 1.1-2.7.7-.7 1.6-1.1 2.7-1.1 1 0 2 .4 2.7 1.1s1.1 1.6 1.1 2.7c0 1.9.1 11 .2 15.5 2-.6 4-.6 6.1-.1 1.7.5 3.5 1.4 4.9 2.6 1.5 1.2 2.6 2.7 3.3 4.5.4.9.7 1.9 1 2.8.2 1 .4 2.1.5 3.2 0 1.1.1 2.1.1 2.8s0 1.7-.1 2.8c-.1 1.2-.1 1.9-.1 2.1 0 1-.5 2-1.2 2.6-.7.7-1.7 1.1-2.7 1-1 0-2-.5-2.6-1.2-.7-.7-1.1-1.7-1-2.7 0-.6 0-1.4.1-2.5s.1-2 .1-2.5v-2c0-.7-.1-1.5-.2-2s-.2-1.1-.5-1.6c-.2-.6-.7-1.2-1.4-1.7s-1.2-.9-2-1c-1.2-.4-2.2 0-3 1 0 0-.1.2-.1.6l-.1.2c0 .1 0 .2-.1.4v.3c-.1.5-.2 1-.4 1.4l-.5.2z"/>
                            <path class="wk-tl3"
                                  d="M81.6 19.1c10.7 0 19.4 11 19.4 24.6s-8.7 24.6-19.4 24.6-19.4-11-19.4-24.6 8.7-24.6 19.4-24.6"/>
                            <path class="wk-tl1"
                                  d="M92.8 52.5c-.6-3-1-6.1-1.1-9.6v-8.3c0-1-.2-1.7-.7-2.5-.5-.7-1.4-1.1-2.2-1.1h-.1c-.9 0-1.9.5-2.3 1.2-.5.7-.9 1.6-1 2.7-.1.7-.1 1.9-.1 3.3 0 1.5 0 3.5.1 5.9V46c0 2.5-.5 4.3-1.4 5.6-1 1.5-2.2 2.1-4.1 2.2h-.1c-1.2 0-1.9-.4-2.6-1.6S76 49 76 46.1V44c0-2 .1-4 .2-6.2.2-2.8.4-4.6.4-5.2v-.1c0-.9-.4-1.6-1-2.1-.5-.5-1.4-.9-2.1-.9h-.1c-1 0-2 .5-2.6 1.4-.6.7-.9 1.7-1.1 3-.4 2.5-.5 5.3-.5 8.3 0 1.2 0 2.5.1 3.8.1 4.5 1 7.9 2.6 10.4s4.1 3.8 7.1 3.8h.6c3.1-.1 5.7-1.5 7.9-3.8.2.7.5 1.4.9 1.9.2.5.6.9 1 1.2.4.4 1 .6 1.5.6h.1c.9 0 1.7-.5 2.3-1.2.5-.6.9-1.5.7-2.3-.3.1-.8-2-1.2-4.1"/>
                            <path class="wk-tl4"
                                  d="M130.3 13.7c12.8.8 22.5 12.2 21.7 25.5s-11.9 23.4-24.7 22.6-22.5-12.2-21.7-25.5c.9-13.3 11.9-23.4 24.7-22.6"/>
                            <path class="wk-tl1"
                                  d="M127.4 26.7c-2.1.2-4.7 1.6-4.5 4.1.4 3.1 4.7 3.8 7.1 4.3 9.3 2 11 6.1 11.3 10.1.4 5.1-2.3 11.7-10.6 12.4-6.1.4-12.7-2.3-14.7-9-1-3.5 3.2-5.1 4.6-2.6 2 3.8 6.4 5.8 10.1 5.4 3.1-.2 4.7-2.8 4.5-5.6-.4-3.1-4.3-4-6.8-4.5-9.6-1.9-11.1-6.1-11.5-9.6-.4-3.6 1.4-9.9 10-10.8 6.2-.6 10.1 2.5 11 3.3 2.3 2.3.5 6.3-2.5 5.1-1.1-.3-3.6-3.1-8-2.6"/>
                            <path class="wk-tl5"
                                  d="M284.8 14.2c11.6 3.3 17.6 17.9 13.4 32.6s-16.9 24-28.5 20.7-17.6-17.9-13.4-32.6 17-24 28.5-20.7"/>
                            <path class="wk-tl1"
                                  d="M272.6 51.6c-2-.2-3.8-3.2-2.7-10.6 1.1-7 5.2-8.5 6.2-8.5s2.1.7 2.7 1.6c1.1 1.7.2 18.4-6.2 17.5m14.5 2.5c-.5-2.2-1.5-4-1.5-8.2-.1-4.1 1.2-8.4 1.7-13 0-.2.1-.7.2-1.4.1-.9-.1-2.3-1.4-3.3-.6-.5-1.5-.9-2.5-1-.5 0-1 0-1.5.1-.6.2-1.1.5-1.6 1-1.2-.9-2.7-1.5-4.3-1.6h-.4c-4-.2-7.1 2.1-9 4.6-2.5 3.2-4 7.2-4.3 11.3-.1 1.1-.1 2.2 0 3.3.2 2.2.9 4.8 2.2 6.9 1.4 2.1 3.5 4 6.4 4.5h.4c4.9.2 7.5-4.3 7.8-4.9 0 0 .4 1.2.5 1.6 1.5 4 4.1 3.8 5.4 3.1 1.7-1 2-1.8 1.9-3"/>
                            <path class="wk-tl6"
                                  d="M252.1 64c0 3.2-3.2 5.8-7.2 5.8l-30.2-.2c-4 0-7-2.7-7-5.9l.5-57.9c0-3.2 3.2-5.8 7.2-5.8l30.2.2c4 0 7.1 2.7 7.1 5.9l-.6 57.9z"/>
                            <path class="wk-tl1"
                                  d="M245.1 57.2c-1.9-1.4-2.6.6-3.8.6h-.1c-2.8 0-3.2-3.3-4.3-6.9-.1-.6-1.5-4.7-4.8-7.4-1-.7-2.1-1.1-3.3-1.1 1.1-1.9 1.4-2.3 5.2-9.5 1.5-2.8 2.5-4.5 3.7-7.2.5-1.1.2-2.1.1-2.5-.4-.7-1.1-1.4-1.9-1.6-.4-.1-.7-.1-1.2-.1s-.9.1-1.2.4c-.6.4-1.2 1-1.6 1.7-1.5 3.6-5.8 10.4-7.9 14.1-.2-2.1-.6-5.3-1-8.5-.2-2.5-.6-4.9-.9-6.6 0-.1 0-.2-.1-.4-.1-1.1-.4-2.4-.5-3.5-.1-.7-.6-1.6-1.2-2-.6-.4-1.4-.6-2-.5h-.3c-.7.1-1.6.6-2.1 1.2-.4.6-.6 1.4-.5 2.1l.4 2.7c1.2 9.3 1.6 11.5 2 15.8 0 .5 1.1 11.5 1.1 12.1.2 5.2.7 11.7 1.1 13.1 1 3.7 4.7 3.2 5.8 1.6.5-.6.9-1.2.6-2.7V62c-1-5.7-.7-11.7-.9-12.7.7-1.2 1.9-1.2 2.6-.6 1.4 1.1 2.5 3.1 3.1 5.4.7 3.6 2 5.7 3.5 7.3 1.2 1.2 3.3 1.9 5.7 1.9 2.2 0 3.6-.9 4.3-1.4 2.4-1.5 1.7-3.9.4-4.7M9.5 93H3.9V82.8h1.6v8.7h4zM14.5 93.2c-1.1 0-1.9-.4-2.4-1.2s-.7-1.7-.7-2.7c0-1.1.3-2 .8-2.8s1.4-1.3 2.5-1.3c.8 0 1.5.3 2.1 1s.8 1.7.8 3.1v.5H13c0 .7.2 1.2.4 1.4.3.3.7.5 1.1.5.3 0 .6 0 .9-.1.3-.1.6-.2.9-.5l.5-.3.7 1.5-.4.2c-.4.2-.9.4-1.3.5-.4.1-.8.2-1.3.2zm-1.5-5h2.9c-.1-.4-.2-.7-.4-1-.2-.4-.6-.6-1-.6-.5 0-.8.2-1.1.6-.2.3-.3.7-.4 1zM24 93.2l-.1-.4c0-.1 0-.1-.1-.2l-.2.2c-.4.3-.8.4-1.3.4-.7 0-1.3-.3-1.8-.8-.4-.5-.6-1.1-.6-1.8 0-.8.2-1.5.6-2 .6-.8 1.7-1.1 2.8-.7h.1c0-.5-.1-.8-.2-.9 0-.1-.1-.3-.6-.3-.2 0-.4 0-.6.1s-.5.2-.8.3l-.6.3-.4-1.6.4-.2c.3-.1.7-.2 1-.3s.6-.1 1-.1c.8 0 1.4.3 1.8.8s.6 1.2.6 2.1l-.1 2.8c0 .3 0 .5.1.8 0 .2.1.5.2.9l.2.6H24zm-1.4-4.1c-.5 0-.6.2-.7.3-.2.3-.3.6-.3 1.1 0 .3.1.6.2.8.3.4.8.3 1.1 0 .2-.2.4-.4.6-.8v-1.3c-.1 0-.2-.1-.4-.1-.1.1-.3 0-.5 0zM30.7 93h-1.6v-5.1l-.3-2.7h1.6l.1.9.3-.3c.5-.5 1-.7 1.7-.7s1.2.3 1.5.9c.3.5.4 1 .4 1.7v.5h-1.6v-.5c0-.4 0-.7-.1-.9 0-.1 0-.1-.2-.1-.3 0-.6.3-1 .8s-.6 1.3-.6 2.4V93h-.2zM42.4 93h-1.6v-5c0-.5 0-.9-.1-1.2 0-.1-.1-.2-.4-.2-.4 0-.8.2-1.2.7s-.6 1.3-.6 2.4V93h-1.6v-3.9c0-.9 0-1.5-.1-2 0-.5-.1-.9-.2-1.3l-.1-.6h1.6l.1.9c.1-.1.2-.2.3-.2.6-.5 1.2-.7 1.8-.7.9 0 1.4.5 1.6.8.3.5.4 1.1.4 1.7l.1 5.3zM48.9 83.8c0 .2-.1.3-.2.5-.1.1-.3.2-.5.2s-.3-.1-.5-.2-.2-.3-.2-.5.1-.3.2-.5c.1-.1.3-.2.5-.2s.3.1.5.2c.1.1.2.3.2.5"/>
                            <path class="wk-tl1"
                                  d="M49 93h-1.6v-6.2h-1.9v-1.5H49V93zm-.8-8c-.3 0-.6-.1-.8-.3s-.3-.5-.3-.8.1-.6.3-.8c.4-.4 1.2-.5 1.6 0 .2.2.4.5.4.8s-.1.6-.3.8c-.3.1-.6.3-.9.3zm0-1.4l-.1.2c0 .1.2 0 .2 0v-.1-.1h-.1z"/>
                            <path class="wk-tl1"
                                  d="M48.9 83.8c0 .2-.1.3-.2.5-.1.1-.3.2-.5.2s-.3-.1-.5-.2-.2-.3-.2-.5.1-.3.2-.5c.1-.1.3-.2.5-.2s.3.1.5.2c.1.1.2.3.2.5z"/>
                            <path class="wk-tl1"
                                  d="M48.2 84.8c-.3 0-.5-.1-.7-.3s-.3-.5-.3-.7c0-.3.1-.5.3-.7.4-.4 1-.4 1.4 0 .2.2.3.5.3.7 0 .3-.1.5-.3.7-.1.2-.4.3-.7.3zm0-1.3c-.1 0-.1 0-.2.1s-.1.1-.1.2 0 .2.1.2c.1.1.3.1.4 0 .1-.1.1-.1.1-.2s0-.1-.1-.2-.1-.1-.2-.1zM59.2 93h-1.6v-5c0-.5 0-.9-.1-1.2 0-.1-.1-.2-.4-.2-.4 0-.8.2-1.2.7s-.6 1.3-.6 2.4V93h-1.6v-3.9c0-.9 0-1.5-.1-2 0-.5-.1-.9-.2-1.3l-.1-.6H55l.1.9c.1-.1.2-.2.3-.2.6-.5 1.2-.7 1.8-.7.9 0 1.4.5 1.6.8.3.5.4 1.1.4 1.7V93zM64.8 95.2c-.4 0-.8-.1-1.3-.3-.4-.2-.8-.5-1-.8l-.3-.4 1.2-1.1.3.4c.1.2.3.3.5.4.5.2 1 .2 1.5-.2.2-.2.4-.6.4-1.2-.1 0-.1.1-.2.1-.4.2-.8.3-1.2.3-.9 0-1.6-.4-2.1-1.1-.4-.7-.7-1.5-.7-2.4 0-1 .2-1.8.7-2.6s1.3-1.2 2.3-1.2c.4 0 .8.1 1.1.2.1.1.2.1.4.2l.1-.4h1.7l-.2.6c-.1.4-.2.9-.2 1.3 0 .5-.1 1-.1 1.6v3.3c0 1-.3 1.8-.9 2.4-.6.6-1.3.9-2 .9zm0-8.6c-.4 0-.7.2-.9.6-.3.5-.4 1-.4 1.6 0 .8.2 1.4.4 1.7.6.6 1.1.5 1.6.2.2-.1.4-.3.5-.5v-2.9c-.1-.2-.3-.3-.5-.5-.2-.1-.4-.2-.7-.2zM82.3 93.2c-.6 0-1.2-.2-1.5-.6-.4-.4-.6-1.1-.6-2v-3.8h-1.5v-1.5h1.5v-2.2h1.6v2.2h2.4v1.5h-2.4v4.1c0 .3.1.5.2.6 0 0 .1.1.4.1.2 0 .4-.1.6-.2.2-.2.4-.4.5-.7l.2-.4 1.6.4-.3.6c-.3.5-.6 1-1 1.3-.6.4-1.1.6-1.7.6zM90.3 93.2c-.8 0-1.5-.3-2.1-1-.6-.6-.9-1.7-.9-3s.3-2.3.9-3 1.3-1 2.2-1c.8 0 1.5.3 2.2 1 .6.6.9 1.7.9 3 0 1.4-.3 2.4-.9 3-.8.6-1.5 1-2.3 1zm0-6.6c-.5 0-.9.2-1.1.6-.2.5-.4 1.2-.4 1.9s.1 1.4.4 1.9c.2.4.5.6 1.1.6.7 0 1-.3 1.1-.6.3-.5.4-1.2.4-1.9s-.1-1.4-.4-1.9c-.2-.4-.5-.6-1.1-.6zM111.2 93h-1.9l-2.3-4h-.9v4h-1.6V82.8h2.6c.9 0 1.6.3 2.1.9s.8 1.3.8 2.2c0 .7-.2 1.3-.6 1.9-.3.4-.6.7-1 .9l2.8 4.3zm-5-5.5h1c.3 0 .6-.1.9-.4s.4-.7.4-1.3c0-.5-.1-.8-.3-1.1-.2-.2-.5-.4-.9-.4h-1.1v3.2zM115.6 93.2c-1.1 0-1.9-.4-2.4-1.2s-.7-1.7-.7-2.7.3-2 .7-2.8c.5-.8 1.4-1.3 2.5-1.3.8 0 1.5.3 2.1 1s.8 1.7.8 3.1v.5H114c0 .7.2 1.2.4 1.4.3.3.7.5 1.2.5.3 0 .6 0 .9-.1s.6-.3.9-.5l.5-.3.7 1.5-.4.2c-.4.2-.9.4-1.3.5-.4.1-.9.2-1.3.2zm-1.6-5h2.9c-.1-.4-.2-.7-.4-1-.2-.4-.6-.6-1-.6-.5 0-.8.2-1.1.6-.1.3-.3.7-.4 1zM125.1 93.2l-.1-.4c0-.1 0-.1-.1-.2l-.2.2c-.4.3-.8.4-1.3.4-.7 0-1.3-.3-1.8-.8-.4-.5-.6-1.1-.6-1.8 0-.8.2-1.5.6-2 .6-.8 1.7-1.1 2.8-.7h.1c0-.5-.1-.8-.2-.9 0-.1-.1-.3-.6-.3-.2 0-.4 0-.6.1s-.5.2-.8.3l-.6.3-.4-1.6.4-.2c.3-.1.7-.2 1-.3s.6-.1 1-.1c.8 0 1.4.3 1.8.8s.6 1.2.6 2.1l-.1 2.8c0 .3 0 .5.1.8 0 .2.1.5.2.9l.2.6h-1.4zm-1.5-4.1c-.5 0-.6.2-.7.3-.2.3-.3.6-.3 1.1 0 .3.1.6.2.8.3.4.8.3 1.1 0 .2-.2.4-.4.6-.8v-1.3c-.1 0-.2-.1-.4-.1-.1.1-.3 0-.5 0zM133.8 93.2l-.1-.5c-.4.3-.9.5-1.5.5-.8 0-1.5-.4-2.2-1-.6-.7-.9-1.7-.9-3s.3-2.4 1-3 1.4-1 2.1-1c.4 0 .8.1 1.1.2v-2.8h1.6v7.6c0 .5 0 .9.1 1.2 0 .3.1.7.2 1.1l.2.6-1.6.1zm-1.4-6.6c-.4 0-.8.2-1.1.5-.4.4-.5 1-.5 2 0 .9.2 1.6.5 2 .6.7 1.3.7 1.7.3.2-.2.4-.4.5-.7V87c-.1-.1-.3-.2-.4-.3-.4 0-.5-.1-.7-.1zM149.2 91.4c-.6 0-1.1-.2-1.5-.6s-.6-.9-.6-1.5.2-1.1.6-1.5c.8-.8 2.2-.8 3 0 .4.4.6.9.6 1.5s-.2 1.1-.6 1.5c-.5.4-1 .6-1.5.6zM170.2 93h-1.9l-2.3-4h-.9v4h-1.6V82.8h2.6c.9 0 1.6.3 2.1.9s.8 1.3.8 2.2c0 .7-.2 1.3-.6 1.9-.3.4-.6.7-1 .9l2.8 4.3zm-5-5.5h1c.3 0 .6-.1.9-.4.3-.3.4-.7.4-1.3 0-.5-.1-.8-.3-1.1-.2-.2-.5-.4-.9-.4h-1.1v3.2z"/>
                            <g>
                                <path class="wk-tl1"
                                      d="M174.6 93.2c-1.1 0-1.9-.4-2.4-1.2s-.7-1.7-.7-2.7.3-2 .7-2.8c.5-.8 1.4-1.3 2.5-1.3.8 0 1.5.3 2.1 1s.8 1.7.8 3.1v.5H173c0 .7.2 1.2.4 1.4.3.3.7.5 1.2.5.3 0 .6 0 .9-.1.3-.1.6-.3.9-.5l.5-.3.7 1.5-.4.2c-.4.2-.9.4-1.3.5-.5.1-.9.2-1.3.2zm-1.6-5h2.9c-.1-.4-.2-.7-.4-1-.2-.4-.6-.6-1-.6-.5 0-.8.2-1.1.6-.2.3-.3.7-.4 1z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M184 93.2l-.1-.4c0-.1 0-.1-.1-.2l-.2.2c-.4.3-.8.4-1.3.4-.7 0-1.3-.3-1.8-.8-.4-.5-.6-1.1-.6-1.8 0-.8.2-1.5.6-2 .6-.8 1.7-1.1 2.8-.7h.1c0-.5-.1-.8-.2-.9 0-.1-.1-.3-.6-.3-.2 0-.4 0-.6.1-.2.1-.5.2-.8.3l-.6.3-.4-1.6.4-.2c.3-.1.7-.2 1-.3s.6-.1 1-.1c.8 0 1.4.3 1.8.8.4.5.6 1.2.6 2.1l-.1 2.8c0 .3 0 .5.1.8 0 .2.1.5.2.9l.2.6H184zm-1.4-4.1c-.5 0-.6.2-.7.3-.2.3-.3.6-.3 1.1 0 .3.1.6.2.8.3.4.8.3 1.1 0 .2-.2.4-.4.6-.8v-1.3c-.1 0-.2-.1-.4-.1-.1.1-.3 0-.5 0z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M192.8 93.2l-.1-.5c-.4.3-.9.5-1.5.5-.8 0-1.5-.4-2.2-1-.6-.7-.9-1.7-.9-3s.3-2.4 1-3c.7-.7 1.4-1 2.1-1 .4 0 .8.1 1.1.2v-2.8h1.6v7.6c0 .5 0 .9.1 1.2 0 .3.1.7.2 1.1l.2.6-1.6.1zm-1.4-6.6c-.4 0-.8.2-1.1.5-.4.4-.5 1-.5 2 0 .9.2 1.6.5 2 .6.7 1.3.7 1.7.3.2-.2.4-.4.5-.7V87c-.1-.1-.3-.2-.4-.3-.4 0-.6-.1-.7-.1z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M200.6 93H199v-6.2h-1.9v-1.5h3.5V93zm-.8-8c-.3 0-.6-.1-.8-.3s-.3-.5-.3-.8.1-.6.3-.8c.4-.5 1.2-.5 1.6 0 .2.2.4.5.4.8s-.1.6-.3.8c-.3.1-.6.3-.9.3z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M210.8 93h-1.6v-5c0-.5 0-.9-.1-1.2 0-.1-.1-.2-.4-.2-.4 0-.8.2-1.2.7s-.6 1.3-.6 2.4V93h-1.6v-3.9c0-.9 0-1.5-.1-2 0-.5-.1-.9-.2-1.3l-.1-.6h1.6l.1.9c.1-.1.2-.2.3-.2.6-.5 1.2-.7 1.8-.7.9 0 1.4.5 1.6.8.3.5.4 1.1.4 1.7V93h.1z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M216.4 95.2c-.4 0-.8-.1-1.3-.3-.4-.2-.8-.5-1-.8l-.3-.4 1.2-1.1.3.4c.1.2.3.3.5.4.5.2 1.1.2 1.5-.2.2-.2.4-.6.4-1.2 0 0-.1 0-.2.1-.4.2-.8.3-1.2.3-.9 0-1.6-.4-2.1-1.1-.4-.7-.7-1.5-.7-2.4 0-1 .2-1.9.7-2.6.5-.8 1.3-1.2 2.3-1.2.4 0 .8.1 1.1.2.1.1.2.1.4.2l.1-.4h1.7l-.2.6c-.1.4-.2.9-.2 1.3 0 .5-.1 1-.1 1.6v3.3c0 1-.3 1.8-.9 2.4s-1.3.9-2 .9zm0-8.6c-.4 0-.7.2-.9.6-.3.5-.4 1-.4 1.6 0 .8.2 1.4.4 1.7.6.6 1.1.5 1.6.2.2-.1.4-.3.5-.5v-2.9c-.1-.2-.3-.3-.5-.5-.2-.1-.4-.2-.7-.2z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M233.9 93.2c-.6 0-1.2-.2-1.5-.6-.4-.4-.6-1.1-.6-2v-3.8h-1.5v-1.5h1.5v-2.2h1.6v2.2h2.4v1.5h-2.4v4.1c0 .3.1.5.2.6.1.2.7.2 1-.1.2-.2.4-.4.5-.7l.2-.4 1.6.4-.3.6c-.3.5-.6 1-1 1.3-.6.4-1.1.6-1.7.6z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M241.9 93.2c-.8 0-1.5-.3-2.1-1-.6-.6-.9-1.7-.9-3s.3-2.3.9-3c.6-.7 1.3-1 2.2-1 .8 0 1.5.3 2.2 1 .6.6.9 1.7.9 3 0 1.4-.3 2.4-.9 3-.8.6-1.5 1-2.3 1zm0-6.6c-.5 0-.9.2-1.1.6-.2.5-.4 1.2-.4 1.9s.1 1.4.4 1.9c.2.4.5.6 1.1.6.7 0 1-.3 1.1-.6.3-.5.4-1.2.4-1.9s-.1-1.4-.4-1.9c-.2-.4-.5-.6-1.1-.6z"/>
                            </g>
                            <g>
                                <path class="wk-tl1" d="M262.1 93h-5.6V82.8h1.6v8.7h4z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M267.2 93.2c-1.1 0-1.9-.4-2.4-1.2s-.7-1.7-.7-2.7.3-2 .7-2.8c.5-.8 1.4-1.3 2.5-1.3.8 0 1.5.3 2.1 1s.8 1.7.8 3.1v.5h-4.6c0 .7.2 1.2.4 1.4.5.5 1.3.6 2.1.3.3-.1.6-.3.9-.5l.5-.3.7 1.5-.4.2c-.4.2-.9.4-1.3.5-.4.2-.9.3-1.3.3zm-1.6-5h2.9c-.1-.4-.2-.7-.4-1-.2-.4-.6-.6-1-.6-.5 0-.8.2-1.1.6-.1.3-.3.7-.4 1z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M276.7 93.2l-.1-.4c0-.1 0-.1-.1-.2l-.2.2c-.4.3-.8.4-1.3.4-.7 0-1.3-.3-1.8-.8-.4-.5-.6-1.1-.6-1.8 0-.8.2-1.5.6-2 .6-.8 1.7-1.1 2.8-.7h.1c0-.5-.1-.8-.2-.9 0-.1-.1-.3-.6-.3-.2 0-.4 0-.6.1s-.5.2-.8.3l-.6.3-.4-1.6.4-.2c.3-.1.7-.2 1-.3s.6-.1 1-.1c.8 0 1.4.3 1.8.8s.6 1.2.6 2.1l-.1 2.8c0 .3 0 .5.1.8 0 .2.1.5.2.9l.2.6h-1.4zm-1.5-4.1c-.5 0-.6.2-.7.3-.2.3-.3.6-.3 1.1 0 .3.1.6.2.8.3.4.8.3 1.1 0 .2-.2.4-.4.6-.8v-1.3c-.1 0-.2-.1-.4-.1-.1.1-.3 0-.5 0z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M283.4 93h-1.6v-5.1l-.3-2.7h1.6l.1.9.3-.3c.5-.5 1-.7 1.7-.7s1.2.3 1.5.9c.3.5.4 1 .4 1.7v.5h-1.6v-.5c0-.4 0-.7-.1-.9 0-.1 0-.1-.2-.1-.3 0-.6.3-1 .8s-.6 1.3-.6 2.4V93h-.2z"/>
                            </g>
                            <g>
                                <path class="wk-tl1"
                                      d="M295 93h-1.6v-5c0-.5 0-.9-.1-1.2 0-.1-.1-.2-.4-.2-.4 0-.8.2-1.2.7-.4.5-.6 1.3-.6 2.4V93h-1.6v-3.9c0-.9 0-1.5-.1-2 0-.5-.1-.9-.2-1.3l-.1-.6h1.6l.1.9c.1-.1.2-.2.3-.2.6-.5 1.2-.7 1.8-.7.9 0 1.4.5 1.6.8.3.5.4 1.1.4 1.7V93h.1z"/>
                            </g>
                        </svg>
                        </span>

                        <div class="tracks-heading-wrapper">
                            <h1 aria-level="1" role="heading" class="tracks-heading">Levelled Digital Reading Program</h1>

                            <div class="tracks-subheading">
                                <p>
                                    <span class="hidden-xs block">For beginning to fluent readers</span>

                                <div class='home-popover-wrapper'>
                                    <a class='home-popover schools' href='/features-for-teachers/'>At School</a>
                                    <a class='home-popover parents' href='/features-for-homes/'>At Home</a>
                                </div>
                                </p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </main>

    <section class="container-wrapper what-is-wushka pb5">
        <div class="container block-icon-header-copy">
            <div class="row">
                <div class="col-sm-12 text-center">

                </div>
            </div>
            <div class="row row-2">
                <article class="col-sm-12 text-center">
                    <p class="block-copy_lead">Wushka is a cloud based levelled school reading program, developed using
                        decades of educational publishing experience, which helps students learn to read. The extensive
                        selection of fiction and non-fiction levelled school readers are stored in Wushka's coloured
                        reading
                        boxes. Every levelled reader has an online comprehension quiz and printable teacher support
                        material. Teachers manage their own class, easily setting class reading levels, reading groups
                        and
                        choosing readers to read at home. Ongoing reading statistics with clear infographics provide
                        detailed insights into a student's progress.</p>
                </article>
            </div>
        </div>
    </section>

    <section id="home-readers-section" class="container-wrapper free-samples py20">
        <header></header>
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-center">
                    <h2 class="site-heading strong underline front-page-sample-readers">Try our Sample School
                        Readers</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-samples panel-default mt20">
                        <div class="carousel slide" id="carousel-taxo-samples">
                            <div class="panel-body">
                                <?php
                                /*------------ Free Sample Books --------------- */
                                $c_carousel = new Wushka_Carousel();
                                $a_samples = $c_carousel->get_free_samples();
                                $a_carousel = $c_carousel->build_sample_carousel($a_samples, 3);
                                echo implode('', $a_carousel);
                                ?>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container-wrapper video-wushka pb40">
        <div class="container">
            <div class="row mh200 mt40">
                <div class="col-xs-12 text-center dummy-video">
                    <div align="center" class="embed-responsive embed-responsive-16by9 video-item-wrapper">

                        <video id="video1" controls="controls" width="100%" height="100%" preload="auto"
                               class="me-video wk-bg_b"
                               poster="<?php echo get_template_directory_uri(); ?>/build/video-poster.png">
                            <source src="<?= getCdnLink(); ?>/Resources/Introduciton_to_Wushka.mp4" type="video/mp4">
                        </video>

                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-xs-12">
                    <div class='home-popover-wrapper home-popover-wrapper_below-video text-center'>
                        <a class='home-popover schools' href='/features-for-teachers'>At School</a>
                        <a class='home-popover parents' href='/features-for-homes'>At Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="library-img-wrapper py30 wk-bg_b">
        <div class="container-fluid">
            <div class="row no-gutter">
                <div class="col-xs-12">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 text-center">
                                <h2 class="site-heading strong underline mb10 colour-white">Visit our Colour Coded
                                    Reading
                                    Boxes</h2>
                            </div>
                        </div>
                    </div>
                    <a href="/library">
                        <img src="<?= getCdnLink(); ?>/Resources/magenta-box-mobile.png" width="750" height="772"
                             class="width100p home-reading-box small" alt="Visit our Colour Coded Reading Boxes">
                        <img src="<?= getCdnLink(); ?>/Resources/magenta-box-medium.png" width="1263" height="419"
                             class="width100p home-reading-box medium" alt="Visit our Colour Coded Reading Boxes">
                        <img src="<?= getCdnLink(); ?>/Resources/magenta-box-large.png" width="1905" height="450"
                             class="width100p home-reading-box large" alt="Visit our Colour Coded Reading Boxes">
                        <!-- div class="text-center mt15 mb20"><span class="btn btn-xl btn-green front-page-sample-readers">View Our Reading Boxes</span></div -->
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="container-wrapper what-is-wushka pt45 pb30 bg-white">
        <div class="container block–icon-header-copy">
            <div class="row what-is-wushka-row">
                <div class="col-sm-4 text-center">
                    <span class="block-icon glyphicon glyphicon-inbox x2"></span>

                    <h2 class="block-heading">Reading Boxes</h2>

                    <p class="block-copy">Our <a href="library">reading boxes</a> are carefully levelled from Magenta
                        (Level
                        1-2) through to Black (Levels 31+). School Readers have highlighted text and narration, useful
                        for
                        guided reading. A student’s classroom teacher selects appropriate reading levels, reading groups
                        and
                        school readers for both school and home, ensuring students progress at the appropriate pace and
                        build strong reading skills.
                    </p>
                </div>
                <div class="col-sm-4 text-center">
                    <span class="block-icon glyphicon glyphicon-more-items x2"></span>

                    <h2 class="block-heading">Support Materials</h2>

                    <p class="block-copy"><a href="<?= getCdnLink(); ?>/Resources/wk-support-materials.png"
                                             data-toggle="lightbox" data-title="Support Materials" class="inline">Support
                            materials</a> are provided for every school reader which include online comprehension
                        quizzes,
                        printable lessons plans, literacy activities, blackline masters and assessment tools. The Wushka
                        program has been developed using decades of educational publishing experience.</p>
                </div>
                <div class="col-sm-4 text-center">
                    <span class="block-icon glyphicon glyphicon-nameplate x2"></span>

                    <h2 class="block-heading">Wushka Site Licence </h2>

                    <p class="block-copy">Schools can purchase a Wushka Site Licence on behalf of their whole school
                        community to give all users unlimited access to the program. Teachers can set Readers for
                        homework
                        and students can continue to read after school. </p>
                </div>
            </div>
        </div>
    </section>

    <div class="container-fluid py30">
        <div class='row'>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <h2 class="site-heading strong mb10">Testimonials</h2>
                    </div>
                </div>
                <div class="row">
                    <div class='col-md-12 text-center m-b-lg'>

                        <div class="testimonials">
                            <div class="testimonials-carousel">
                                <div class="testimonial hidden">
                                    <img class="testimonial__logo"
                                         src="<?= getCdnLink(); ?>/Resources/testimonials-120.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1601365962&Signature=tC4KfKs8k6GcnTXjgKAdfRC9OVI%3D"
                                         alt="Profile Icon">
                                    <h2 class="testimonial__title">Lyn J</h2>
                                    <p>Wushka levelled reading is an excellent cloud based program that is being enjoyed
                                        by my 6/5 composite class. The program is easy to set up and provides me as the
                                        class teacher excellent information on what and how the students are reading.
                                        The variety of text is impressive and the lesson plans and blackline masters
                                        associated with the text are great.
                                        Students in my class are asking to do reading groups so they can logon to
                                        Wushka.</p>
                                </div>
                                <div class="testimonial active">
                                    <img class="testimonial__logo"
                                         src="<?= getCdnLink(); ?>/Resources/testimonials-120.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1601365962&Signature=tC4KfKs8k6GcnTXjgKAdfRC9OVI%3D"
                                         alt="Profile Icon">
                                    <h2 class="testimonial__title">Pauline H</h2>
                                    <p>Staff are really happy with the program as it has encouraged students disengaged
                                        in reading to be more enthusiastic in reading groups and during independent
                                        reading activities. The program also allows for students to choose from a very
                                        wide range of texts and genre.</p>
                                </div>
                                <div class="testimonial hidden">
                                    <img class="testimonial__logo"
                                         src="<?= getCdnLink(); ?>/Resources/testimonials-120.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1601365962&Signature=tC4KfKs8k6GcnTXjgKAdfRC9OVI%3D"
                                         alt="Profile Icon">
                                    <h2 class="testimonial__title">Tanya H</h2>
                                    <p>I personally like the way you can select books that are appropriate to each
                                        child’s reading level and they can check their own level of understanding of a
                                        text through receiving a score for questions answered after reading the
                                        book.</p>
                                </div>
                            </div>

                            <div class="testimonials-controls">
                                <input class="previous" type="image"
                                       src="<?= getCdnLink(); ?>/Resources/testimonial-arow-left.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1601367149&Signature=EeT%2F9UOU6zh3nO903NaaTWFNwj0%3D">

                                <input class="next" type="image"
                                       src="<?= getCdnLink(); ?>/Resources/testimonial-arow-right.png?AWSAccessKeyId=AKIAI7QDCQTAVPNOU5XA&Expires=1601367150&Signature=YKLdH4Jj%2B5Zy4V7Vj8kLZz9aa3k%3D">
                            </div>
                        </div>
                        <div class="text-center"><a href="/stories" class="btn btn-green colour-white inline-block">Read
                                All
                                Stories</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="container-wrapper what-is-wushka pt45 pb30 bg-white">
        <div class="container block–icon-header-copy">
            <div class="row what-is-wushka-row">
                <div class="col-sm-12 text-center">
                    <span class="block-icon glyphicon glyphicon-facetime-video x2"></span>

                    <h2 class="block-heading">Professional Development</h2>

                    <p class="block-copy">Watch a collection of our Wushka Professional Development sessions. Hosted by
                        our Business Development Managers at schools across the country.
                    </p>
                    <div class="text-center"><a href="/professional-development"
                                                class="btn btn-green colour-white inline-block">View Sessions</a></div>
                </div>
            </div>
        </div>
    </section>
    <?php /* CONDITIONAL HOMEPAGE DEPENDING ON COUNTRY End --------------------------------------------------------*/
} ?>
<?php /* ----- EOF ----- */ ?>

<script>
    var testimonials = document.querySelectorAll('.testimonial');
    var activeTestimonialIndex = 1; // Second slide
    var animatedYet = false;

    jQuery(document).ready(function ($) {
        // Testimonials
        document.querySelector('.testimonials-controls .previous').addEventListener('click', showPreviousSlide);
        document.querySelector('.testimonials-controls .next').addEventListener('click', showNextSlide);

        setCarouselHeight();
        $(window).on('resize', setCarouselHeight);

        $(window).on('resize scroll', function () {
            if (animatedYet) return;
            if (!isScrolledIntoView(testimonials[activeTestimonialIndex])) return;

            animateSlides();
            animatedYet = true;
        });

        if (isScrolledIntoView(testimonials[activeTestimonialIndex])) {
            animateSlides();
        }

        //Add Img Src to img element, fade in
        var a_samples = $('.bookshelf-item-wrapper');
        setTimeout(function () {
            if (a_samples.length > 0) {
                $.each(a_samples, function (idx, o_sample) {
                    if ($(o_sample).find('.img-source').length > 0) {
                        var s_src = $(o_sample).find('.img-source').attr('value').trim();
                        $(o_sample).find('img.img-responsive').attr('src', s_src);
                    }
                });
                $('#carousel-taxo-samples').find('.panel-body').fadeTo(200, 1);
            }
        }, 500);

        // Initialise Popover
        $('[data-toggle="popover"]').popover({html: true});

        /*
        jQuery("#free-trial").on("click", function () {
            if (jQuery("#wk-form-modal .gform_title:contains('Subscription Offer')")) {

                jQuery("#wk-form-modal .gform_title").html("Contact Me About Wushka Free Trial")
            }
        });

        jQuery("#subscription-offer").on("click", function () {
            if (jQuery("#wk-form-modal .gform_title:contains('Free Trial')")) {

                jQuery("#wk-form-modal .gform_title").html("Contact Me About Wushka Subscription Offer")
            }
        });

        */
        // Prepend Play button to Sample Books
        a_samples.prepend('<span class="glyphicon glyphicon-play-button btn-glyphicon-sample-play"></span>');
        $('.btn-glyphicon-sample-play').velocity("fadeIn", {duration: 500});
        $('#quote-carousel').carousel({pause: true, interval: 8000});
    });

    function setCarouselHeight() {
        var largestHeight = 0;

        for (var i = 0; i < testimonials.length; i++) {
            var el = testimonials[i];
            largestHeight = el.clientHeight > largestHeight ? el.clientHeight : largestHeight;
        }

        //document.querySelector('.testimonials-carousel').style.minHeight = largestHeight * 1.2 + 'px';
        largestHeight = largestHeight * 1.2;
        document.querySelector('.testimonials-carousel').style.minHeight = 'calc(' + largestHeight + 'px * 1.2)';
    }

    function animateSlides() {
        var isMobile = window.matchMedia('(max-width: 767px)').matches;
        activeTestimonial = testimonials[activeTestimonialIndex];
        activeTestimonial.classList.remove('hidden');
        activeTestimonial.classList.remove('left');
        activeTestimonial.classList.remove('right');
        activeTestimonial.classList.add('active');
        activeTestimonial.style.marginLeft = '';

        for (var i = 0; i < testimonials.length; i++) {
            if (i === activeTestimonialIndex) continue;

            var el = testimonials[i];

            if (i < activeTestimonialIndex) animate(el, 'left', 'right', i);
            else if (i > activeTestimonialIndex) animate(el, 'right', 'left', i);
        }
        ;

        function animate(el, addClass, removeClass, index) {
            el.classList.remove('active');
            el.classList.remove(removeClass);
            el.classList.add(addClass);

            if (isMobile) {
                setTimeout(function () {
                    el.classList.remove('hidden');
                }, 500);
            } else {
                el.classList.remove('hidden');
            }

            var multiple = index - activeTestimonialIndex;
            el.style.marginLeft = (multiple * 30) + '%';
        }
    }

    function showPreviousSlide(event) {
        event.preventDefault();
        if (activeTestimonialIndex === 0) return;

        activeTestimonialIndex--;
        animateSlides();
        document.querySelector('.next').classList.remove('faded');

        if (activeTestimonialIndex > 0) return;
        event.target.classList.add('faded');
    }

    function showNextSlide(event) {
        event.preventDefault();
        if (activeTestimonialIndex === testimonials.length - 1) return;

        activeTestimonialIndex++;
        animateSlides();
        document.querySelector('.previous').classList.remove('faded');

        if (activeTestimonialIndex < testimonials.length - 1) return;
        event.target.classList.add('faded');
    }

    function isScrolledIntoView(el) {
        // Check if element is completely in view.
        var rect = el.getBoundingClientRect();
        var elemTop = rect.top;
        var elemBottom = rect.bottom;

        var isVisible = (elemTop >= 0) && (elemBottom <= window.innerHeight);
        return isVisible;
    }
</script>
