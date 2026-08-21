<?php

/*
Template Name: Parent Dashboard
*/
get_header();

/*User role checking.
 * Only parents, and admin are allowed to access this page.
 */

if ( !(is_user_logged_in() && is_super_admin() || is_admin() || user_can($current_user, "parent"))
    ) {
   wp_redirect( home_url());
   exit;
}
?>
<script>
    var add_new_user_data_path = '<?php echo get_template_directory_uri() . '/db_add-new-user.php'; ?>';
    var edit_child_data_path   = '<?php echo get_template_directory_uri() . '/edit-children-data.php'; ?>';
</script>
<div class="new-user-confirmation-msg" style="display:none;"> New user has been added! </div>

<div class="container parent-view-wrapper">
    <div class="row">
        <div class="col-xs-3">
          <div class="parent-view-left-col">
            <ul class="nav nav-pills nav-stacked">
              <li id="children-list" class="selected"><a href="/parent-dashboard"> Dashboard </a></li>
              <?php
                if(check_license_limit($current_user->ID) <= 0) { ?>
                    <li id="create-new-profile">
                        <a href="#" class="license-limit" data-placement="left" data-toggle="popover" title="Your Subscription" data-content="You have used all your licenses">Create Children profile</a>
                    </li>
                  <?php

              } else { ?>
                  <li id="create-new-profile"><a href="/create-child-profile"> Create Children profile </a></li>
              <?php } ?>
            </ul>
            </div>
        </div>
        <div class="col-xs-9 parent-view-right-col">
            <h1 class="parent-view-heading">Parent Dashboard</h1>
            <div class="parent-view-container">
                <div class="show-children-list">
                    <?php  $parent_id = $current_user->ID;
                        $args = array(
                        'role' => 'student',
                            'meta_query' => array(
                                'relation' => 'AND',
                                0 => array(
                                    'key' => 'parent_id',
                                    'value' => $parent_id
                                ),
                                1 => array(
                                    'key' => 'active',
                                    'value' => 1
                                )
                            )
                        );
                        $total = 0;
                        $user_query = new WP_User_Query($args);
                        if ( ! empty( $user_query->results ) ) {
                            foreach ( $user_query->results as $idx => $user ) { ?>
                               <div id="child-<?php echo $user->id;?>"><span><?php echo $user->first_name . ' ' . $user->last_name .' (' . $user->user_login .')'; ?></span>
                                <form method="POST" action="/child-profile">
                                    <input type="hidden" name="childID" value="<?php echo $user->id; ?>">
                                <input class="btn btn-default btn-sm btn-parent-view-profile" type="submit" value="View Profile" /></form></div>
                          <?php  }
                        } else {
                            echo 'There is no user profile in your account. Add new user by clicking on <b>Create Children Profile</b> link.';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>