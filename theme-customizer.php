<?php
/**
 * Plugin Name: Kunoichi Theme Customizer
 * Plugin URI:  https://github.com/kuno1/theme-customizer
 * Description: Theme customizer helper
 * Version:     0.0.0
 * Author:      Kunoichi INC.
 * Author URI:  https://kunoichiwp.com
 * License:     GPLv3 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-3.0.html
 * Text Domain: theme-customizer
 * Domain Path: /languages
 */

// This file actually do nothing.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

require __DIR__ . '/vendor/autoload.php';

\Kunoichi\ThemeCustomizer::load_locale( get_locale() );

$auto_loaded = [ 'Patterns' ];
foreach ( $auto_loaded as $dir ) {
	$path = __DIR__ . '/src/Kunoichi/ThemeCustomizer/' . $dir;
	if ( ! is_dir( $path ) ) {
		continue;
	}
	foreach ( scandir( $path ) as $file ) {
		if ( ! preg_match( '/^([^._].*)\.php$/u', $file, $match ) ) {
			continue;
		}
		list( $file, $class_name ) = $match;
		$class_name = "Kunoichi\\ThemeCustomizer\\{$dir}\\{$class_name}";
		if ( ! class_exists( $class_name ) ) {
			continue;
		}
		$class_name::get_instance();
	}
}
