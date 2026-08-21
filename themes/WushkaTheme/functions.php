<?php


// Comment out the autoload php as we suspect the composer libraries specified in composer.json are not required anymore
//require 'vendor/autoload.php';

global $wushka_theme_db_version;
$wushka_theme_db_version = "1.0";

//Include Files
include_once 'functions/helpers.php';
include_once 'functions/wushka_enqueue.php';
include_once 'functions/wushka_ajax.php';
//include_once 'app/Controllers/AzureAuth.php';

define('LICENCE_WDT', "Wushka Decodable Teacher");
define('SIS_REST_API_ENABLED', "false");
define('OPEN_HOUSE_CUSTOMER', "ohc");
// Remove type text/javascript/css from script and styles
add_action('after_setup_theme', function () {
    add_theme_support('html5', ['script', 'style']);
});

function wushka_is_yootheme_builder_page() {
    // Primary: YOOtheme's own flag, set by LoadCustomizerSession during wp_loaded.
    // The customizer preview always loads via a form POST containing customizer_session
    // and customizer params — so this flag is reliably true on every preview request.
    if (function_exists('\YOOtheme\app')) {
        try {
            $cfg = \YOOtheme\app('config');
            // Customizer preview: set by LoadCustomizerSession during wp_loaded (POST-based loads).
            if ($cfg->get('app.isCustomizer')) {
                return true;
            }
            // Frontend builder page: set by RenderBuilderTemplate at template_include priority 50,
            // before header.php or wp_head ever runs — most reliable frontend signal.
            if ($cfg->get('app.isBuilder')) {
                return true;
            }
        } catch (\Throwable $e) {}
    }

    // Fallback A: cookie set by beforeunloadPreview for user-initiated link clicks.
    if (!empty($_COOKIE['yootheme_session'])) {
        return true;
    }

    // Fallback B: POST param present (belt-and-suspenders for the POST-based loads).
    if (!empty($_POST['customizer_session'])) {
        return true;
    }

    global $post;
    if (!$post) return false;
    $content = (string) ($post->post_content ?? '');
    // Use YOOtheme's own PostHelper when available — guaranteed to match
    // exactly what YOOtheme considers a builder page.
    if (class_exists('\YOOtheme\Builder\Wordpress\PostHelper')) {
        return \YOOtheme\Builder\Wordpress\PostHelper::matchContent($content) !== null;
    }
    // Fallback: replicate PostHelper::PATTERN exactly.
    return strpos($content, '<!--') !== false &&
           (bool) preg_match('/<!--\s?(\{.*})\s?-->/', $content);
}
