<?php
/**
 * Run once from CMD to replace get_template_directory_uri() and
 * get_template_directory() with their stylesheet equivalents in all
 * PHP files inside the yootheme-child theme folder.
 *
 * Usage (from themes\yootheme-child\):
 *   php fix-paths.php
 */

$theme_dir = __DIR__;
$replacements = [
    'get_template_directory_uri()' => 'get_stylesheet_directory_uri()',
    'get_template_directory()'     => 'get_stylesheet_directory()',
];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($theme_dir, RecursiveDirectoryIterator::SKIP_DOTS)
);

$changed = 0;
$skipped = 0;

foreach ($iterator as $file) {
    if ($file->getExtension() !== 'php') {
        continue;
    }

    // Skip this script itself and the vendor directory
    $path = $file->getRealPath();
    if (strpos($path, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR) !== false) {
        $skipped++;
        continue;
    }
    if ($path === realpath(__FILE__)) {
        continue;
    }

    $original = file_get_contents($path);
    $updated  = str_replace(
        array_keys($replacements),
        array_values($replacements),
        $original,
        $count
    );

    if ($count > 0) {
        file_put_contents($path, $updated);
        echo "[FIXED $count] $path\n";
        $changed++;
    }
}

echo "\nDone. Fixed $changed file(s). Skipped $skipped vendor file(s).\n";
echo "You can delete this script now: " . basename(__FILE__) . "\n";
