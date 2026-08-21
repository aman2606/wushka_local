<?php
/*
  Template Name: Tablet-Company
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

            .nav.navbar-nav .dropdown {
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

			.feature-faq {
				font-size: 1.8rem;
				text-align: left;
			}

			.feature-faq .badge {
				margin: 7px 0;
			}



			.section.banner-section.splash-banner {
                display: block;
                position: relative;
                min-height: 550px;
            	padding:0px!important;
               	background-size: 100% 800px;
                background-repeat: no-repeat;
                background-color: #EDE9DE;
            	transition: background 0.4s;
				-webkit-transition: background 0.4s;
            	background-position: 0px -70px;


                /*-webkit-animation-name: splashbanner;
        		-webkit-animation-duration: 30s;
                -webkit-animation-iteration-count: infinite;
                -webkit-animation-timing-function: ease-in-out;*/
            }
           .splash-banner.faq-1 {
     			background: url('<?php echo get_template_directory_uri() . '/img/info_about_1.jpg'; ?>');
     			background-position-y: -40px!important;
            }
            .splash-banner.faq-2 {
				background: url('<?php echo get_template_directory_uri() . '/img/info_contact_1.jpg'; ?>');
          	}
            .splash-banner.faq-3 {
				background: url('<?php echo get_template_directory_uri() . '/img/info_tc_1.jpg'; ?>');
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
                margin: 0 auto;
                padding: 0px;
            	text-align: center;
            	font-weight: normal;
				position: absolute;
            	top: 130px;
            	width: 100%;
            }
            .banner-text.heading h1 {
            	font-size: 6.5rem;
                font-family: DINWeb, Arial, Helvetica, sans-serif;
				font-weight: normal;
            	color: #FFF;
            }
            .banner-text.heading h2{
                font-size: 3rem;
            	font-family: DINWeb, Arial, Helvetica, sans-serif;
				font-weight: normal;
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

            .panel-link:focus {
            	color: #444!important;
            	text-decoration: none!important;
            }
            .panel-heading:hover {
            	background: #F5F5F5;
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
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(document).on('click', 'a[href="#faq-1"]', function(e){
					if ( ! $('.splash-banner').hasClass('faq-1') ) {
						$('.splash-banner').addClass('faq-1');
						$('.splash-banner').removeClass('faq-2');
						$('.splash-banner').removeClass('faq-3');
					}
				});
				$(document).on('click', 'a[href="#faq-2"]', function(){
					if ( ! $('.splash-banner').hasClass('faq-2') ) {
						$('.splash-banner').removeClass('faq-1');
						$('.splash-banner').addClass('faq-2');
						$('.splash-banner').removeClass('faq-3');
					}
				});
				$(document).on('click', 'a[href="#faq-3"]', function(){
					if ( ! $('.splash-banner').hasClass('faq-3') ) {
						$('.splash-banner').removeClass('faq-1');
						$('.splash-banner').removeClass('faq-2');
						$('.splash-banner').addClass('faq-3');
					}
				});
			});
		</script>
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
            <section class="section banner-section splash-banner col-lg-12 faq-1">
                <div class="banner-text heading">
                    <h1 class="hero-heading page-heading banner-header">Wushka</h1>
                    <h2 class="sub-heading">Get to know us better</h2>
                </div>
            </section>
<main role="main">
	<div class="container-fluid">
		<div class="feature-set col-sm-10">
			<div class="row">
				<div class="feature-faq col-sm-3">
					<div class="panel panel-default">
					  	<div class="panel-body">
							<ul class="list-group">
								<a href="#faq-1" class="list-group-item" data-toggle="collapse" data-parent="#faq-wrap" aria-expanded="false" aria-controls="faq-1">
							    	About Us
							  	</a>
							  	<a href="#faq-2" class="list-group-item" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-2">
							    	Contact
							  	</a>
							  	<a href="#faq-3" class="list-group-item" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-3">
							    	Blog
							  	</a>
							</ul>
					  	</div>
					</div>
				</div>
				<div class="feature-faq col-sm-9 panel-group" id="faq-wrap" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default" role="tab">
						<a href="#faq-1" class="panel-link" data-toggle="collapse" data-parent="#faq-wrap" aria-expanded="false" aria-controls="faq-1"><div class="panel-heading">About Us</div></a>
						<div class="panel-body collapse" id="faq-1">
					  	</div>
					</div>
					<div class="panel panel-default" role="tab">
						<a href="#faq-2" class="panel-link" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-2"><div class="panel-heading">Contact</div></a>
					  	<div class="panel-body collapse" id="faq-2">
					  	</div>
					</div>
					<div class="panel panel-default" role="tab">
						<a href="#faq-3" class="panel-link" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-3"><div class="panel-heading">Blog Info</div></a>
					  	<div class="panel-body collapse"  id="faq-3">
					  	</div>
					</div>
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