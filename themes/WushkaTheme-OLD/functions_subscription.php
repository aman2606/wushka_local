<?php
/** ----------- Functions for New Wushka Subscriptions Functions ----------
 * Created by PhpStorm.
 * User: Jordan
 * Date: 22/09/2015
 * Time: 12:46 PM
 */


/** Wushka Custom Price
 * ----------------------------------
 * Calculates customer price/discount for this school,
 * based on what category of school it belongs to (student numbers)
 *
 * @param object $cart_object - The WooCommerce Cart Object of the current checkout
 * @param int    $i_school    - The Taxonomy Term ID for the school that is attempting to checkout
 *
 * @return mixed $i_price - The Customized/Discounted price for this school
 **/
// function wushka_custom_price( $cart_object = NULL, $i_school = NULL ) {
//     $o_user = wp_get_current_user();

//     $i_price = NULL;

//     if( current_user_can('school') || current_user_can('bdm') ) {

//         if( ! isset($i_school) ) {
//             $school   = $terms = wp_get_object_terms($o_user->ID, 'school');
//             $i_school = $school[0]->term_id;
//         }
//         $a_options = get_option('taxonomy_' . $i_school);
//         if( isset($a_options) && isset($a_options['school_pupils']) ) {
//             $s_pupils = $a_options['school_pupils'];
//         } else {
//             $s_pupils = NULL;
//         }

//         $i_price = $o_user->user_subscriptionprice;
//         if( isset($i_price) && ! empty($i_price) ) {
//             error_log('school price set as: ' . $o_user->user_subscriptionprice);
//             $i_price = $i_price - custom_discount();
//         } else {
//             $i_price = wushka_calculate_school_price($s_pupils) - custom_discount();
//         }

//         //Is School Account?
//         // This will be your custom price, in dollar(AUD)
//         //DOES NOT INCLUDE GST

//         if( isset($cart_object) ) {
//             foreach( $cart_object->cart_contents as $key => $value ) {
//                 $value['data']->price = $i_price;
//             }
//         } else {
//             return $i_price;
//         }
//     }

//     return $i_price;
// }

// add_action('woocommerce_before_calculate_totals', 'wushka_custom_price');

// function custom_discount() {
//     //Get Discount Price From School User
//     $o_user     = wp_get_current_user();
//     $i_discount = $o_user->user_subscriptiondiscount;
//     $s_exp      = $o_user->user_subscriptiondiscountexpiration;

//     $expiry = new DateTime($s_exp);
//     $today  = new DateTime();

//     if( $expiry < $today ) {
//         error_log('discount expired');
//         $i_discount = 0;
//     }

//     return $i_discount;
// }

// function filter_woocommerce_subscriptions_product_trial_length( $subscription_trial_length, $product ) {
//     $o_user = wp_get_current_user();
//     if( current_user_can('school') ) {

//         if( isset($o_user->user_subscriptiontrialexpiration) ) {
//             $d_date = $o_user->user_subscriptiontrialexpiration;

//             $expiry = new DateTime($d_date);
//             error_log('todays date:' . date('Y-m-d'));
//             $today = new DateTime(date('Y-m-d'));

//             //Get Days From now until trial expiration
//             $days = $today->diff($expiry);
//             error_log('days difference between today and expiry: ' . $days->format('%r%a'));

//             if( $days->format('%r%a') > 0 ) {
//                 $subscription_trial_length = $days->format('%r%a');
//             }
//         }
//         error_log('filter subscription length: ' . $subscription_trial_length);

//     }

//     return $subscription_trial_length;

// }

// add_filter('woocommerce_subscriptions_product_trial_length', 'filter_woocommerce_subscriptions_product_trial_length', 10, 2);

// function filter_woocommerce_subscriptions_update_users_role( $update_role = TRUE, $user, $role_name ) {
//     if( ! empty($user->roles) && in_array('school', $user->roles) ) {
//         $update_role = FALSE;
//     }

//     return $update_role;
// }

// add_filter('woocommerce_subscriptions_update_users_role', 'filter_woocommerce_subscriptions_update_users_role', 10, 2);

//function filter_woocommerce_add_hidden_order_items( $order_items ) {
//    $order_items[] = '_subscription_interval';
//    $order_items[] = '_subscription_length';
//    $order_items[] = '_subscription_period';
//    $order_items[] = '_subscription_trial_length';
//    $order_items[] = '_subscription_trial_period';
//    $order_items[] = '_subscription_recurring_amount';
//    $order_items[] = '_subscription_sign_up_fee';
//    $order_items[] = '_recurring_line_total';
//    $order_items[] = '_recurring_line_tax';
//    $order_items[] = '_recurring_line_subtotal';
//    $order_items[] = '_recurring_line_subtotal_tax';
//
//    error_log('filtering unwanted meta');
//    return $order_items;
//}
//add_filter( 'woocommerce_hidden_order_itemmeta', 'filter_woocommerce_add_hidden_order_items' );

/* Calculate School Price
 * ---------------------------
 * Processes the School Pupils Count String to determine
 * appropriate subscription price for this school
 *
 * @param null||string - $s_pupil - School Term Option value (school_pupils)
 *
 * @return null||int - $i_price - Calculated Price, in AUD dollars
 */
// function wushka_calculate_school_price( $s_pupil = NULL ) {
//     $i_average = NULL;
//     //Process Pupil option string to determine average pupil count
//     //Determine Pupil Count Procedure:
//     //The Following pupil formats get default price:
//     //- NULL or empty
//     //- negative numbers
//     //- less than #
//     //- MTA Pricing Group Letter(character)

//     //Attempt to get standard bracket string (2 numbers on either side of a '-'
//     $a_range = explode('-', $s_pupil);

//     if( count($a_range) == 2 ) {
//         if( isset($a_range[0]) && isset($a_range[1]) ) {
//             $i_low     = (int)$a_range[0];
//             $i_high    = (int)$a_range[1];
//             $i_average = ($i_low + $i_high) / 2;
//         }
//     } else if( count($a_range) == 1 ) {
//         $i_range   = (int)$a_range[0];
//         $i_average = (is_int($i_range)) ? $i_range : NULL;
//     }

//     //Get Price for school (based on Average Pupil)
//     $i_price = wushka_get_school_price($i_average);

//     error_log('calcualted price as: ' . $i_price . ', averge: ' . $i_average);

//     return $i_price;
// }

// function wushka_get_school_price( $i_pupils = NULL ) {
//     //Get Brackets, sort descending (Highest First)

//     $a_brackets = wushka_get_price_brackets();
//     $i_default  = wushka_get_default_price();

//     if( ! isset($i_pupils) || empty($i_pupils) ) {
//         return $i_default;
//     }

//     //Starts at largest bracket and moves down
//     $i_price = NULL;
//     foreach( $a_brackets as $idx => $a_bracket ) {
//         //Check if pupil is bigger than current low,
//         if( $i_pupils >= $a_bracket['low'] ) {

//             //If number is lower than the current high
//             //OR
//             //If in highest bracket
//             //store price;
//             if( ($i_pupils <= $a_bracket['high']) || $idx == 0 ) {
//                 $i_price = $a_bracket['price'];
//                 break;
//             }
//         }
//     }

//     if( ! isset($i_price) ) {
//         error_log('An Error Setting the price occurred, returning Default Price');

//         return $i_default;
//     }

//     error_log('Determined School Price based on avg. pupils(' . $i_pupils . '): $' . $i_price);

//     return $i_price;
// }

/* ---------- GET PRICE BRACKETS ----------
 * PRICES DOES NOT INCLUDE GST
 * PRICE IN DOLLARS (AUD)
*/
// function wushka_get_price_brackets() {
//     $a_brackets = array(
//         array(
//             'low'   => 750,
//             'high'  => NULL,
//             'price' => 12735
//         ),
//         array(
//             'low'   => 650,
//             'high'  => 749,
//             'price' => 11325
//         ),
//         array(
//             'low'   => 550,
//             'high'  => 649,
//             'price' => 9735
//         ),
//         array(
//             'low'   => 450,
//             'high'  => 549,
//             'price' => 8235
//         ),
//         array(
//             'low'   => 350,
//             'high'  => 449,
//             'price' => 6735
//         ),
//         array(
//             'low'     => 250,
//             'high'    => 349,
//             'price'   => 5235,
//             'default' => TRUE
//         ),
//         array(
//             'low'   => 150,
//             'high'  => 249,
//             'price' => 3735
//         ),
//         array(
//             'low'   => 50,
//             'high'  => 149,
//             'price' => 2235
//         ),
//         array(
//             'low'   => 0,
//             'high'  => 49,
//             'price' => 1225
//         ),
//     );

//     return $a_brackets;
// }

//Returns the Default Price, As set in the price brackets array
// function wushka_get_default_price() {
//     $a_brackets = wushka_get_price_brackets();
//     foreach( $a_brackets as $idx => $a_bracket ) {
//         if( isset($a_bracket['default']) && $a_bracket['default'] === TRUE ) {
//             return $a_bracket['price'];
//         }
//     }
// }

// function wushka_get_discount_minus_tax( $i_school, $i_discount ) {
//     if( ! isset($i_school, $i_discount) ) {
//         return NULL;
//     }

//     return $i_discount;

// //    $i_tax = 0; //In %
// //    //Get Country from term options
// //    $a_options = get_option('taxonomy_'.$i_school);
// //    $s_country = isset($a_options['school_country']) ? trim($a_options['school_country']) : NULL;
// //    //If no country found : 0 tax
// //    if ( ! isset($s_country) ) {
// //        $i_tax = 0;
// //    } else {
// //        //Get Country Tax
// //        #TOOD: Clarify Return Tax objects
// //        $a_rates = WC_Tax::find_rates(array('country'=>$s_country));
// //        if ( isset($a_rates) && count($a_rates) > 0 ) {
// //            $i_tax = $a_rates[0]['rate'];
// //        }
// //    }
// //    #TODO: Test calculation & result return: Get Tax Not Working.
// //    $i_tax = 10;
// //
// //    if ( $i_tax > 0 ) {
// //        $i_actual = ($i_discount / 100) * $i_tax;
// //        $i_raw_discount = $i_discount - $i_actual;
// //    } else {
// //    $i_raw_discount = $i_discount;
// //    }
// //    return $i_raw_discount;
// }

//Run To determine if school has a subscription of certain status that
//count as 'active' subscriptions
// function school_has_active_sub( $school ) {

//     global $wpdb;

//     // if( ! user_can($user, 'school') ) {
//     //     return FALSE;
//     // }

//     //Accepted Statuses for active subs:
//     // $a_valid = array(
//     //     'active',
//     //     'pending',
//     //     'processing',
//     //     'on-hold'
//     // );
//     $valid = array(
//         'trial',
//         'subscription',
//     );

//     // $i_product_option1 = wc_get_product_id_by_sku('school-licence-copy');
//     $b_active          = FALSE;

//     $query = 'SELECT * FROM '.$wpdb->prefix.'wushka_licence WHERE account_id = ' . $school->slug;
//     error_log('licence query ' . $query);
//     $results = $wpdb->get_results($query);

//     error_log('licence results ' . print_r($results, true));
//     if (!empty($results)) {
//         if (in_array($results[0]->licence_type, $valid)) {
//             $b_active = 1;
//         }
//     }

//     // $subscriptions = WC_Subscriptions_Manager::get_users_subscriptions($i_user);
//     // if( ! empty($subscriptions) ) {
//     //     foreach( $subscriptions as $subscription ) {
//     //         if( $subscription['product_id'] == $i_product_option1 && in_array($subscription['status'], $a_valid) ) {
//     //             $b_active = 1;
//     //             break;
//     //         }
//     //     }
//     // }

//     return $b_active;
// }

// add_filter('woocommerce_email_attachments', 'wushka_add_invoice_to_emails', 10, 3);
// function wushka_add_invoice_to_emails( $attachments, $id, $object ) {
//     if( $id == 'new_order' ) {
//         include_once(ABSPATH . 'wp-admin/includes/plugin.php');
//         if( is_plugin_active('pdf-stamp-invoicer/pdf-stamp-invoicer.php') ) {
//             $i_order = $object->get_order_number();

//             $c_invoice = new Stamp_Invoice_Plugin();

//             if( $c_invoice->generate_tmp_invoice($i_order) === TRUE ) {
//                 $attachments[] = $c_invoice->s_tmp_path;
//             }
//         }
//     }

//     return $attachments;
// }

/* ----- END OF FILE ----- */
