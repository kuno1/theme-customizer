<?php

namespace Kunoichi;

use Kunoichi\ThemeCustomizer\CustomizerSetting;
use Symfony\Component\Finder\Finder;

/**
 * Theme customizer controller.
 *
 * @package theme-customizer
 */
class ThemeCustomizer {

	/**
	 * Do nothing.
	 */
	final private function __construct() {}

	/**
	 * Register customizers based on PSR-0 structure.
	 *
	 * @param string $name_space Themes name space like 'MyBrand\MyTheme\Customizers'
	 * @param string $base_dir   Base directory of name space. If not set, theme folder's root 'src' folder.
	 * @return bool|\WP_Error
	 */
	public static function register( $name_space, $base_dir = '' ) {
		static $initialized = false;
		self::load_locale( get_locale() );
		if ( ! $base_dir ) {
			$base_dir = get_template_directory() . '/src';
		}
		$base_dir = trailingslashit( $base_dir );
		$dir_name = $base_dir . str_replace( '\\', '/', $name_space );
		if ( ! is_dir( $dir_name ) ) {
			return new \WP_Error( 'theme_customizer_error', __( 'No items found.' ) );
		}
		$base_class_name = CustomizerSetting::class;
		$finder          = new Finder();
		$found           = 0;
		$class_names     = [];
		foreach ( $finder->in( $dir_name )->name( '*.php' )->files() as $file ) {
			$path       = $file->getPathname();
			$path       = str_replace( $base_dir, '', $path );
			$path       = str_replace( '.php', '', $path );
			$class_name = str_replace( '/', '\\', $path );
			if ( ! class_exists( $class_name ) ) {
				continue;
			}
			$class_names[] = $class_name;
		}
		/**
		 * Add filter for registered class names.
		 *
		 * @param string[] $class_names
		 * @param string   $name_space
		 * @param string   $base_class_name
		 * @param bool     $initialized
		 */
		$class_names = apply_filters( 'theme_customizer_class_name', $class_names, $name_space, $base_class_name, $initialized );
		foreach ( $class_names as $class_name ) {
			try {
				$reflection = new \ReflectionClass( $class_name );
				if ( ! $reflection->isSubclassOf( $base_class_name ) ) {
					continue;
				}
				$class_name::get_instance();
				$found ++;
			} catch ( \Exception $e ) {
				// Do nothing.
			}
		}
		$initialized = true;
		return $found ? true : new \WP_Error( 'no_class_found', __( 'No items found.' ) );
	}

	/**
	 * Load locales.
	 *
	 * @param string $locale
	 */
	public static function load_locale( $locale ) {
		$mo = dirname( dirname( __DIR__ ) ) . '/languages/theme-customizer-' . $locale . '.mo';
		if ( file_exists( $mo ) ) {
			load_textdomain( 'theme-customizer', $mo );
		}
	}
}
