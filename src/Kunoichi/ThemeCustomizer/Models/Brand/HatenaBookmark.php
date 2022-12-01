<?php

namespace Kunoichi\ThemeCustomizer\Models\Brand;

use Kunoichi\ThemeCustomizer\Models\AbstractBrand;

/**
 * Hatena Bookmark
 *
 * @package theme-customizer
 */
class HatenaBookmark extends AbstractBrand {

	public function get_priority() {
		return 10;
	}

	public function get_url( $post = null ) {
		return sprintf( 'https://b.hatena.ne.jp/entry/panel/?mode=confirm&title=%1$s&url=%2$s', rawurlencode( get_the_title( $post ) ), rawurlencode( get_permalink( $post ) ) );
	}

	public function verbose_name() {
		return __( 'Hatena Bookmark', 'theme-customizer' );
	}
}
