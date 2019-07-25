<?php

namespace Kunoichi\ThemeCustomizerTest;


use Kunoichi\ThemeCustomizer\CustomizerSetting;

/**
 * Simple customizer.
 *
 * @package theme-customizer
 */
class SimpleCustomizer extends CustomizerSetting {
	
	protected $section_id = 'site-setting';
	
	
	/**
	 * @return array
	 */
	protected function get_fields() {
		return [
			'site_setting_title' => [
				'label'       => __( 'Title' ),
				'description' => '',
			],
		];
	}
	
}
