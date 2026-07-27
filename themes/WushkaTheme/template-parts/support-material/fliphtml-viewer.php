<?php

$post_id          = get_the_ID();
$asset_data       = get_field( 'support_material_assets', $post_id );

$request_type     = $_GET['type'] ?? 'week'; // expects 'week' or 'day'
$assessment_id    = isset( $_GET['id'] ) ? (int) $_GET['id'] : null;
$final_iframe_url = '';

// Small helper to keep the flipable/iframe logic DRY
function get_final_iframe_url( $is_flipable, $iframe_url, $primary_link ) {
    return $is_flipable ? $iframe_url : $primary_link;
}

if ( $request_type === 'day' && $assessment_id !== null ) {
    $day_data      = $asset_data['week_days'][ $assessment_id ] ?? [];

    if( isset($_GET['sm_type']) && $_GET['sm_type'] === 'slideshow' ):

        echo "day secondary";
        $secondary_link          = $day_data['day_secodary_button_link'] ?? '#';
        $is_flipable_secondary   = $day_data['day_is_secondary_flipable_file'] ?? false;
        $secobdary_iframe_url    = $day_data['day_secondary_iframe_url'] ?? '';
        $final_iframe_url        = get_final_iframe_url( $is_flipable_secondary, $secobdary_iframe_url, $secondary_link );
    else:
        echo "day primary";
        $primary_link  = $day_data['day_primary_button_link'] ?? '#';
        $is_flipable   = $day_data['day_is_flipable_file'] ?? false;
        $iframe_url    = $day_data['day_iframe_url'] ?? '';
        $final_iframe_url = get_final_iframe_url( $is_flipable, $iframe_url, $primary_link );
    endif;

} else {

    $primary_link  = $asset_data['primary_button_link'] ?? '#';
    $is_flipable   = $asset_data['is_flipable_file'] ?? false;
    $iframe_url    = $asset_data['iframe_url'] ?? '';

    $final_iframe_url = get_final_iframe_url( $is_flipable, $iframe_url, $primary_link );
}

$is_file = false;

$path = parse_url($final_iframe_url, PHP_URL_PATH);
$extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

// List file extensions you want treated as "files"
$file_extensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt'];

if (in_array($extension, $file_extensions)) {
    $is_file = true;
}

$html_lang_attr = get_language_attributes();
// Normalize the AU locale to plain "en" for this standalone viewer page.
if ( 'lang="en-AU"' === $html_lang_attr ) {
    $html_lang_attr = 'lang="en"';
}
?>
<!DOCTYPE html>
<html <?php echo $html_lang_attr; ?> ontouchmove id="simpleViewer">

<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="p:domain_verify" content="0b1f38a6c5f52782dddde74afcc90cd1" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="shortcut icon" href="<?php echo esc_url( get_template_directory_uri() ); ?>/favicon.ico">
<link href="<?php echo esc_url( get_template_directory_uri() ); ?>/style.css" rel="stylesheet">

<?php wp_head(); ?>
<style>
    html, body {
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden; /* Prevent scrollbars */
    }

    .iframe-container {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .iframe-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    .close-btn {
        position: absolute;
        top: 15px;
        right: 20px;
        background-color: rgba(0, 0, 0, 0.6);
        color: white;
        border: none;
        padding: 8px 12px;
        font-size: 18px;
        border-radius: 4px;
        cursor: pointer;
        z-index: 9999;
        transition: background-color 0.2s ease;
    }

    .close-btn:hover {
        background-color: rgba(0, 0, 0, 0.8);
    }
    .toolbar-blocker-pdf.right {
        position: absolute;
        top: 1px;
        width: 190px;
        height: 55px;
        background: #3c3c3c;
        z-index: 5;
        pointer-events: all;
        right: 0;
    }
</style>
</head>

<body>
    <div class="iframe-container">
        <iframe
            src="<?php echo $final_iframe_url; ?>"
            allow="autoplay; fullscreen"
            seamless
            scrolling="no"
            frameborder="0"
            allowtransparency="true"
            webkitallowfullscreen="true"
            mozallowfullscreen="true"
            allowfullscreen="true">
        </iframe>
        <?php if ($is_file): ?>
            <div class="toolbar-blocker-pdf right"></div>
        <?php endif; ?>
    </div>

    <script type="text/javascript">
        // 1. Disable Specific Keyboard Shortcuts
        document.addEventListener('keydown', function (e) {
            var key = e.key ? e.key.toLowerCase() : '';

            // Disable Ctrl + S, P, A, U (Save, Print, Select All, View Source)
            if ((e.ctrlKey || e.metaKey) && ['s', 'p', 'a', 'u'].indexOf(key) !== -1) {
              e.preventDefault();
            }

            // Disable F12 and DevTools shortcuts (Ctrl + Shift + I / J / C)
            if (
              key === 'f12' || 
              ((e.ctrlKey || e.metaKey) && e.shiftKey && ['i', 'j', 'c'].indexOf(key) !== -1)
            ) {
              e.preventDefault();
            }
        });

        // 2. Disable Mouse Right-Click (Context Menu)
        document.addEventListener('contextmenu', function (e) {
            e.preventDefault();
        });

        // 3. Disable Dragging of Images/Elements
        document.addEventListener('dragstart', function (e) { 
            e.preventDefault(); 
        });
    </script>
</body>

</html>