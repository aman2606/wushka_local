<?php
/*
  Template Name: Tablet-features-teacher
 */
?>
<?php
global $current_user;
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <style type="text/css">
            .logo-wushka img {
                width: 180px !important;
                margin: 10px 20px !important;
            }

            .user-welcome-wrapper {
				  float: left!important;
				  margin-top: 10px!important;
				  border-top: none!important;
				  border-left: solid 1px #D2D2D2 !important;
				  padding-top: 0px!important;
				  padding-left: 25px!important;
            }

            header.navbar-wushka {
                padding: 0;
                height: 75px;
                min-height: 75px;
            }
      		.nav.navbar-nav {
                width: 410px;
                min-width: 410px;
            	height: auto;
            }

            .navbar-nav .container {
            	width: 100%;
            }

            .btn-navbar-wushka {
                width: 130px;
            }

            .nav.navbar-nav .btn {
            	width: 120px;
            }

            .nav.navbar-nav .btn.btn-login {
              width: 100px;
			  margin: 0;
			  height: 44px;
			  line-height: 23px;
			}

            .nav.navbar-nav .menu-drop {
				display: inline-block;
            	position:relative;
                float: left;
                margin: 0 10px;
            }

            .row {
            	margin-left: 0px!important;
            	margin-right: 0px!important;
            }

            /* url(http://cdn1.wushka.com.au/Resources/home-prelogin-banner2.jpg);
                    url(http://cdn1.wushka.com.au/Resources/home-prelogin-banner3.jpg); */
			.feature-set {
				float:none!important;
				margin: 0 auto;
				padding-top: 30px;
			}

			.features {
				padding: 0px!important;
			}

			.feature-image {
				padding: 0px!important;
				border: solid 2px rgba(0, 0, 0, 0)!important;
				min-height: 10%;
			}

			.feature-learn-more {
				background: #444;
			}

			.feature-learn-more h2 {
				margin: 20px 0px 5px!important;
			}

			.feature-learn-more p {
				margin: 10px!important;
			}

			.feature-sub-section {
				color:#444;
				padding: 0px 50px!important;
			}

			.feature-video {
				padding: 0px!important;
			}


			.dummy-image {
				height: 350px;
				background:#444;
				padding-top: 150px;
			}
			.dummy-video {
				height: 550px;
				background:#444;
				padding-top: 250px;
			}
			.feature-text {
				font-size: 1.6rem;
				margin-top: 3%!important;
			}
			.feature-step {
				padding: 20px 100px!important;
				font-size: 1.6rem;
				height: 200px;
			}

			.feature-step .feature-glyph {
				  width: 80px;
				  border-radius: 40px;
				  background: #FFF;
				  height: 80px;
				  color: #444;
				  padding-top: 3px;
				  text-align: center;
				  margin: 0 auto;
			}

			.feature-text h2 {
				margin: 25px 0 15px 0;
			}

			.feature-text .feature-glyph {
				  height: 60px;
				  width: 60px;
				  border-radius: 30px;
				  margin: 20px auto 10px;
			}

			.feature-glyph span {
				font-size: 50px;
			}

			.feature-text p {
				font-size: 2rem;
				line-height: 1.6;
			}



            .section.banner-section.splash-banner {
                display: block;
                position: relative;
                height: 250px;
                background-size: 100% 140%;
                background-repeat: no-repeat;
                background-image: url('<?php echo get_template_directory_uri() . '/img/feature_school_4.jpg'; ?>');
                background-color: #EDE9DE;
                color: #444;

                /*-webkit-animation-name: splashbanner;
        		-webkit-animation-duration: 30s;
                -webkit-animation-iteration-count: infinite;
                -webkit-animation-timing-function: ease-in-out;*/
            }


            @-webkit-keyframes splashbanner {
                0%   {background-image: url('<?php echo get_template_directory_uri() . '/img/class_hands2.jpg'; ?>');}
                33%  {background-image: url('<?php echo get_template_directory_uri() . '/img/class_hands2.jpg'; ?>');}
                36%  {background-image: url(http://cdn1.wushka.com.au/Resources/home-prelogin-banner2.jpg);}
                63%  {background-image: url(http://cdn1.wushka.com.au/Resources/home-prelogin-banner2.jpg);}
                66%  {background-image: url(http://cdn1.wushka.com.au/Resources/home-prelogin-banner3.jpg);}
                96%  {background-image: url(http://cdn1.wushka.com.au/Resources/home-prelogin-banner3.jpg);}

    		}

            .banner-text.heading {
                color: #FFF;
                min-width: 500px;
                padding: 70px 10px;
            	text-align: left;
            	font-weight: normal;

          	}
            .banner-text.heading h1 {
            	font-size: 60px;
               /* font-family: DINWeb, Arial, Helvetica, sans-serif; */
				font-weight: 700;
            	text-shadow: 3px 2px 3px #333;
            	color: #FFA500;
            }
            .banner-text.heading h2 {
                font-size: 2rem;
            	/* font-family: DINWeb, Arial, Helvetica, sans-serif; */
				font-weight: bold;
            	margin-top: 10px;
            	text-shadow: 2px 2px 2px #333;
            }
            .wrapper-main {
                margin-bottom: 0px!important;
            }
            .wrapper-main:after {
                height: 0px!important;
            }

            main[role="main"] {
                background: linear-gradient(#FFF, #DDD);
                text-align: center;
                padding-bottom: 30px;
            }

        </style>
        <script type="text/javascript">var _sf_startpt = new Date().getTime();</script>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msvalidate.01" content="B7ED223AB106964EF6B6CC0E1483AA00" />
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico">

        <?php wp_head(); ?>

        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.css">
        <!-- Fonts -->
        <link href="<?php echo get_template_directory_uri(); ?>/css/glyphicons.css" rel="stylesheet">
        <link href="<?php echo get_template_directory_uri(); ?>/css/glyphicons-bootstrap.css" rel="stylesheet">

        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style.css">
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/dataTable.css">

        <script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.dataTable.js"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.jeditable.js"></script>

        <!-- ------------------------------ Morris.js Charts ------------------------------ -->
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
        <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    </head>

    <body <?php body_class(); ?>>
        <?php get_template_part('analyticstracking'); ?>

        <!-- Fixed navbar -->
        <?php
        global $current_user;
        get_currentuserinfo();
        ?>
        <div class="wrapper-main">
           	<?php require_once('wireframe-header.php'); ?>
            <section class="section banner-section splash-banner col-lg-12">
                <div class="col-sm-offset-2 col-sm-8 banner-text heading">
                    <h1 class="hero-heading page-heading banner-header">Features</h1>
                    <h2 class="sub-heading">Discover more about the Wushka features for schools</h2>
                </div>
            </section>
<main role="main">
	<div class="container-fluid">
		<div class="feature-set col-sm-8">
	    	<div class="features" style="background:#50B4D0;">
	        	<div class="row">
	            	<div class="col-sm-7 feature-image feature-1">
	                	<img class="img-responsive" title="Teacher Dashboard" src="<?php echo get_template_directory_uri() . '/img/feature_school_1.jpg'; ?>" />
<!-- 	            		<div class="dummy-image">Insert Image Here</div> -->
	                </div>
	                <div class="col-sm-5 feature-text">
	                	<div class="feature-glyph">
	                    	<span class="glyphicon glyphicon-show-thumbnails"></span>
	                    </div>
	                    <h2>Take Control</h2>
	                    <p>Our Teacher Dashboard gives you full access to everything you need to get underway</p>

	                </div>
	            </div>
	       	</div>
        </div>
        <div class="feature-set col-sm-8">
	    	<div class="features" style="background:#693F69;">
	        	<div class="row">
	            	<div class="col-sm-5 feature-text">
	                	<div class="feature-glyph">
	                    	<span class="glyphicon glyphicon-group"></span>
	                    </div>
	                    <h2>Manage Your Class</h2>
	                    <p>Set reading groups and define the level of difficulty best suited for each student</p>
	                </div>
	                <div class="col-sm-7 feature-image">
	                	<img class="img-responsive" title="Manage Class List" src="<?php echo get_template_directory_uri() . '/img/feature_school_2.jpg'; ?>" />
<!-- 	            		<div class="dummy-image">Insert Image Here</div> -->
	                </div>
	            </div>
	       	</div>
        </div>
        <div class="feature-set col-sm-8">
	    	<div class="features" style="background:#C71E24;">
	        	<div class="row">
	            	<div class="col-sm-7 feature-image">
	            		<img class="img-responsive" title="Student Statistics" src="<?php echo get_template_directory_uri() . '/img/feature_school_3.jpg'; ?>" />
<!-- 	            		<div class="dummy-image">Insert Image Here</div> -->
	                </div>
	                <div class="col-sm-5 feature-text">
	                	<div class="feature-glyph">
	                    	<span class="glyphicon glyphicon-charts"></span>
	                    </div>
	                    <h2>Analyse Statistics</h2>
	                    <p>Quickly view overall performance, or dive into a thorough analysis of individual student progress</p>
	                </div>
	            </div>
	       	</div>
        </div>
        <div class="feature-set col-sm-12">
	    	<div class="features" style="background:#444;">
	        	<div class="row">
	        		<div class="col-sm-12 feature-learn-more">
	                	<h2>How it Works</h2>
	                </div>
	            	<div class="col-sm-4 feature-step">
	                	<div class="feature-glyph">
	                    	<span>1</span>
	                    </div>
	                    <p>Wushka has provided all Australian Schools with a free pass to our site</p>
	                </div>
	                <div class="col-sm-4 feature-step">
	                	<div class="feature-glyph">
	                    	<span>2</span>
	                    </div>
	                    <p>Ask your school administrator to sign on and create a teacher profile for you</p>
	                </div>
	                <div class="col-sm-4 feature-step">
	                	<div class="feature-glyph">
	                    	<span>3</span>
	                    </div>
	                    <p>Sign in and setup your first class</p>
	                </div>
	                <div class="col-sm-12 feature-learn-more">
	                <p>Want to Learn More? Enrol your School or enrol as a teacher etec etc  - speak to your School Administrator, See the Help Centre or Contact Us</p>
	                </div>
	            </div>
	       	</div>
        </div>
        <div class="feature-set col-sm-8">
	    	<div class="features" style="background:#444;">
	        	<div class="row">
	            	<div class="col-sm-12 feature-video">
	            		<div class="embed-responsive embed-responsive-16by9">
 						  	<video controls src="http://cdn1.wushka.com.au/Resources/manage-class-list.mp4" class="embed-responsive-item"></video>
 						</div>
	                </div>
	            </div>
	       	</div>
        </div>
        <div class="feature-set col-sm-8">
            <div class="row">
	            <div class="col-sm-12 feature-sub-section">
	                <h2>Want to Know More?</h2>
	                <p>Still Not Sure? Why not take a look at our FAQ page?</p>
	                <a href="/features-for-schools/" class="btn btn-primary">Frequently Asked Questions</a>
	            </div>
	        </div>
        </div>
        <div class="feature-set col-sm-12">
        	<div class="row">
        		<div class="col-sm-12 feature-learn-more">
	                <p>Want to learn more? Take a look at our <a href="#">Features</a> page, or if you have any questions why not read through some of our <a href="#">Frequently Asked Questions</a></p>
	            </div>
        	</div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<?php get_footer(); ?>