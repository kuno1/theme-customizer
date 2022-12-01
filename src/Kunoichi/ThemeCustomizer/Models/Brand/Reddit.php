<?php

namespace Kunoichi\ThemeCustomizer\Models\Brand;


use Kunoichi\ThemeCustomizer\Models\AbstractBrand;

class Reddit extends AbstractBrand {

	protected function get_priority() {
		return 11;
	}

	public function get_url( $post = null ) {
		return $this->add_query_params( [
			'url'   => get_permalink( $post ),
			'title' => get_the_title( $post ),
		], 'https://www.reddit.com/submit', $post );
	}
}
