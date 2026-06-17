<?php
/*
 * Activate a New Wushka PARENT User Account
 */
if (!defined('ABSPATH')) exit; // Exit if accessed directly
include_once 'class_activate_user.php';

/* ----------- User Account Confirmation Functions ----------- */
class Activate_Parent extends Activate_User {

    public  $a_return;
    public  $a_params;
    public  $o_user;

    public function __construct() {
        $this->a_params = $this->prepare_params();
        $this->a_return = $this->prepare_return();
        $this->o_user = NULL;
        error_log('Activate User ( '.$this->a_params['role'].': Prepare Function');

        if ( $this->validate_user_parameters() ) {
            if ( $this->activate_user_password() ) {
                if ( $this->activate_subscription() ) {
                    $a_creds = array(
                        'user_login'    => $this->o_user->user_login,
                        'user_password' => $this->a_params['pwd'],
                        'remember'	    => FALSE
                    );

                    $user = wp_signon($a_creds, FALSE);
                    if ( is_wp_error($user) ) {
                        $this->a_return['message'] = 'Login Failed';
                        return FALSE;
                    }

                    return TRUE;
                }
            }
        }

        return FALSE;
    }

    private function prepare_params() {
        return array(
            'role'	=> 'parent',
            'code'	=> null,
            'pwd'	=> null,
            'hash'	=> null
        );
    }

    private function prepare_return() {
        return array(
            'status' 	=> 0,
            'url'		=> NULL,
            'message' 	=> 'New Parent',
            'data'      => array()
        );
    }

    private function activate_subscription() {
        global $woocommerce;
        //Get Subscription Product
        $i_product = wc_get_product_id_by_sku('trial_subscription');
        /*  $o_product = wc_get_product($i_product);

                  $a_args = array(
                      'customer_id'   => $this->o_user->ID
                  );
                //Create New Order
                  $o_order = wc_create_order($a_args);

                  $i_item = $o_order->add_product($o_product, 1);

                  woocommerce_update_order_item_meta($i_item, '_subscription_period',             'day');
                  woocommerce_update_order_item_meta($i_item, '_subscription_interval',           1    );
                  woocommerce_update_order_item_meta($i_item, '_subscription_length',             5    );
                  woocommerce_update_order_item_meta($i_item, '_subscription_trial_length',       0    );
                  woocommerce_update_order_item_meta($i_item, '_subscription_trial_period',       'day');
                  woocommerce_update_order_item_meta($i_item, '_subscription_recurring_amount',   0    );
                  woocommerce_update_order_item_meta($i_item, '_subscription_sign_up_fee',        0    );
                  woocommerce_update_order_item_meta($i_item, '_recurring_line_total',            0    );
                  woocommerce_update_order_item_meta($i_item, '_recurring_line_tax',              0    );
                  woocommerce_update_order_item_meta($i_item, '_recurring_line_subtotal',         0    );
                  woocommerce_update_order_item_meta($i_item, '_recurring_line_subtotal_tax',     0    );

                  WC_Subscriptions_Manager::create_pending_subscription_for_order($o_order->id,$o_product->id);
        */

        $this->a_return['message']  = 'Subscription Created';
        $this->a_return['status']   = 3;
        $this->a_return['url'] = home_url('/').'?add-to-cart='.$i_product;

        return TRUE;
    }
}
/* ----- EOF ----- */