<?php

namespace Kunoichi\ThemeCustomizer\Models\Brand;


use Kunoichi\ThemeCustomizer\Models\AbstractBrand;

class Pinterest extends AbstractBrand {
	
	protected function get_priority() {
		return 1001;
	}
	
	public function get_url( $post = null ) {
		return $this->add_query_params( [
			'url'         => get_permalink( $post ),
			'description' => get_the_title( $post ),
		], 'http://pinterest.com/pin/create/button/', $post );
	}
}
