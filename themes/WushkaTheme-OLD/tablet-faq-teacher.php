<?php
/*
  Template Name: Tablet-faq-teacher
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
                height: 250px;
            	padding:0px!important;
                background-size: cover;
                background-repeat: no-repeat;
                background-image: url('<?php echo get_template_directory_uri() . '/img/faq_2.jpg'; ?>');
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
                margin: 0 auto;
                padding: 0px;
            	text-align: center;
            	font-weight: normal;
				position: absolute;
            	top: 120px;
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
                <div class="banner-text heading">
                    <h1 class="hero-heading page-heading banner-header">Frequently Asked Questions</h1>
                    <h2 class="sub-heading">For Teachers and Schools</h2>
                </div>
            </section>
<main role="main">
	<div class="container-fluid">
		<div class="feature-set col-sm-10">
			<div class="row">
				<div data-spy="affix" data-offset-top="450" data-offset-bottom="400">
				<div class="feature-faq col-sm-3">
					<div class="panel panel-default">
					  	<div class="panel-heading">Category</div>
					  	<div class="panel-body">
							<ul class="list-group">
								<a href="#faq-1" class="list-group-item" data-toggle="collapse" data-parent="#faq-wrap" aria-expanded="false" aria-controls="faq-1">
							    	<span class="badge">4</span>
							    	General
							  	</a>
							  	<a href="#faq-2" class="list-group-item" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-2">
							    	<span class="badge">6</span>
							    	Subscription
							  	</a>
							  	<a href="#faq-3" class="list-group-item" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-3">
							    	<span class="badge">6</span>
							    	Payment
							  	</a>
							  	<a href="#faq-4" class="list-group-item" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-4">
							    	<span class="badge">10</span>
							    	Reading and Downloading eBooks
							  	</a>
							  	<a href="#faq-5" class="list-group-item" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-5">
							    	<span class="badge">2</span>
							    	Troubleshooting and Passwords
							  	</a>
							</ul>
					  	</div>
					</div>
				</div>
				</div>
				<div class="panel-group feature-faq col-sm-9" id="faq-wrap" role="tablist" aria-multiselectable="true">
					<div class="panel panel-default" role="tab">
						<a href="#faq-1" class="panel-link" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-1"><div class="panel-heading">General</div></a>
					  	<div class="panel-body collapse" id="faq-1">
					  		<p><b>Does Wushka support the English curriculum?</b></p>
							<p>Yes. Wushka's literacy programme aligns to the English curriculum however, it is not intended to be used as a standalone program. Wushka provides teachers with a means of utilizing 21st-century, digital technologies to complement classroom practice. </p>
							<p>As stated in the Australian curriculum*, literacy programs should aim �to development students� ability to interpret and create texts with appropriateness, accuracy, confidence, fluency and efficacy for learning in and out of school�. Wushka provides a safe platform where students can feel free to experiment and make mistakes on this journey and where teachers can easily monitor their ongoing progress. </p>
							<p>(*http://www.australiancurriculum.edu.au/english/content-structure/literacy)</p>
							<p><b>How can teachers use Wushka in their teaching practice?</b>
							<p>Wushka has been designed to complement traditional reading groups and literacy programs. With the freedom to use as many or as few of the features, Wushka can be used as a whole class, in small groups, with specialised reading support sessions or to supplement homework programmes. </p>
							<p>The eBooks within the Wushka library have been carefully aligned to different reading levels from Magenta (Levels 1-2) through to Black (Levels 31 ++) and they follow a reading recovery-like structure. The different shelves cater for a range of abilities so that you can select the most appropriate books for your students� needs.  </p>
							<p><b>Who has written the Wushka eBooks?</b></p>
							<p>Our eBooks have been created by a team of teachers, authors and designers and are published by world-renowned publisher, Learning Media. Each book has been carefully written and formatted with the aim of helping students develop their literacy skills and a love of reading. If you have further ideas for topics you would like to see covered in our library please contact us at <a href="mailto:publisher@wushka.com.au">publisher@wushka.com.au</a>.</p>
							<p><b>Can students access Wushka at home?</b></p>
							<p>Yes. Students have complete access to Wushka from their home devices when connected to the internet. They will need to use their password and username that is attached to their school account. </p>
					  	</div>
					</div>
					<div class="panel panel-default" role="tab">
					  	<a href="#faq-2" class="panel-link" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-2"><div class="panel-heading">Subscription</div></a>
					  	<div class="panel-body collapse" id="faq-2">
							<p><b>How do I become a member?</b></p>
							<p>To sign up for a Wushka membership visit our <a href="/subscription">Subscription page</a>. If you need further assistance, please do not hesitate to contact us on <a href="mailto:support@wushka.com.au">support@wuhska.com.au</a>.</p>
							<p><b>How long does an individual membership last?</b></p>
							<p>A membership lasts for one year, beginning on the date your account is activated.</p>
							<p><b>Can I login to Wushka from both school and home?</b></p>
							<p>Yes. If you�re a member you can sign into the site from any computer or device that is connected to the internet.</p>
							<p><b>I am home schooling my child, can I subscribe?</b></p>
							<p>Yes, of course however, we do not have any special subscription for home schooling teachers. We recommended the home subscription which provides you with 3 student licenses. Please see the parent subscription options for more information.</p>
							<p><b>I have an individual membership. Can I share my password with others?</b></p>
							<p>Members have unlimited access to the site for their own personal or classroom use. Sharing of personal login and password information is therefore prohibited. Accounts are monitored for this activity and may be suspended if evidence of login and password sharing is found.</p>
							<p><b>How do I cancel my subscription?</b></p>
							<p>Please visit the <a href="/my-account/">My Account</a> tab along the menu bar. Please note, immediately after clicking cancel, your subscription will be terminated and access to the Wushka website will be discontinued without a refund.
							You can easily renew your subscription after cancelling your account by visiting <a href-"/my-account/">My Account</a> and clicking �Renew� under the �My Subscriptions� section. However, you will need to begin the subscription process again and provide your credit card details for payment.</p>
					  	</div>
					</div>
					<div class="panel panel-default" role="tab">
					  	<a href="#faq-3" class="panel-link" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-3"><div class="panel-heading">Payment</div></a>
					  	<div class="panel-body collapse"  id="faq-3">
							<p><b>What type of payment do you accept?</b></p>
							<p>All of our payments go through the PayPal system for added security. You can set up your PayPal account to use direct debit or online transfer. If you do not have a PayPal account you can also use a credit card when using a Visa, MasterCard or Amex. Debit cards are accepted for payment, only if the card has a VISA or MasterCard symbol on it. Unfortunately, we do not accept any other form of payment.</p>
							<p><b>Is my credit card information secure when I pay for my Wushka membership?</b></p>
							<p>Yes. Wushka uses a secure third party payment gateway to process all credit card payments.</p>
							<p><b>How can I be sure that my Wushka payment has been processed?</b></p>
							<p>Shortly after completing the subscription process, you will receive a transaction summary confirming your account details and subscription package. If you do not receive this message, it may be blocked by your spam filter. In this case please check your spam filter and then add Wushka to your address list so that it does not happen again.</p>
							<p>If you are unsuccessful in retrieving your transaction summary, please login to the site directly with your email address and password. You can view your subscription summary and print off an invoice by visiting <a href="/my-account">My Account</a> on the Wushka website.</p>
							<p><u>If you fail to access the website directly � please contact us at <a href="mailto:support@wuhska.com.au">support@wushka.com.au</a>. DO NOT GO THROUGH THE PAYMENT PROCESS AGAIN. Please include your phone number in your email � if your inbox is blocked we will contact you by phone.</u></p>
							<p><b>Does my account automatically renew?</b></p>
							<p>Yes, your subscription will automatically renew unless you indicate you would not like this to occur. You will be notified by email when your subscription is coming up to renewal. Please DO NOT PRESS CANCEL to do this as your account will then be disabled. To remove the automatic payments please log into your PayPal account, access the "Profile" tab, click the �My money� tab, then "My Pre-Approved Payments" link, select and click Wushka and follow the instructions to cancel the payment. </p>
							<p><b>How do I get a receipt to claim the cost of membership for tax purposes?</b></p>
							<p>You can retrieve a copy of your receipt along with the status of your account simply by logging into the Wushka website. To do this visit your <a href="/my-account/">My Account</a> page and under the �Recent Orders� tab, click the PDF invoice button. </p>
							<p><b>Who can I contact if I have a payment query?</b></p>
							<p>Please contact us by email at <a href="mailto:support@wushka.com.au">support@wushka.com.au</a>.</p>
					  	</div>
					</div>
					<div class="panel panel-default" role="tab">
					  	<a href="#faq-4" class="panel-link" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-4"><div class="panel-heading">Reading and Downloading eBooks</div></a>
					  	<div class="panel-body collapse" id="faq-4">
							<p><b>How do I download an eBook?</b></p>
							<p>Unfortunately you cannot download our eBooks. Instead, all of the Wushka eBooks are accessible when logged into your account on any device that has an internet connection. </p>
							<p><b>What browsers are supported by Wushka and the Wushka eReader?</b></p>
							<p>Wushka supports Chrome for optimum results. It can be downloaded <a href="http://www.google.com/chrome/">here</a>. If you are using Internet Explorer however, we recommend that you update to the most recent version as it supports our eReader. If you have any doubts about your browser, please contact us at <a href="support@wushka.com.au">support@wushka.com.au</a> before subscribing. </p>
							<p><b>Are your eBooks compatible with devices other than desktop computers?</b></p>
							<p>Yes. Our eBooks are compatible with iPads version 3 and up, and iPad minis. Earlier versions of iPads unfortunately are not supported. </p>
							<p><b>How is the audio read?</b></p>
							<p>Depending on the reading level of the eBook, the audio is read word-by-word, sentence-by-sentence or paragraph-by-paragraph. The words are highlighted as the audio is read, and the pages turn automatically. If you would like to remain on a page longer than the audio accounts for, press the pause audio button from the controls in the top left-hand corner of the screen. </p>
							<p><b>Does every eBook come with audio?</b></p>
							<p>Yes, every eBook can be read with audio if audio narration has been enabled the teacher. To enable this as a teacher please go to your <a href="/my-class/">Manage Class List</a> page. To begin the audio when using the eReader, press the play button, located in the top left hand corner of the screen.</p>
							<p><b>How do I navigate the pages of an eBook?</b></p>
							<p>Our eBooks begin once you have pressed the play button, and pages will turn automatically after they have been read. You can also navigate the eBook without audio using the arrows on either side of the page. If you manually turn the page, and would like to continue narration, you will simply need to press the play button to start the audio again. </p>
							<p><b>How does the rating system work? (COMING SOON)</b></p>
							<p>On the completion of an eBook, the student provides a rating as to their individual preference and how difficult they found the eBook. Students can simply select the number of stars to indicate there preference level and then separately indicate, the eBook�s degree of difficulty. This feature will need to be explained to your class but it is simple to use then without further instruction.</p>
							<p><b>Where will I be able to view my students� ratings? </b></p>
							<p>In the Detailed Student Information page found on the Teacher Dashboard, you will soon be able to view the average ratings from each student. To see how each student has rated individual books, click on the Details link on this page where you will be directed to the <a href="/student-statistics/">Individual Student Statistics </a>page. </p>
							<p><b>How do the quizzes on the completion of a book work? </b></p>
							<p>On the completion of an eBook, students are required to complete around 5 multiple choice questions that assesses their comprehension level. This ensures that students do not simply flick through the pages of the book without reading them and provides teachers with the ability to monitor for meaning making. </p>
							<p><b>How are the quizzes relevant to the reading level? </b></p>
							<p>As the books become more advanced, so do the questions. Lower level quizzes use questions that determine recall and a basic understanding of the main ideas and concepts found within the text. Quizzes then progress into prompting students to apply and analyse knowledge. In the highest levels, students are provided with questions that encourage synthesis and evaluation of hypothetical scenarios related to the text.</p>
					  	</div>
					</div>
					<div class="panel panel-default" role="tab">
					  	<a href="#faq-5" class="panel-link" data-toggle="collapse" data-parent="#faq-wrap"  aria-expanded="false" aria-controls="faq-5"><div class="panel-heading">Troubleshooting and Passwords</div></a>
					  	<div class="panel-body collapse" id="faq-5">
							<p><b>I forgot my password.</b></p>
							<p>If you have forgotten your password please visit the Login page and click the �Lost Password?� link. You will then be prompted to enter the email or username that you used for your subscription. After completing your details click the �Reset Password� button and an email will then be forwarded to your nominated email address containing a link to create a new password.</p>
							<p>
							If you simply like to change your password after being able to login, please visit <a href="/my-account/">My Account</a> and click on the �change your password� link and follow the prompts.
							</p>
							<p><b>How do I reach Wushka technical support?</b></p>
							<p>Email us directly at <a href="mailto:techsupport@wushka.com.au">techsupport@wushka.com.au</a>. The Wushka support team will get back to you within 48 hours of receiving your request.</p>
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
