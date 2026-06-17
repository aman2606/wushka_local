<?php

/**
 * Merges two large arrays based on a common field
 *
 * @param array $array1 The first array to merge
 * @param array $array2 The second array to merge
 * @param string $field The field name used as
 *
 * @return array An array containing the merged items
 */
function custom_merge_arrays(array $array1, array $array2, $field) {
    // Create an index of the first array using
    $result = array();
    $index = array();
    foreach ($array1 as $item1) {
        $index[$item1->$field] = (array)$item1;
    }
    unset($array1); //freeing memory
    // Iterate over the second array and merge items with matching fields
    foreach ($array2 as $item2) {
        if (isset($index[$item2->$field])) {
            $item1 = $index[$item2->$field];
            $result[] = (object)array_merge($item1, (array)$item2);
        }
    }
    return $result;
}

/**
 * Returns account IDs that have valid subscriptions or trials and are not expired.
 * 
 * @return array
 */
function get_valid_subscription_account_ids() {
    global $wpdb;
    $licence_table = $wpdb->prefix . "wushka_licence"; 
    $excluded_account_id_like = wushka_sql_esc_like('S1');

    return $wpdb->get_results($wpdb->prepare(
        "SELECT DISTINCT `account_id` FROM ".$licence_table." WHERE `licence_end` >= CURDATE() AND `licence_type` IN ('Subscription', 'Trial') AND `account_id` < 999009 AND `account_id` NOT LIKE %s AND `account_id` NOT IN ('DLSUSACC', 'MTA ')", $excluded_account_id_like 
    ));
}

 
/**
 * Returns active schools with the given account IDs.
 * 
 * @param array $account_ids
 * @return array
 */
function get_active_schools($account_ids) {
    global $wpdb;
    $terms_table = $wpdb->prefix . "terms"; 

    $placeholder = implode(',', array_fill(0, count($account_ids), '%d'));

    return $wpdb->get_results($wpdb->prepare(
        "SELECT `term_id`, `name` AS `SchoolName`, `slug` AS `AccountNumber` FROM ". $terms_table ." WHERE slug IN ( $placeholder )", $account_ids
    ));
}


/**
 * Returns the user IDs and term IDs for the given term IDs.
 * 
 * @param array $term_ids
 * @return array
 */
function get_user_term_relationships($term_ids) {
    global $wpdb;
    $term_relationships_table = $wpdb->prefix . "term_relationships";

    $placeholder = implode(',', array_fill(0, count($term_ids), '%d'));

    return $wpdb->get_results($wpdb->prepare(
        "SELECT `object_id` AS `user_id`,`term_taxonomy_id` AS `term_id` FROM ". $term_relationships_table ." WHERE term_taxonomy_id IN ( $placeholder )", $term_ids
    ));
}


/**
 * Returns the first name, last name, and role of users with the given user IDs.
 * 
 * @param array $user_ids
 * @return array
 */
function get_user_info($user_ids) {
    global $wpdb;
    $usermeta_table = $wpdb->prefix . "usermeta";

    $school_like = wushka_sql_esc_like('s:6:"school"');
    $teacher_like = wushka_sql_esc_like('s:7:"teacher"');

    return $wpdb->get_results($wpdb->prepare(
        "SELECT first.user_id, first.meta_value as FirstName, last.meta_value as LastName, 
        CASE 
            WHEN capabilities.meta_value LIKE %s AND capabilities.meta_value LIKE %s THEN 'Co-ordinator/Teacher' 
            WHEN capabilities.meta_value LIKE %s THEN 'Teacher' 
        ELSE 
            'Co-ordinator' 
        END AS `Role`
        FROM ".$usermeta_table." as first 
        JOIN ".$usermeta_table." as last ON (first.user_id = last.user_id ) 
        JOIN ".$usermeta_table." as capabilities ON (first.user_id = capabilities.user_id) 
        WHERE first.user_id IN (" . implode(',', $user_ids) . ") 
        AND first.meta_key = 'first_name'
        AND last.meta_key = 'last_name'
        AND capabilities.meta_key = 'wp_capabilities'
        AND (capabilities.meta_value LIKE %s OR capabilities.meta_value LIKE %s ) 
        Group by first.umeta_id", $school_like, $teacher_like, $teacher_like, $school_like, $teacher_like
    ));
}

/**
* This function gets the email of the users from the 'users' table based on the user_ids passed in as a string
*
* @param array $user_ids_str A comma-separated string of user ids
*
* @return array The user_id and user_email of the users
*/
function get_user_emails($user_ids_str){
    global $wpdb;
    $users_table = $wpdb->prefix . "users";
    return $wpdb->get_results(
        "SELECT ID AS `user_id`, `user_email` AS `Email` FROM ". $users_table ." WHERE ID IN (". implode(',', $user_ids_str) .")"
    );
}

// Define the comparison function to sort by AccountNumber
function compare_account_numbers($a, $b) {
    return strcmp($a->AccountNumber, $b->AccountNumber);
}
 
function wushka_export_teachers_pc(){
    //Acount id which has valid subscription
    $account_ids_mixed = array_column(get_valid_subscription_account_ids(), 'account_id');
    $account_ids = array_filter($account_ids_mixed, function($value) {
        return is_int($value) || ctype_digit($value);
    });
    //School details from account id
    $active_schools = get_active_schools($account_ids);
    //All users which is associated with valid subscription school
    $associated_users = get_user_term_relationships(array_column($active_schools, 'term_id'));
    //Get user information only when they are PC/teacher
    $user_info = get_user_info(array_column($associated_users, 'user_id'));
    //get user email
    $filtered_user_ids_str = array_column($user_info, 'user_id');
    $user_emails = get_user_emails($filtered_user_ids_str);


    $merged1 = custom_merge_arrays($active_schools, $associated_users, "term_id");
    $merged2 = custom_merge_arrays($merged1, $user_info, "user_id");
    $unfiltered_datas = custom_merge_arrays($merged2, $user_emails, "user_id");

    //Filter data
    $datas = array_filter($unfiltered_datas, function($data) {
        return !empty($data->Email) && strpos($data->Email, "wushka.co") === false && strpos($data->Email, "teaching.co") === false;
    });

    // Sort the filtered array by AccountNumber using usort
    usort($datas, 'compare_account_numbers');
?>
<div class="wrap">
    <h2>Active PC/Teacher Data</h2>

    <table id="exportTable">
        <thead>
            <tr>
                <th class="sn">S.N</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>School Name</th>
                <th>Account Number</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $count = 1;
                foreach($datas as $data){
            ?>
            <tr>
                <td><?=$count;?></td>
                <td><?= $data->FirstName; ?></td>
                <td><?= $data->LastName; ?></td>
                <td><?= $data->Email; ?></td>
                <td><?= $data->Role; ?></td>
                <td><?= $data->SchoolName; ?></td>
                <td><?= $data->AccountNumber; ?></td>                
            </tr>
            <?php 
                $count++;
                }
            ?>
        </tbody>
    </table>
</div>
<?php
}
