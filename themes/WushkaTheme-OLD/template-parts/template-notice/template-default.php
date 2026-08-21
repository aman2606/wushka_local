<?php 
/* Notice Template 2 for Demo School */

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

$subHeading = get_field('sub_heading', $notice_id);
$color = get_field('color', $notice_id);
$text_under_last_button = get_field('text_under_last_button', $notice_id);
?> 



<div class="notice-container notice-template-<?= $template; ?>" style="background: <?php echo $color; ?>">
    <div class="close-notice">
        <a href="#" title="Close">X Close</a>
    </div>
    <div class="notice-content">
        <h1 style="font-size:23px;"><?= get_the_title($notice_id); ?></h1>
        <?php if(!empty($subHeading)): ?>
        <h3 style="padding-bottom:13px;font-size:18px;"><?php echo get_field('sub_heading', $notice_id) ?></h3>
        <?php endif; ?>
        <p>
           <?= get_field('description', $notice_id); ?> 
        </p>

        <?php 
            if(!empty(get_field('button_link', $notice_id))){
        ?>
        <a href="<?= esc_url(get_field('button_link', $notice_id)) ?>" target="_blank" class="notice-btn">
                <?= get_field('button_text', $notice_id); ?>
        </a>
        <?php 
            }
        ?>
        <div class="notice-complete mt20">
            <?php echo $text_under_last_button; ?>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        $(document).on('click', '.notice-template-<?= $template; ?> .close-notice a', function(e) {
            e.preventDefault();
            ajax_set_default_notice_cookie();
        });

        function ajax_set_default_notice_cookie() {
            $.ajax({
                url: '<?php echo esc_url(admin_url("admin-ajax.php"), "admin-ajax.php"); ?>',
                type: "POST",
                data: {
                    'action': 'set_notice_cookie',
                    'nonce': '<?php  echo wp_create_nonce( 'notice_cookie' )?>',
                    'TID': '<?php  echo encrypt_decrypt('encrypt', $template ); ?>',
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

        
    });
</script>
