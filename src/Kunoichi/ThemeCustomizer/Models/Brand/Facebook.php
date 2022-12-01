<?php
namespace Kunoichi\ThemeCustomizer\Models\Brand;


use Kunoichi\ThemeCustomizer\Models\AbstractBrand;

/**
 *
 * @package theme-customizer
 */
class Facebook extends AbstractBrand {
	protected function get_priority() {
		return 1010;
	}


	public function get_url( $post = null ) {
		return sprintf( 'https://www.facebook.com/share.php?u=%s', rawurlencode( get_permalink( $post ) ) );
	}
}
