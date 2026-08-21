<?php
/*
 * Template Name: Features for Teachers
 */
get_header();
?>
<style type="text/css">
    .shelf-book-details.level-details {
        display: block;
        color: #FFF;
        padding: 10px 20px;
        margin: 10px 0 0 0;
        border-radius: 5px;
    }
</style>
<div class="features-for-teachers-wrapper">

  <section class="section banner-section splash-banner col-lg-12 mb30">
    <div class="banner-text heading">
      <h1 class="hero-heading page-heading banner-header">Levelled Reading Program</h1>
      <div class="clearfix"></div>
      <h2 class="sub-heading">Wushka for Schools.</h2>
    </div>
  </section>

  <div class="container-wrapper what-is-wushka pb20">
    <div class="container block–icon-header-copy">
      <div class="row mt30">
        <div class="col-sm-4 text-center">
          <span class="block-icon glyphicon glyphicon-inbox x2"></span>
          <h2 class="block-heading">Reading Boxes</h2>
          <p class="block-copy">Our <a href="library">Reading Boxes</a> are carefully levelled from Magenta (Level 1-2) through to Black (Levels 31+) encompassing a very broad range of engaging fiction and non-fiction School Readers. A student’s classroom teacher sets the appropriate reading levels and reading groups for both school and home. The School Readers have optional highlighted text and narration, useful for guided reading.
          </p>

        </div>
        <div class="col-sm-4 text-center">
          <span class="block-icon glyphicon glyphicon-more-items x2"></span>
          <h2 class="block-heading">Support Materials</h2>
          <p class="block-copy">The Wushka reading program has been developed using decades of educational publishing experience. Teachers are provided with comprehensive <a href="//cdn1.wushka.com.au/Resources/wk-support-materials.png" data-toggle="lightbox" data-title="Comprehensive Support Materials" class="inline">support materials</a>  for every School Reader, which include online quizzes, printable lessons plans, literacy activities, blackline masters and assessment tools.</p>
        </div>
        <div class="col-sm-4 text-center">
          <span class="block-icon glyphicon glyphicon-education x2"></span>
          <h2 class="block-heading">Whole School Management</h2>
          <p class="block-copy">Easily set up classes and teachers using one school login. With the drag and drop system, it’s simple for teachers to allocate School Readers to students and reading groups and select appropriate Readers to complete at home. Reading statistics give detailed insights into students’ level of interaction and comprehension both at school and home.</p>
        </div>
      </div>
    </div>
  </div>

<div class="container-wrapper video-wushka pb40">
  <div class="container">
      <div class="row mh200 mt40">
          <div class="col-xs-12 col-sm-6 text-center dummy-video">
              <div align="center" class="embed-responsive embed-responsive-16by9 video-item-wrapper">

                  <video id="video1" controls="controls" width="100%" height="100%" preload="auto"
                         class="me-video wk-bg_b"
                         poster="<?php echo get_template_directory_uri(); ?>/build/wushka-reader.jpg">
                      <source src="//cdn1.wushka.com.au/Resources/Introduciton_to_Wushka.mp4" type="video/mp4">
                  </video>

              </div>
          </div>
          <div class="visible-xs mt20 col-xs-12">

          </div>
          <div class="col-xs-12 col-sm-6 text-center dummy-video">

              <div align="center" class="embed-responsive embed-responsive-16by9 video-item-wrapper">

                  <video id="video1" controls="controls" width="100%" height="100%" preload="auto"
                         class="me-video wk-bg_b"
                         poster="//cdn1.wushka.com.au/Resources/how-to-use-wushka-start.jpg">
                      <source src="//cdn1.wushka.com.au/Resources/How-to-use-wushka-tutorial.mp4" type="video/mp4">
                  </video>

              </div>
          </div>

      </div>
    <div class="clearfix"></div>
    <div class="row">
      <div class="col-xs-12">
        <div class="text-center mt30">
          <button type="button" class="btn btn-xl btn-green" data-toggle="modal" data-target="#wk-form-modal">Start Using Wushka Today</button>
        </div>
      </div>
    </div>
  </div>
</div>

  <section id="home-readers-section" class="container-wrapper free-samples pt30 pb20">
    <header><h2 class="sr-only">Free Samples</h2></header>
      <div class="container">
      <div class="row">
            <div class="col-xs-12 text-center">
              <h1 class="site-heading strong underline front-page-sample-readers">Try our Sample School Readers</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
              <div class="panel panel-samples panel-default mt20">
                <div class="carousel slide" id="carousel-taxo-samples">
              <div class="panel-body">
                <?php
                  /*------------ Free Sample Books --------------- */
                  $c_carousel = new Wushka_Carousel();
                  $a_samples  = $c_carousel->get_free_samples();
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

  <div class="container-wrapper call-to-action-wrapper bg-cyan pt30">
      <div class="container">
          <div class="row">
              <div class="col-xs-12 text-center">
                  <p class="no-margin subscription-price-label">The Benefits of Wushka’s Cloud-based Levelled Reading Program</p>
              </div>
          </div>
      </div>
  </div>


  <div class="container-wrapper what-is-wushka bg-cyan">
      <div class="container block–icon-header-copy">
          <div class="row mt20 mb30">
              <div class="col-sm-4 text-center">
                  <span class="block-icon glyphicon glyphicon-more-items x2"></span>
                  <h2 class="block-heading">Curriculum Support</h2>
                  <p class="block-copy">A Wushka Site Licence includes access to a comprehensive collection of support materials for every School Reader. Support materials help teachers meet the requirements of the National English Curriculum and state standards when teaching and assessing reading.</p>
              </div>
              <div class="col-sm-4 text-center">
                  <span class="block-icon glyphicon glyphicon-home x2"></span>
                  <h2 class="block-heading">Wushka at Home</h2>
                  <p class="block-copy">Teachers manage their own class, easily setting class reading levels, reading groups and choosing appropriate readers that can be accessed on tablets or computers. Students can login to read with Wushka at any time if the school has a Wushka Site Licence.</p>
              </div>
              <div class="col-sm-4 text-center">
                  <span class="block-icon glyphicon glyphicon-nameplate x2"></span>
                  <h2 class="block-heading">Unlimited Access</h2>
                  <p class="block-copy">With a Wushka Site Licence, teachers and students have access during school hours and at home. On request, we will walk you through the steps of setting up your school and provide you with unlimited access to the Wushka program. <a href="/contact-us" class="text-link colour-red">Enquire about a Wushka Site Licence.</a></p>
              </div>
          </div>
      </div>
  </div>

  <div class="container-wrapper teacher-features text-image-blocks">
    <div class="wrapper-even">
      <div class="container">
        <div class="row feat-block school-management">
          <div class="col-xs-12 col-sm-7 text-center feat-image-container hidden-xs">
  <div class="feat-image"><a href="//cdn1.wushka.com.au/Resources/wk-whole-school-management.png" data-toggle="lightbox" data-title="Whole School Management"><span class="btn-click-to-enlarge-wrapper"><span class="btn-click-to-enlarge"><span class="glyphicon glyphicon-zoom-in"></span>Click to enlarge</span></span><img src="//cdn1.wushka.com.au/Resources/wk-whole-school-management.png" alt="Whole School Management" class="img-responsive"></a></div>
          </div>
          <div id="" class="col-xs-12 col-sm-5 text-center feat-body-container">
            <div class="feat-body right">
              <div class="feat-body-top">
                <span class="feat-icon glyphicon glyphicon-education x2"></span>
                <h3 class="feat-heading">Whole School Management</h3>
              </div>
              <div class="feat-image visible-xs"><img src="//cdn1.wushka.com.au/Resources/wk-whole-school-management.png" alt="Whole School Management" class="img-responsive"></div>
              <div class="feat-body-bottom">
                <p class="feat-copy">Easily set up classes and teachers using one school login. With the drag and drop system, it’s simple for teachers to allocate School Readers to students and reading groups and select appropriate Readers to complete at home. Reading statistics give detailed insights into students’ level of interaction and comprehension both at school and home.<button type="button" class="block center text-link" data-toggle="modal" data-target="#wk-form-modal">Use Wushka at Your School</button></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="wrapper-odd">
      <div class="container">
        <div class="row feat-block class-lists">
          <div id="" class="col-xs-12 col-sm-5 text-center feat-body-container">
            <div class="feat-body left">
              <div class="feat-body-top">
                <span class="feat-icon glyphicon glyphicon-group x2"></span>
                <h3 class="feat-heading">Manage Class Lists</h3>
              </div>
              <div class="feat-image visible-xs"><img src="//cdn1.wushka.com.au/Resources/wk-manage-class-list.png" alt="Manage Class List" class="img-responsive"></div>
              <div class="feat-body-bottom">
                <p class="feat-copy">Teachers manage their own class of students, for both school and home, easily editing settings such as reading levels, reading groups or passwords. Teachers can turn the narration and comprehension quizzes on or off to suit each student.<button type="button" class="block center text-link" data-toggle="modal" data-target="#wk-form-modal">Use Wushka at Your School</button></p>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-7 text-center feat-image-container hidden-xs">
  <div class="feat-image"><a href="//cdn1.wushka.com.au/Resources/wk-manage-class-list.png" data-toggle="lightbox" data-title="Manage Class Lists"><span class="btn-click-to-enlarge-wrapper"><span class="btn-click-to-enlarge"><span class="glyphicon glyphicon-zoom-in"></span>Click to enlarge</span></span><img src="//cdn1.wushka.com.au/Resources/wk-manage-class-list.png" alt="Manage Class List" class="img-responsive"></a></div>
          </div>
        </div>
      </div>
    </div>

    <div class="wrapper-even">
      <div class="container">
        <div class="row feat-block reading-groups">
          <div class="col-xs-12 col-sm-7 text-center feat-image-container hidden-xs">
  <div class="feat-image"><a href="//cdn1.wushka.com.au/Resources/wk-reading-group-beta.png" data-toggle="lightbox" data-title="Reading Groups"><span class="btn-click-to-enlarge-wrapper"><span class="btn-click-to-enlarge"><span class="glyphicon glyphicon-zoom-in"></span>Click to enlarge</span></span><img src="//cdn1.wushka.com.au/Resources/wk-reading-group-beta.png" alt="Reading Groups" class="img-responsive"></a></div>
          </div>
          <div id="" class="col-xs-12 col-sm-5 text-center feat-body-container">
            <div class="feat-body right">
              <div class="feat-body-top">
                <span class="feat-icon glyphicon glyphicon-book-open x2"></span>
                <h3 class="feat-heading">Reading Groups</h3>
              </div>
              <div class="feat-image visible-xs"><img src="//cdn1.wushka.com.au/Resources/wk-reading-group-beta.png" alt="Reading Groups" class="img-responsive"></div>
              <div class="feat-body-bottom">
                <p class="feat-copy">With the interactive program, it’s easy for teachers to allocate School Readers and students to customisable reading groups. This can be useful for classroom teachers when setting appropriate School Readers to be completed at home.<button type="button" class="block center text-link" data-toggle="modal" data-target="#wk-form-modal">Use Wushka at Your School</button></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="wrapper-odd">
      <div class="container">
        <div class="row feat-block class-statistics">
          <div id="" class="col-xs-12 col-sm-5 text-center feat-body-container">
            <div class="feat-body left">
              <div class="feat-body-top">
                <span class="feat-icon glyphicon glyphicon-pie-chart x2"></span>
                <h3 class="feat-heading">Class Statistics</h3>
              </div>
              <div class="feat-image visible-xs"><img src="//cdn1.wushka.com.au/Resources/wk-class-statistics.png" alt="Class Statistics" class="img-responsive"></div>
              <div class="feat-body-bottom">
                <p class="feat-copy">With access to class statistics teachers will be able to monitor how students are progressing. The class details are only accessible by the teacher and the personal statistics to individual students.<button type="button" class="block center text-link" data-toggle="modal" data-target="#wk-form-modal">Use Wushka at Your School</button></p>
              </div>
            </div>
          </div>
          <div class="col-xs-12 col-sm-7 text-center feat-image-container hidden-xs">
  <div class="feat-image"><a href="//cdn1.wushka.com.au/Resources/wk-class-statistics.png" data-toggle="lightbox" data-title="Class Statistics"><span class="btn-click-to-enlarge-wrapper"><span class="btn-click-to-enlarge"><span class="glyphicon glyphicon-zoom-in"></span>Click to enlarge</span></span><img src="//cdn1.wushka.com.au/Resources/wk-class-statistics.png" alt="Class Statistics" class="img-responsive"></a></div>
          </div>
        </div>
      </div>
    </div>

    <div class="wrapper-even">
      <div class="container">
        <div class="row feat-block student-statistics">
          <div class="col-xs-12 col-sm-7 text-center feat-image-container hidden-xs">
  <div class="feat-image"><a href="//cdn1.wushka.com.au/Resources/wk-student-statistics.png" data-toggle="lightbox" data-title="Student Statistics"><span class="btn-click-to-enlarge-wrapper"><span class="btn-click-to-enlarge"><span class="glyphicon glyphicon-zoom-in"></span>Click to enlarge</span></span><img src="//cdn1.wushka.com.au/Resources/wk-student-statistics.png" alt="Student Statistics" class="img-responsive"></a></div>
          </div>
          <div id="" class="col-xs-12 col-sm-5 text-center feat-body-container">
            <div class="feat-body right">
              <div class="feat-body-top">
                <span class="feat-icon glyphicon glyphicon-charts x2"></span>
                <h3 class="feat-heading">Student Statistics</h3>
              </div>
              <div class="feat-image visible-xs"><img src="//cdn1.wushka.com.au/Resources/wk-student-statistics.png" alt="Student Statistics" class="img-responsive"></div>
              <div class="feat-body-bottom">
                <p class="feat-copy">Ongoing reading statistics for individual students provide detailed insights into their level of interaction and comprehension. Infographics, such as progress bars, help determine appropriate reading levels and keep students motivated. <button type="button" class="block center text-link" data-toggle="modal" data-target="#wk-form-modal">Use Wushka at Your School</button></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

</div>


<div class="container-wrapper call-to-action-wrapper block-free-for-school m0 bg-cyan">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <p class="no-margin subscription-price-label">Start Using Wushka’s Cloud-based Levelled Reading Program Today <button type="button" class="text-link colour-red" data-toggle="modal" data-target="#wk-form-modal">Use Wushka at Your School</button></p>
            </div>
        </div>
    </div>
</div>

<div class="container-wrapper pedagogy-wushka full-white">
  <div class="container block–icon-header-copy">
    <div class="row">
      <div class="col-xs-12 text-center">
        <h2 class="site-heading strong underline">Pedagogy</h2></div>
      <div class="col-sm-6 text-center"><span class="block-icon glyphicon glyphicon-book x2"></span>
        <h2 class="block-heading">Educational Publisher</h2>
        <p class="block-copy">Alongside our own print-based educational publisher, <strong>Learning Media</strong>, with over 20 years of educational publishing experience, the team at Wushka have created an online reading program that can be used both at school and at home.</p>
      </div>
      <div class="col-sm-6 text-center"><span class="block-icon glyphicon glyphicon-history x2"></span>
        <h2 class="block-heading">Evidence Based</h2>
        <p class="block-copy">Learning Media, has an enviable reputation for bringing high-quality, innovative printed literacy resources to the education community. <strong>Learning Media's</strong> printed books are centred on evidence-based instruction and designed with the knowledge of how teachers teach and students learn.</p>
      </div>
    </div>
  </div>
</div>

<div class="container-wrapper testimonials-wushka">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        <div class="row">
          <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-sm-offset-2">
            <div class="panel">
              <div class="panel-body">
                <p class="testimonial-copy"> I have used it every day. This has engaged my struggling readers extremely well and they love doing it! They often ask me when we are doing WUSHKA reading!!
Thank you for making my job easier!</p>
                <p class="testimonial-author"><span class="strong">SHARON</span> – Teacher's Aide, VIC </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="row">
          <div class="col-sm-9 col-lg-10">
            <div class="panel">
              <div class="panel-body">
                <p class="testimonial-copy"> Everyone is loving it! It has been really motivating for students and staff alike.</p>
                <p class="testimonial-author"><span class="strong">LESLEY</span> – Teacher, Sydney</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


</div>


<script>
  jQuery(document).ready(function($) {
    //Add Img Src to img element, fade in
    var a_samples = $('.bookshelf-item-wrapper');
    setTimeout(function(){
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
    $('[data-toggle="popover"]').popover({html:true});
  // Prepend Play button to Sample Books
    $('.bookshelf-item-wrapper').prepend('<span class="glyphicon glyphicon-play-button btn-glyphicon-sample-play"></span>');
    $('.btn-glyphicon-sample-play').velocity("fadeIn", { duration: 500 });
  });
</script>

<?php get_footer(); ?>
