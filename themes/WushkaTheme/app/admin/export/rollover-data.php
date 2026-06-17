<?php
function wushka_export_rollover()
{

    global $wpdb;
    //Table name with prefix 
    $users_table = $wpdb->prefix . "users";
    $usermeta_table = $wpdb->prefix . "usermeta";
    $capabilities_table = $wpdb->prefix . "capabilities";

    //Esc like
    $school_like = wushka_sql_esc_like('school');
    $teacher_like = wushka_sql_esc_like('teacher');

    $dateCondition = "";
    $year = "";

    if (isset($_GET['year']) && !empty($_GET['year']) && $_GET['year'] != 'all') {

        $year = intval($_GET['year']);

        $dateCondition .= " INNER JOIN wp_usermeta as c2 ON(u.ID = c2.user_id AND c2.meta_key = 'wushka_rollover_time' AND YEAR(c2.meta_value) = {$year}) ";
    }

?>
    <div class="wrap">
        <h2>Rollover Data</h2>

        <div class="year-filter" style="display: inline-block;padding-left:5px;">
            <form action="" id='year-filter-form' method="GET">
                <input type="hidden" name='page' value="export_rollover" />
                <label>Select Year: </label>
                <select id="yearFilter" class="form-control" style="height:39px;border-radius: 4px;border: 1px solid #ccc;" name="year">
                    <option value="all">Show All</option>
                    <?php
                    $currentYear = date("Y");
                    for ($i = 0; $i < 3; $i++) {
                        $y = $currentYear - $i;
                    ?>
                        <option value="<?php echo  $y ?>" <?php echo ($year == $y ? 'selected' : '') ?>><?php echo $y  ?></option>
                    <?php
                    }
                    ?>

                </select>
            </form>
        </div>
        <table id="exportTable">
            <thead>
                <tr>
                    <th class="sn">S.N</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>School Name</th>
                    <th>Account</th>
                </tr>
            </thead>
            <tbody>
                <?php



                $sql = "SELECT u.ID,u.user_login, 
                    CASE 
                        WHEN c.meta_value LIKE %s AND c.meta_value LIKE %s THEN 'Co-ordinator/Teacher' 
                        WHEN c.meta_value LIKE %s THEN 'Teacher' 
                    ELSE 
                        'Co-ordinator' 
                    END AS `Role` 
                    From " . $users_table . " u
                    LEFT JOIN " . $usermeta_table . " c ON c.user_id = u.ID AND c.meta_key = '" . $capabilities_table . "'  {$dateCondition}
                    WHERE ID IN (SELECT user_id FROM " . $usermeta_table . " WHERE meta_key = %s)
                    ";

                $results = $wpdb->get_results(
                    $wpdb->prepare($sql, $school_like, $teacher_like, $teacher_like, 'wp_wushka_rollover')
                );

                $count = 1;
                foreach ($results as $result) {

                    $schoolName = "";
                    $acountNumber = "";

                    $schoolInfo = wp_get_object_terms($result->ID, 'school');
                    if (!empty($schoolInfo)) {

                        $schoolName = $schoolInfo[0]->name;
                        $acountNumber = $schoolInfo[0]->slug;
                    }
                ?>
                    <tr>
                        <td><?= $count; ?></td>
                        <td><?= $result->user_login; ?></td>
                        <td><?= $result->Role; ?></td>
                        <td><?= $schoolName; ?></td>
                        <td><?= $acountNumber; ?></td>
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
