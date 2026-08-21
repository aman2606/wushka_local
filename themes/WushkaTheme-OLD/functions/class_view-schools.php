<?php

/**
 * Created by PhpStorm.
 * User: Jordan
 * Date: 22/09/2015
 * Time: 2:20 PM
 */
class View_Schools {

    private $a_result;
    private $o_user;
    private $a_ajax;

    function __construct() {
        //Define Constants
        $this->a_result = array(
            'status'  => 0,
            'message' => NULL,
            'data'    => array()
        );

        $this->o_user = NULL;

    }

    public function get_result() {
        return $this->a_result;
    }

    /* ----- Has Access ----- *
     * Set Permissions to view this page here
     */
    public function has_access() {
        if( is_user_logged_in() ) {
            $o_current = wp_get_current_user();

            #TODO: Re-add user requirements after testing
            return TRUE;
            if( user_can($o_current, 'bdm') ) {
                $this->o_user = $o_current;

                return TRUE;
            }
        }

        return FALSE;

    }

    /* ----- Redirect ----- *
     * Set Redirects Parameters here
     */
    public function redirect() {
        error_log('View Schools: User does not have access, redirect');
        wp_redirect(home_url('/'));
        exit();
    }

    /* ----- Load Page ----- *
     * Return All Page Data through here
     */
    public function load_page() {

        $a_page = array();

        return implode('', $a_page);
    }

    public function load_search_schools() {
        return $this->get_search_fields();
    }

    private function get_search_fields() {
        // updated Feb 2019 to prevent count_total performing slow query
        $q_schools = new WP_User_Query(array('role' => 'school', 'count_total'   => false));  // args updated for slow query
        $a_search  = array();

        if( isset($q_schools->results) && ! empty($q_schools->results) ) {
            foreach( $q_schools->results as $o_school ) {
                $a_terms    = wp_get_object_terms($o_school->ID, 'school');
                $o_term     = (! is_wp_error($a_terms)) ? $a_terms[0] : NULL;
                $a_search[] = array(
                    'ID'      => $o_school->ID,
                    'term_id' => isset($o_term) ? $o_term->term_id : NULL,
                    'name'    => isset($o_term) ? $o_term->name : NULL,
                    'slug'    => isset($o_term) ? $o_term->slug : NULL,
                );

            }
        }

        // error_log('Found ' . count($a_search) . ' schools');
        return $a_search;
    }

    public function validate_ajax() {

        $a_variables = array();

        //Validation
        $s_validate = json_decode(trim(stripcslashes(filter_input(INPUT_POST, 's_validate'))));
        if( ! isset($s_validate) || empty($s_validate) || ! wp_verify_nonce($s_validate, 'bisdev_validation') ) {
            error_log('View School Ajax Validation Failed: ' . $s_validate);
            $this->a_result['message'] = 'Invalid Ajax Field';

            return FALSE;
        }

        //Function to be run
        $s_type = json_decode(trim(stripcslashes(filter_input(INPUT_POST, 's_type'))));
        if( ! isset($s_type) || empty($s_type) ) {
            error_log('View School Ajax Type Failed: ' . $s_type);
            $this->a_result['message'] = 'Invalid Ajax Type';

            return FALSE;
        }

        //Variable 1
        $s_var_1 = json_decode(trim(stripcslashes(filter_input(INPUT_POST, 's_var_1'))));
        if( ! isset($s_var_1) || empty($s_var_1) ) {
            error_log('View School Ajax Variabel 1 Failed: ' . $s_var_1);
            $this->a_result['message'] = 'Invalid Ajax Data';

            return FALSE;
        } else {
            $a_variables['var_1'] = $s_var_1;
        }

        $this->a_ajax = array(
            'function' => $s_type,
            'data'     => $a_variables
        );

        return TRUE;
    }

    public function run_function() {
        if( ! isset($this->a_ajax['function']) || empty($this->a_ajax['function']) ) {
            return FALSE;
        }

        $f_ajax = 'ajax_' . $this->a_ajax['function'];

        //Run Ajax Function
        return $this->{$f_ajax}();
    }

    private function ajax_load_school() {
        error_log('Running ajax_load_School');
        $this->a_result['message'] = 'Running Load School Ajax Function';
        $i_school                  = $this->a_ajax['data']['var_1'];
        //Get School User
        $o_user = get_user_by('id', $i_school);
        if( isset($o_user) && $o_user !== FALSE ) {
            //Get School Term
            $a_terms = wp_get_object_terms($o_user->ID, 'school');
            $o_term  = $a_terms[0];
            //Build Return HTML
            $s_html                            = $this->html_school_details($o_user, $o_term);
            $this->a_result['status']          = 1;
            $this->a_result['data']['details'] = $s_html;

            return TRUE;
        } else {
            $this->a_result['message'] = 'No User found (id=' . $i_school . ').';
        }

        return FALSE;
    }

    private function html_school_details( $o_user, $o_term ) {
        if( ! isset($o_user, $o_term) ) {
            return FALSE;
        }

        #TODO: CHECK HAS ACTIVE SUB
        #TODO: LOAD Subscription Details
        // $a_options = get_option('taxonomy_' . $o_term->term_id);

        $a_html = array();

        $a_html[] = '<div id="details-wrap" data-school="' . $o_user->id_hash . '">';
            $a_html[] = '<div class="customer-details">';
                $a_html[] = '<h3>' . $o_user->user_login . '</h3>';
                $a_html[] = '<div>Customer: <span class="details">#' . $o_term->slug . '</span></div>';
                $a_html[] = '<div>School: <span class="details">' . $o_term->name . '</span></div>';
                $a_html[] = '<div>Address: <span class="details">' . $o_term->description . '</span></div>';
            $a_html[] = '</div>';
            $a_html[] = '<div class="customer-subscription">';
                $a_html[] = $this->load_school_subscription_html($o_user, $o_term);
            $a_html[] = '</div>';
        $a_html[] = '</div>';

        return implode('', $a_html);
    }

    private function load_school_subscription_html( $o_user, $o_term ) {
        $a_subscription = $this->load_school_subscription($o_user, $o_term );

        $a_prices = wushka_get_price_brackets();

        $a_html = array();

        $i_discount_price = NULL;
        if( isset($a_subscription['discount']) ) {
            $i_discount_price = wushka_get_discount_minus_tax($o_term->term_id, $a_subscription['discount']);
        }

        #TODO: SORT OUT FREE TRIAL FUNCTIONALITY WITH WOOCOMMERCE

        //Load Read-Only Info
        if( isset($a_subscription['status']) ) {
            $a_html[]   = '<p><label>Subscription Status: ' . $a_subscription['status'] . '</label></p>';
            $a_html[]   = '<p><label>Order No. : ' . $a_subscription['order_id'] . '</label></p>';
            $a_html[]   = '<p><label>Price: ' . $a_subscription['price'] . '</label></p>';
            $s_discount = 'No Discount Given';
            if( isset($a_subscription['discount']) ) {
                $s_discount = '$' . $i_discount_price;
            }
            $a_html[] = '<p><label>Discount Amount: ' . $s_discount . '</label></p>';
            $s_date   = '-none-';
            if( isset($a_subscription['discount_exp']) ) {
                $s_date = $a_subscription['discount_exp'] . ' (YYYY-MM-DD)';
            }
            $a_html[] = '<p><label>Discount Expires: ' . $s_date . '</label></p>';

            $s_tdate = '-none-';
            if( isset($a_subscription['trial_exp']) ) {
                $s_tdate = $a_subscription['trial_exp'] . ' (YYYY-MM-DD)';
            }
            $a_html[] = '<p><label>Trial Expires: ' . $s_tdate . '</label></p>';
        } else {
            $a_html[] = '<p><h3>Customise Subscription</h3></p>';
            //Add Price Bracket
            $a_html[] = '<div class="form-group"><label class="control-label col-sm-4">Price Bracket:</label>';
            $a_html[] = '<div class="col-sm-8"><select id="new_sub_price" name="new_sub_price">';

            $s_selected = NULL;



            foreach( $a_prices as $idx => $a_bracket ) {
                $s_selected = NULL;
                if( isset($a_subscription['price']) && (int)$a_subscription['price'] == (int)$a_bracket['price'] ) {
                    $s_selected = 'selected="selected"';
                }

                $a_html[]   = '<option ' . $s_selected . ' value="' . $a_bracket['price'] . '">$' . $a_bracket['price'] . ' - [' . $a_bracket['low'] . '-' . $a_bracket['high'] . ']</option>';
            }

            $a_html[] = '</select></div></div>';
            //Add Discount Price
            $a_html[] = '<div class="form-group discount-form-group"><label class="control-label col-sm-4">Discount ($AUD)</label>';
            $a_html[] = '<div class="col-sm-8"><input class="form-control" type="text" id="new_sub_discount" name="new_sub_discount" placeholder="Enter a price in dollars (AUD)" value="' . $i_discount_price . '"/></div></div>';
            //Add Discount Expiration Data
            $s_date   = isset($a_subscription['discount_exp']) ? $a_subscription['discount_exp'] : NULL;
            $s_tdate  = isset($a_subscription['trial_exp']) ? $a_subscription['trial_exp'] : NULL;
            $a_html[] = '<div class="form-group discount-expiration-date-form-group"><label class="control-label col-sm-4">Discount Expiration Data (YYYY-MM-DD):</label>';
            $a_html[] = '<div class="col-sm-8"><input class="form-control" type="text" id="new_sub_discount_exp" name="new_sub_discount_exp" placeholder="Enter a Date, example: 2015-12-05)" value="' . $s_date . '"/></div></div>';
            $a_html[] = '<div class="form-group"><label class="control-label col-sm-4">Trial Expiration Data (YYYY-MM-DD):</label>';
            $a_html[] = '<div class="col-sm-8"><input class="form-control" type="text" id="new_sub_trial_exp" name="new_sub_trial_exp" placeholder="Enter a Date, example: 2015-12-05)" value="' . $s_tdate . '"/></div></div>';
            $a_html[] = '<div><span class="pull-right"><button class="btn btn-primary btn-save" type="button">Save</button></span></div>';
        }

        return implode('', $a_html);
    }

    private function load_school_subscription( $o_user, $o_term ) {
        global $woocommerce;

        $a_options = array(
            'status'       => NULL,
            'price'        => NULL,
            'discount'     => NULL,
            'discount_exp' => NULL,
            'trial_exp'    => NULL,
            'order_id' => NULL
        );



        $subscriptions = WC_Subscriptions_Manager::get_users_subscriptions($o_user->ID);

        if( isset($subscriptions) && count($subscriptions) > 0 ) {
            foreach( $subscriptions as $idx => $a_sub ) {
                if( $a_sub['status'] == 'active' || $a_sub['status'] == 'pending' || $a_sub['status'] == 'processing' ) {
                    $a_options['status']   = $a_sub['status'];
                    $a_options['order_id'] = $a_sub['order_id'];
                    break;
                }
            }

        }
        $a_options['price']        = isset($o_user->user_subscriptionprice) ? $o_user->user_subscriptionprice : NULL;
        $a_options['discount']     = isset($o_user->user_subscriptiondiscount) ? $o_user->user_subscriptiondiscount : NULL;
        $a_options['discount_exp'] = isset($o_user->user_subscriptiondiscountexpiration) ? $o_user->user_subscriptiondiscountexpiration : NULL;
        $a_options['trial_exp']    = isset($o_user->user_subscriptiontrialexpiration) ? $o_user->user_subscriptiontrialexpiration : NULL;

        if ( ! isset($a_options['price']) ) {
            error_log('no price found, getting custom price');
            $a_options['price'] = wushka_custom_price(null, $o_term->term_id);
            error_log('custom price: ' . $a_options['price']);
        }

        return $a_options;
    }

    private function ajax_save_school() {
        //Variable 2 { ARRAY, not string }
        error_log(print_r($_POST, true));
        $a_var_2 = $_POST['s_var_2'];
        error_log(print_r($a_var_2, true));

        if( ! isset($a_var_2) || empty($a_var_2) ) {
            error_log('View School Ajax Variable 2 Failed: ' . $a_var_2);
            $this->a_result['message'] = 'Settings Array Failed Validation';

            return FALSE;
        }

        $o_school = get_user_by_hash($this->a_ajax['data']['var_1']);
        error_log('found school: ' . $o_school->ID);
        if( $o_school !== FALSE ) {
            $a_data = $a_var_2;
            //Update Subscription Price
//            if( isset($a_data['price']) && ! empty($a_data['price']) ) {
//                update_user_meta($o_school->ID, 'user_subscriptionprice', $a_data['price']);
//            }
//
//            //Update Discount Price
//            if( isset($a_data['discount']) && ! empty($a_data['discount']) ) {
//                update_user_meta($o_school->ID, 'user_subscriptiondiscount', $a_data['discount']);
//            }
//
//            //Update Price
//            if( isset($a_data['discount_exp']) && ! empty($a_data['discount_exp']) ) {
//                update_user_meta($o_school->ID, 'user_subscriptiondiscountexpiration', $a_data['discount_exp']);
//            }
//
//            //Update Subscription Price
//            if( isset($a_data['trial_exp']) && ! empty($a_data['trial_exp']) ) {
//                update_user_meta($o_school->ID, 'user_subscriptiontrialexpiration', $a_data['trial_exp']);
//            }
            $data = [
                'ID' => $o_school->ID,
                'user_subscriptionprice' => $a_data['price'],
                'user_subscriptiondiscount' => $a_data['discount'],
                'user_subscriptiondiscountexpiration' => $a_data['discount_exp'],
                'user_subscriptiontrialexpiration' => $a_data['trial_exp']
            ];
            $user_id = wp_update_user($data);
            
            if (is_wp_error($user_id)) {
                error_log('failed to save school pricing details');
            }
            
            $this->a_result['status']          = 1;
            $this->a_result['message'] = 'Settings Have Been Saved!';
            $this->a_result['data']['details'] = array('id' => $o_school->id_hash);
            return TRUE;
        } else {
            error_log('school is false');
        }

        return FALSE;
    }
}
