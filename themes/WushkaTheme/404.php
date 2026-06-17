<?php
/*
  Template Name: 404 Page
 */
?>
<?php get_header(); ?>
<style>
#hero{
  display: flex;
  align-items: center;
  min-height: 385px;
  text-align: center;
  padding: 3rem 0 0 0;
}
.role- .wrapper-main{
  min-height: 0;
}
.btn-home{
  background: #fff !important;
  border-color: #fff !important;
  color: #00bef2 !important;
  transition: 0.3s;
}
.btn-home:hover{
  background: #ccc !important;
  border-color: #ccc !important;
  color: #11bef2 !important;
}
</style>
<div id="hero">
  <div class="container-fluid padding-y">
      <div class="row">
          <div class="col-md-12">
              <div class="text-center">
                  <h1>
                      <?php _e('404 Error: Page Not Found', 'lessonzone'); ?>
                  </h1>
                  <p>
                      <?php _e('Apologies, but the page you requested could not be found.', 'lessonzone') ?>
                  </p>
                  <p>
                    <a class="btn btn-primary btn-home" href="/">Return Home</a>
                  </p>
              </div>
          </div>
      </div>
  </div>
</div>


<?php
include 'dashboard_options.php';
get_footer();
?>
