<?php

namespace Kunoichi\ThemeCustomizer\Models\Brand;


use Kunoichi\ThemeCustomizer\Models\AbstractBrand;

class Twitter extends AbstractBrand {

	protected function get_priority() {
		return 1009;
	}

	public function get_url( $post = null ) {
		return $this->add_query_params( [
			'url'  => get_permalink( $post ),
			'text' => get_the_title( $post ),
		], 'https://twitter.com/share', $post );
	}

}
