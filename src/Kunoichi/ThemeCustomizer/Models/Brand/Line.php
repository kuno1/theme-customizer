<?php

namespace Kunoichi\ThemeCustomizer\Models\Brand;


use Kunoichi\ThemeCustomizer\Models\AbstractBrand;

class Line extends AbstractBrand {

	public function verbose_name() {
		return 'LINE';
	}

	protected function get_priority() {
		return 1008;
	}

	public function get_url( $post = null ) {
		return $this->add_query_params( [
			'url' => get_permalink( $post ),
		], 'https://social-plugins.line.me/lineit/share', $post );
	}
}
