<?php

namespace Kunoichi\ThemeCustomizer;

/**
 * Class Singleton
 *
 * @package kunoichi-theme-customizer
 */
abstract class Singleton {
	
	/**
	 * @var static[]
	 */
	private static $instances = [];
	
	/**
	 * Constructor
	 */
	final protected function __construct() {
		$this->init();
	}
	
	/**
	 * Do something inside constructor.
	 */
	protected function init() {}
	
	/**
	 * Register this.
	 *
	 * @return static
	 */
	public static function register() {
		$class_mame = get_called_class();
		if ( ! isset( self::$instances[ $class_mame ] ) ) {
			self::$instances[ $class_mame ] = new $class_mame();
		}
		return self::$instances[ $class_mame ];
	}
}
