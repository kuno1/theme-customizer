<?php

namespace Kunoichi\ThemeCustomizer;

/**
 * Trait Utilities
 * @package Kunoichi\ThemeCustomizer
 */
trait Utilities {

	/**
	 * Convert class name to hungarian.
	 *
	 * @param string $class_name
	 * @return string
	 */
	protected function camelize( $class_name ) {
		return implode( '_', array_map( function( $token ) {
			return strtolower( preg_replace_callback( '/(?<!^|[A-Z])([A-Z])(?![A-Z])/u', function( $matches ) {
				return  '_' . $matches[1];
			}, $token ) );
		}, preg_split( '/(_|\\\\)/u', $class_name ) ) );
	}

}
