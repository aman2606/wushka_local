<?php

/*
  Template Name: View Child profile
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

<?php
/* Get POST child ID*/

$student_id = null;

$_SESSION['childID'] = isset($_POST['childID'])? $_POST['childID'] : $_SESSION['childID'] ;
$child_id = isset($_POST['childID'])? $_POST['childID'] : $_SESSION['childID'] ;

$user =  get_user_by('id',$child_id);
$user_meta = get_user_meta($child_id);

$no_duplicate_books = array();
//$no_duplicate_user = reader_analytics($child_id);
//$no_duplicate_books = $user($child_id);
$reading_analytics = child_reading_history_analytics($child_id);
?>
<section class="view-user-profile-wrapper">
<div class="container-fluid padding-y">
<div class="row">
<!-- div class="left-navigation" -->
<div class="col-xs-12 col-md-3">
    <div class="parent-view-left-col">
<ul class="nav nav-pills nav-stacked">
    <li id="children-list"><a href="/parent-dashboard"> Dashboard</a></li>
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

<!-- div class="right-navigation" -->
<div class="col-xs-12 col-md-9 parent-view-right-col">
<h1 class="parent-view-heading"> <?php echo ucwords($user_meta['first_name'][0]);?>'s Profile</h1>
<div class="child-details">
  <div class="child-name-age">
    <strong>Name:</strong> <?php echo ucwords($user_meta['first_name'][0]) . ' ' . ucwords($user_meta['last_name'][0]) . '<br/>'; ?>
    <strong>Age:</strong> <?php echo $user_meta['min_range'][0] . ' - ' . $user_meta['max_range'][0]; ?>
  </div>
  <div class="edit-profile-box">
    <form method="POST" action="/edit-child-profile">
    <input type="hidden" name="edit_childID" value="<?php echo $child_id; ?>">
    <input class="btn btn-default edit-profile" type="submit" value="Edit Profile" />
    </form>
  </div>
</div>
<?php
if($child_id ) {
    if($user){
        ?>
<div class="reading-history-table-wrapper">
    <div class="table-responsive">
        <table id="child-history-view-<?php echo $child_id ?>" class="display table table-striped table-bordered table-condensed">
            <?php if($reading_analytics) {  ?>
            <thead>
               <tr class="student-view-table-heading">
                <th style="width: 10%;">Book : </th>
                <th style="width: 7%;">No. of Times Read : </th>
                <th style="width: 10%;">First Read Date : </th>
                <th style="width: 10%;">Last Read Date : </th>
                <th style="width: 10%;">Average Reading Time : </th>
              </tr>
            </thead>
            <tfoot>
              <tr class="student-view-table-heading">
                <th style="width: 10%;">Book : </th>
                <th style="width: 7%;">No. of Times Read : </th>
                <th style="width: 10%;">First Read Date : </th>
                <th style="width: 10%;">Last Read Date : </th>
                <th style="width: 10%;">Average Reading Time : </th>
              </tr>
            </tfoot>
            <tbody>
              <?php

                foreach ($reading_analytics as $book_id => $book_info){
                    $book_info['avg_reading_time'] = $book_info['duration']/$book_info['times_read'];
                     $args = array(
                      'post_type'     => 'ebook',
                      'post_status'   => 'publish',
                      'meta_query' => array(
                          array(
                              'key' => 'esiss_resource_id',
                              'value' => $book_id
                          )
                      )
                  );
                     $book_obj =  get_posts($args,ARRAY_A);
                     foreach($book_obj as $idx => $book){
                ?>
            <tr class="student-view-table-data row-odd">
                <td class="book_name"><?php echo $book->post_title; ?></td>
                <td class="times_read"><?php echo  $book_info['times_read']; ?></td>
                <td class="fread_date"><?php echo $book_info['created']; ?></td>
                <td class="lread_date"><?php echo $book_info['last_created']; ?></td>
                <td class="avg_reading_time"><?php echo number_format( ($book_info['avg_reading_time']/60),0) .'m ' . number_format( ($book_info['avg_reading_time']%60),0) .'s'  ; ?></td>
            </tr>
         <?php       }
                }// End of foreach
           ?>
            </tbody>
           <?php  } else { ?>
                <p style="text-align: center;margin-top: 120px;">There is no reading history for <?php echo ucwords($user_meta['first_name'][0]) . ' ' . ucwords($user_meta['last_name'][0]) . '<br/>'; ?></p>
         <?php   }
         ?>
        </table>
    </div>
</div>
    <?php } else {
        echo '<div class="reading-history-table-wrapper">There is no history available for this user</div>';
    }
} ?>
</div>
</div></div></section>
<?php
include 'dashboard_options.php';
get_footer();
?>