<?php
/*
Template Name: Wushka - Subscription Upgrade
*/
?>

<?php 
	if ( ! is_user_logged_in() )
?>


<?php get_header(); ?>

<style type="text/css">
	.title-wrap, .sub-note {
		text-align: center;
	}
	.title-wrap {
		padding-top: 25px;
	}

	.sub-note {
		padding-bottom: 10px;
		line-height: 1.6;
	}
</style>

<div class="container-fluid">
    <div class="row">
    	<div class="col-sm-6 col-sm-offset-3 title-wrap">
    		<h2>You want to upgrade your account?</h2>
    	</div>
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 padding-y">
        	<div class="panel panel-default padding-y">
        		<div class="panel-body">
					<div class="col-xs-12">
						<div class="row">



<!-- 							<div class="col-sm-3">
								<div class="panel panel-default">
									<div class="panel-heading">
										<p>2 Children Licence</p>
									</div>
									<div class="panel-body">
										<p>$10.80 / Month</p>
										<a href="subscription/?add-to-cart=40099"><input type="button" class="btn btn-primary btn-block" value="2 Children"></a>
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="panel panel-default">
									<div class="panel-heading">
										<p>3 Children Licence</p>
									</div>
									<div class="panel-body">
										<p>$13.70 per Month</p>
										<a href="subscription/?add-to-cart=40100"><input type="button" class="btn btn-primary btn-block" value="3 Children"></a>
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="panel panel-default">
									<div class="panel-heading">
										<p>4 Children Licence</p>
									</div>
									<div class="panel-body">
										<p>$16.60 per Month</p>
										<a href="subscription/?add-to-cart=40101"><input type="button" class="btn btn-primary btn-block" value="4 Children"></a>
									</div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="panel panel-default">
									<div class="panel-heading">
										<p>5 Children Licence</p>
									</div>
									<div class="panel-body">
										<p>$19.50 / Month</p>
										<a href="subscription/?add-to-cart=40102"><input type="button" class="btn btn-primary btn-block" value="5 Children"></a>
									</div>
								</div>
							</div> -->
						</div>
						<div class="row">
							<div class="col-sm-6 col-sm-offset-3">
						 		<a href="<?php echo esc_url(home_url().'/my-account/'); ?>"><input type="button" class="btn btn-primary btn-block" value="Nevermind, Maybe Later"></a>
							</div>
						</div>
					</div>
				</div>
			</div><!-- END panel -->
		</div>
    </div>
</div>

<?php get_footer();
/* ----- EOF ----- */