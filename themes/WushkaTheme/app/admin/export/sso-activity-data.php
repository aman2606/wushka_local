<?php
function wushka_export_sso_activity(){

    global $wpdb;  
    //Table name with prefix 
    $event_table = $wpdb->prefix . "wushka_school_events";
    $term_table = $wpdb->prefix . "terms";

?>
<div class="wrap">
    <h2>SSO Activity Data</h2>

    <table id="exportTable">
        <thead>
            <tr>
                <th class="sn">S.N</th>
                <th>School Name</th>
                <th>School Slug</th>
                <th>Role</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php 
                $sql = "SELECT `school_id`, `event_type`, `description`, `date_created` FROM ".$event_table." WHERE `sso_login` = %s";

                $results = $wpdb->get_results(
                    $wpdb->prepare($sql, 'nsw_doe')
                );

                //Change school term_id to term slug
                $school_ids = array_unique(array_column($results,'school_id'));
                $term_ids = implode(', ', $school_ids);

                $term_sql = "SELECT `term_id`, `name`, `slug` FROM ".$term_table." WHERE `term_id` IN (%1s)";
                $term_results = $wpdb->get_results(
                    $wpdb->prepare($term_sql, $term_ids)
                );

                $school_name = [];
                $school_slug = [];
                foreach($term_results as $term_result){
                    $school_name += [
                        $term_result->term_id => $term_result->name
                    ];
                    $school_slug += [
                        $term_result->term_id => $term_result->slug
                    ];
                }
 
                $count = 1;
                foreach($results as $result){
            ?>
            <tr>
                <td><?=$count;?></td>
                <td><?= $school_name[$result->school_id]; ?></td>
                <td><?= $school_slug[$result->school_id]; ?></td>
                <td><?=  $result->event_type; ?></td>
                <td><?=  $result->description; ?></td>
                <td><?=  $result->date_created; ?></td>
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