<?php 
/* Notice Template 1 for Rollover */

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

?>

<div class="notice-container">
    <div class="close-notice">
        <a href="#" title="Close">X Close</a>
    </div>
    <div class="notice-content">
        <h1><?= get_the_title($notice_id); ?></h1>
        <p>
           <?= wp_strip_all_tags(get_field('description', $notice_id)); ?> 
        </p>

        <?php 
            if(!empty(get_field('button_link', $notice_id))){
        ?>
        <a href="<?= esc_url(get_field('button_link', $notice_id)) ?>" class="notice-btn">
                <?= get_field('button_text', $notice_id); ?>
        </a>
        <?php 
            }
        ?>
        <div class="notice-complete mt20">
            <p>If your rollover is complete <a href="" title="Click Here">click here</a>.</p>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        $(document).on('click', '.close-notice a', function(e) {
            e.preventDefault();
            ajax_set_notice_cookie();
        });

        $(document).on('click', '.notice-complete a', function(e) {
            e.preventDefault();
            ajax_rollover_complete();
        });

        function ajax_set_notice_cookie() {
            $.ajax({
                url: '<?php echo esc_url(admin_url("admin-ajax.php"), "admin-ajax.php"); ?>',
                type: "POST",
                data: {
                    'action': 'set_notice_cookie',
                    'nonce': '<?php  echo wp_create_nonce( 'notice_cookie' )?>'
                },
                success: function(response) {
                    if(response.trim() == "success") {
                        $('.notice-container').fadeOut(1000);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                }
            });
        }

        function ajax_rollover_complete(){
            $.ajax({
                url: '<?php echo esc_url(admin_url("admin-ajax.php"), "admin-ajax.php"); ?>',
                type: "POST",
                data: {
                    'action': 'set_rollover_complete',
                    'nonce': '<?php  echo wp_create_nonce( 'rollover_complete' )?>'
                },
                success: function(response) {
                    if(response == "success") {
                        $('.notice-container').fadeOut(1000);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(xhr.status);
                    console.log(xhr.responseText);
                    console.log(thrownError);
                }
            });
        }

    });
</script>
