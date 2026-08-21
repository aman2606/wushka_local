<?php 
/*
 * Template Name: Renew Parent Subscription
 */
//Redirect Away from page if:
//- Not Logged In
//- Not Parent User
//- Has Active Sub
if ( ! is_user_logged_in() || ! user_can($current_user->id, 'parent') || wushka_has_active_sub($current_user->id, 'parent') ) {
    wp_redirect( home_url('/') );
    exit();
}

//Is Paying Customer or Trial User
$b_trial = FALSE;
$i_product = wc_get_product_id_by_sku('trial_subscription');
if ( WC_Subscriptions_Manager::user_has_subscription($current_user->ID, $i_product, 'expired') ) {
    $b_trial = TRUE;
}
?>

<?php get_header(); ?>
    <div class="container-fluid">
        <div class="row mt30">
            <div class="col-xs-12">
                <h1 class="glyphicon-heading">
                    <span class="x2 glyphicon glyphicon-user hidden-xs"></span>
                    <?php if ( $b_trial ) { ?>
                        <span class="glyphicon-heading-text">Your trial period has ended!</span>
                    <?php } else { ?>
                        <span class="glyphicon-heading-text">Your subscription needs to be renewed</span>
                    <?php } ?>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-4 col-md-offset-4 col-lg-6 col-lg-offset-3">
                <?php if ( $b_trial ) { ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>If you would like to continue to have access to all the features Wushka has to offer, you can subscribe now.</p>
                            <div class="col-xs-12 col-sm-6 col-sm-offset-3 button-wrap">
                                <div class="btn-group">
                                <button class="btn btn-primary btn-xl dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Subscribe Today<span class="caret" style="margin-left:5px !important;"></span>
                                </button>
                                <ul class="dropdown-menu subscription-dropdown-menu">
                                    <li><a href="/subscription/?add-to-cart=135"><strong>1 Child</strong> <span class="price-highlight">- $7.90 monthly</span></a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="/subscription/?add-to-cart=40099"><strong>Family Pack of 2-4 Children</strong> <span class="price-highlight">- $11.90 monthly</span></a></li>
                                </ul>
                            </div>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>To continue to experience everything Wushka has to offer, you can renew your subscription now.</p>
                            <div class="col-xs-12 col-sm-6 col-sm-offset-3 button-wrap">
                                <a href="<?php echo home_url('/my-account/'); ?>">
                                    <input type="button" class="btn btn-primary btn-block" value="Renew Subscription">
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php get_footer(); ?>
<?php /* ----- EOF ----- */ ?>