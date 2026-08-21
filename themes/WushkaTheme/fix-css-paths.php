<?php
/**
 * One-time script: fix hardcoded WushkaTheme asset paths in wushka-compiled.css.
 * Run from CMD: php fix-css-paths.php
 */

$file = __DIR__ . '/wushka-compiled.css';

if (!file_exists($file)) {
    echo "ERROR: wushka-compiled.css not found.\n";
    exit(1);
}

$css = file_get_contents($file);
$original_len = strlen($css);

// Replace absolute WushkaTheme font paths with relative paths.
// Relative to the CSS file location (yootheme-child/), ./fonts/ resolves correctly.
$css = str_replace('/wp-content/themes/WushkaTheme/fonts/', './fonts/', $css);

// Replace absolute WushkaTheme img paths with relative paths.
$css = str_replace('/wp-content/themes/WushkaTheme/img/', './img/', $css);

// Replace any remaining WushkaTheme images/ paths just in case.
$css = str_replace('/wp-content/themes/WushkaTheme/images/', './images/', $css);

file_put_contents($file, $css);

// Verify
$remaining = substr_count($css, '/wp-content/themes/WushkaTheme/');
if ($remaining === 0) {
    echo "OK: All WushkaTheme paths replaced. File updated successfully.\n";
} else {
    echo "WARNING: {$remaining} WushkaTheme reference(s) still remain. Check the file manually.\n";
}
