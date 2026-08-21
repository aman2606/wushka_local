<?php
/*
  Template Name: School License
  @deprecated
 */

//Is User Logged In AND is user a school?
if (!is_user_logged_in() || (!user_can($current_user, "school"))) {
    //Redirect to Login Page
    wp_redirect(home_url() . "/wp-login.php");
    exit;
}

$i_product_option1 = wc_get_product_id_by_sku('school-licence-copy');
$i_product_option2 = wc_get_product_id_by_sku('home-subscription');

$option1_url = get_permalink() . '?add-to-cart=' . $i_product_option1;
$option2_url = get_permalink() . '?add-to-cart=' . $i_product_option2;

$s_price = wushka_custom_price();

$b_active = school_has_active_sub($current_user->ID);


/* --- Deploy Page --- */
//Add Header
get_header();

global $woocommerce;

?>
<style>
    label.btn {
        margin: 10px;
    }
    a.btn {
        line-height: 1.6;
    }
</style>
<div class="container-fluid">
    <div class="row mt30">
        <div class="col-xs-12">
            <h1 class="glyphicon-heading"><span class="x2 glyphicon glyphicon-nameplate hidden-xs"></span><span class="glyphicon-heading-text">Wushka Site Licence</span></h1>
        </div>
        <section class='page-section padding-y grad-radial'>
            <div class="col-lg-5 col-sm-12">
                <div class="col-lg-10 col-lg-offset-1">
                    <?php the_content(); ?>
                </div>
            </div>
            <div class="col-lg-7 col-sm-12">
                <div class="col-lg-10 col-lg-offset-1">
                    <div class="options">
                        <?php if ( $b_active !== FALSE ) { ?>
                            <h2>Wushka Site Licence @ $<?php echo $s_price; ?> / year</h2>
                            <p><em>If you would like to change your chosen licence type,
                                   please get in contact with our <a href="<?php echo home_url('/contact-us/'); ?>">customer support staff here</a></em></p>
                        <?php } else { ?>
                            <a id="subscribe" href="<?php echo $option1_url ?>" role="button" class="btn btn-primary">Purchase Today</a>
                            <div class="option-details">Wushka User Licence @ $<?php echo $s_price ?> / year</div>
                        <?php } ?>
                    </div>
                    <div class="subscriptions">
                        <?php do_action( 'woocommerce_before_my_account' ); ?>
                    </div>
                    <div class="orders">
                        <?php wc_get_template('myaccount/my-licenses.php', array('order_count' => -1)); ?>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    jQuery(document).ready(function ($) {
        $(document).on('change', '#option1', function () {
            $('.option-details').html('Wushka Option 1. Wushka Site License @ $<?php echo $s_price ?> / year');
            $('#subscribe').attr('href', '<?php echo $option1_url ?>').show();
        });
        $(document).on('change', '#option2', function () {
            $('.option-details').html('Wushka Option 2. Home Subscription @ $0 / year');
            $('#subscribe').attr('href', '<?php echo $option2_url ?>').show();
        });
    });
</script>
<?php
include 'dashboard_options.php';
get_footer();
?>
