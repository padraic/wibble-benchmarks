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
define('BENCHMARK_ITERATIONS', 100);

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

$config = HTMLPurifier_Config::createDefault();
$config->set('Cache.SerializerPath', HTMLPURIFIER_CACHE);
$purifier = new HTMLPurifier($config);
for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
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
    htmlawed($smallInput, array('safe'=>1,'deny_attribute'=>'style'));
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;
 
/**
 * Wibble Pass #1 - Small File
 */
 
echo 'Wibble Pass #1 - Small File:', PHP_EOL;
$timer->start();

$fragment = new \Wibble\HTML\Fragment($smallInput);
for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
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

$config = HTMLPurifier_Config::createDefault();
$config->set('Cache.SerializerPath', HTMLPURIFIER_CACHE);
$purifier = new HTMLPurifier($config);
for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
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
    htmlawed($mediumInput, array('safe'=>1,'deny_attribute'=>'style'));
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;
 
/**
 * Wibble Pass #2 - Medium File
 */
 
echo 'Wibble Pass #2 - Medium File:', PHP_EOL;
$timer->start();

$fragment = new \Wibble\HTML\Fragment($mediumInput);
for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $fragment->filter();
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;

echo 'PASS #2 - MEDIUM FILE', PHP_EOL, '=============================================', PHP_EOL, PHP_EOL;

/**
 * HTMLPurifier Pass #3 - Big File
 */

echo 'HTMLPurifier Pass #3 - Big File:', PHP_EOL;
$timer->start();

$config = HTMLPurifier_Config::createDefault();
$config->set('Cache.SerializerPath', HTMLPURIFIER_CACHE);
$purifier = new HTMLPurifier($config);
for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
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
    htmlawed($bigInput, array('safe'=>1,'deny_attribute'=>'style'));
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;
 
/**
 * Wibble Pass #3 - Big File
 */
 
echo 'Wibble Pass #3 - Big File:', PHP_EOL;
$timer->start();

$fragment = new \Wibble\HTML\Fragment($bigInput);
for ($i=0;$i<=BENCHMARK_ITERATIONS;$i++) {
    $fragment->filter();
}

$timer->stop();
$timer->display();
echo PHP_EOL, PHP_EOL;

