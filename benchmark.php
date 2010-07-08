<?php

/**
 * Setup common include path
 */
set_include_path(
    __DIR__ . '/src' . PATH_SEPARATOR . get_include_path()
);

/**
 * Include setup files and initialise autoloaders
 */
require_once 'Benchmark/Timer.php';
require_once 'HTMLPurifier.auto.php';
require_once 'htmLawed.php';
require_once 'Wibble/Loader.php';
$loader = new \Wibble\Loader();
$loader->register();
define('HTMLPURIFIER_CACHE', __DIR__ . '/cache');
define('BENCHMARK_ITERATIONS', 200);

/**
 * Benchmark WIP - Install "Benchmark" package from PEAR
 */
$timer = new Benchmark_Timer;

/**
 * Directory deletion function for HTMLPurifier's cache
 */
function recursiveDelete($str) {
    if (is_file($str)) {
        return @unlink($str);
    } elseif (is_dir($str)) {
        $scan = glob(rtrim($str,'/') . '/*');
        foreach ($scan as $index => $path) {
            recursiveDelete($path);
        }
        return @rmdir($str);
    }
}
recursiveDelete(HTMLPURIFIER_CACHE . '/HTML');
recursiveDelete(HTMLPURIFIER_CACHE . '/CSS');
recursiveDelete(HTMLPURIFIER_CACHE . '/URI');

/**
 * Inputs:
 * Small - Blog comment size
 * Medium - Little content but lots of markup
 * Big - Lots of content and average markup
 */
$smallInput = file_get_contents(__DIR__ . '/input/small.html');
$mediumInput = file_get_contents(__DIR__ . '/input/medium.html');
$bigInput = file_get_contents(__DIR__ . '/input/big.html');

echo 'PASS #1 - SMALL FILE', PHP_EOL, '=============================================', PHP_EOL, PHP_EOL;

/**
 * HTMLPurifier Pass #1 - Small File
 */

echo 'HTMLPurifier Pass #1 - Small File:', PHP_EOL;
$timer->start();

for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Cache.SerializerPath', HTMLPURIFIER_CACHE);
    $purifier = new HTMLPurifier($config);
    $purifier->purify($smallInput);
}

$timer->stop();

/**
 * Delete HTMLPurifier Cache
 */
recursiveDelete(HTMLPURIFIER_CACHE . '/HTML');
recursiveDelete(HTMLPURIFIER_CACHE . '/CSS');
recursiveDelete(HTMLPURIFIER_CACHE . '/URI');

$timer->display();
echo PHP_EOL, PHP_EOL;

/**
 * HtmLawed Pass #1 - Small File
 */
 
echo 'htmLawed Pass #1 - Small File:', PHP_EOL;
$timer->start();

for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $config = array(
        'safe' => 1, 'deny_attribute' => '* -name', 'make_tag_strict' => 1,
        'no_deprecated_attr' => 1, 'elements' => 'em'
    );
    htmlawed($smallInput, $config);
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;
 
/**
 * Wibble Pass #1 - Small File
 */
 
echo 'Wibble Pass #1 - Small File:', PHP_EOL;
$timer->start();

for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $fragment = new \Wibble\HTML\Fragment($smallInput);
    $fragment->filter();
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;

echo 'PASS #2 - MEDIUM FILE', PHP_EOL, '=============================================', PHP_EOL, PHP_EOL;

/**
 * HTMLPurifier Pass #2 - Medium File
 */

echo 'HTMLPurifier Pass #2 - Medium File:', PHP_EOL;
$timer->start();

for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Cache.SerializerPath', HTMLPURIFIER_CACHE);
    $purifier = new HTMLPurifier($config);
    $purifier->purify($mediumInput);
}

$timer->stop();

/**
 * Delete HTMLPurifier Cache
 */
recursiveDelete(HTMLPURIFIER_CACHE . '/HTML');
recursiveDelete(HTMLPURIFIER_CACHE . '/CSS');
recursiveDelete(HTMLPURIFIER_CACHE . '/URI');

$timer->display();
echo PHP_EOL, PHP_EOL;

/**
 * HtmLawed Pass #2 - Medium File
 */
 
echo 'htmLawed Pass #2 - Medium File:', PHP_EOL;
$timer->start();

for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $config = array(
        'safe' => 1, 'deny_attribute' => '* -name', 'make_tag_strict' => 1,
        'no_deprecated_attr' => 1, 'elements' => 'em'
    );
    htmlawed($mediumInput, $config);
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;
 
/**
 * Wibble Pass #2 - Medium File
 */
 
echo 'Wibble Pass #2 - Medium File:', PHP_EOL;
$timer->start();

for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $fragment = new \Wibble\HTML\Fragment($mediumInput);
    $fragment->filter();
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;

echo 'PASS #3 - BIG FILE', PHP_EOL, '=============================================', PHP_EOL, PHP_EOL;

/**
 * HTMLPurifier Pass #3 - Big File
 */

echo 'HTMLPurifier Pass #3 - Big File:', PHP_EOL;
$timer->start();

for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $config = HTMLPurifier_Config::createDefault();
    $config->set('Cache.SerializerPath', HTMLPURIFIER_CACHE);
    $purifier = new HTMLPurifier($config);
    $purifier->purify($bigInput);
}

$timer->stop();

/**
 * Delete HTMLPurifier Cache
 */
recursiveDelete(HTMLPURIFIER_CACHE . '/HTML');
recursiveDelete(HTMLPURIFIER_CACHE . '/CSS');
recursiveDelete(HTMLPURIFIER_CACHE . '/URI');

$timer->display();
echo PHP_EOL, PHP_EOL;

/**
 * HtmLawed Pass #3 - Big File
 */
 
echo 'htmLawed Pass #3 - Big File:', PHP_EOL;
$timer->start();

for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $config = array(
        'safe' => 1, 'deny_attribute' => '* -name', 'make_tag_strict' => 1,
        'no_deprecated_attr' => 1, 'elements' => 'em'
    );
    htmlawed($bigInput, $config);
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;
 
/**
 * Wibble Pass #3 - Big File
 */
 
echo 'Wibble Pass #3 - Big File:', PHP_EOL;
$timer->start();

for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $fragment = new \Wibble\HTML\Fragment($bigInput);
    $fragment->filter();
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;

