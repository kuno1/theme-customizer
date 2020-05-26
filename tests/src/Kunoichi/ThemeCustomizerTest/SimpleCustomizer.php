<?php

namespace Kunoichi\ThemeCustomizerTest;


use Kunoichi\ThemeCustomizer\CustomizerSetting;

/**
 * Simple customizer.
 *
 * @package theme-customizer
 */
class SimpleCustomizer extends CustomizerSetting {
	
	protected $section_id = 'title_tagline';
	
	
	/**
	 * @return array
	 */
	protected function get_fields() {
		return [
			'site_setting_title' => [
				'label'       => 'Added Title',
				'description' => 'This section is added by Kunoichi Theme Customizer.',
			],
		];
	}
	
}
