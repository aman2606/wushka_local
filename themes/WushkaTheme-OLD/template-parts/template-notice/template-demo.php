<?php 
/* Notice Template 2 for Demo School */

if (!defined('ABSPATH')) {
    exit(); // Exit if accessed directly
}

$color = get_field('color', $notice_id);
$styleColor = '';
if(isset($color) && !empty($color)){
   $styleColor = 'style="background:' . $color . '"';
}
$text_under_last_button = get_field('text_under_last_button', $notice_id);
if(!isset($text_under_last_button) || empty($text_under_last_button)){
    $text_under_last_button = '';
}

?>

<div class="notice-container notice-template-<?= $template; ?>" <?= $styleColor ?>>
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
        <a href="<?= esc_url(get_field('button_link', $notice_id)) ?>" target="_blank" class="notice-btn">
                <?= get_field('button_text', $notice_id); ?>
        </a>
        <?php 
            }
        ?>
        <div class="notice-complete mt20">
            <p><?= $text_under_last_button ?></p>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        $(document).on('click', '.notice-template-<?= $template; ?> .close-notice a', function(e) {
            e.preventDefault();
            ajax_set_demo_notice_cookie();
        });

        function ajax_set_demo_notice_cookie() {
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
