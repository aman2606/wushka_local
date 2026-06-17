<?php
//qr login data Display (school list only)
function wushka_export_qrlogin_data(){

    global $wpdb; 
        //Table name with prefix 
        $users_table = $wpdb->prefix . "users"; 
        $usermeta_table = $wpdb->prefix . "usermeta";
        
        //Esc like
        $meta = wushka_sql_esc_like('qr_logged_in');
?>


<div class="wrap">
    <h2>QR Login Data</h2>

    <table id="exportTable">
        <thead>
            <tr>
                <th class="sn">S.N</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Logged Count</th>
            </tr>
        </thead>
        <tbody>
            <?php 
         
                //Query for meta key = qr_logged_in
                $sql ="SELECT  m.user_id AS `user_id`,fn.meta_value AS `first_name`,ln.meta_value AS `last_name` ,count(m.user_id) AS `count` ,u.user_email AS `Email` FROM  ".$users_table."  u
                INNER JOIN ".$usermeta_table." m ON m.user_id = u.ID 
                LEFT JOIN ".$usermeta_table." fn ON fn.user_id = m.user_id AND fn.meta_key='first_name' 
                LEFT JOIN ".$usermeta_table." ln ON ln.user_id = m.user_id AND ln.meta_key='last_name'
                WHERE m.meta_key='qr_logged_in' GROUP BY m.user_id";
                
                $results = $wpdb->get_results(
                    $wpdb->prepare($sql,$meta)
                );
                $count = 1;
                foreach($results as $result){
            ?>
            <tr>
                <td><?=$count;?></td>
                <td><?=$result->first_name; ?></td>
                <td><?=$result->last_name; ?></td>
                <td><?=$result->Email; ?></td>
                <td><?= $result->count;?></td>
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