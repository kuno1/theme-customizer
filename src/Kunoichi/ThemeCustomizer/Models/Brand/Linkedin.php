<?php

namespace Kunoichi\ThemeCustomizer\Models\Brand;


use Kunoichi\ThemeCustomizer\Models\AbstractBrand;

class Linkedin extends AbstractBrand {
	
	public function verbose_name() {
		return 'LinkedIn';
	}
	
	protected function get_priority() {
		return 1002;
	}
	
	public function get_url( $post = null ) {
		return $this->add_query_params( [
			'url'   => get_permalink( $post ),
		], 'https://www.linkedin.com/sharing/share-offsite/', $post );
	}
}
