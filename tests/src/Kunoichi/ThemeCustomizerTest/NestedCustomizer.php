<?php

namespace Kunoichi\ThemeCustomizerTest;


use Kunoichi\ThemeCustomizer\CustomizerSetting;

class NestedCustomizer extends CustomizerSetting {
	
	protected $panel_id = 'nested';
	
	protected $section_id = 'nested_child';
	
	protected function section_setting() {
		return [
			'title' => 'Nested Section',
		];
	}
	
	protected function panel_settings() {
		return [
			'title'       => 'Nested Panel',
			'description' => 'This panel is added by Kunoichi Theme Customizer.',
			'priority'    => 200,
		];
	}
	
	protected function get_fields() {
		return [
			'nested_section_1' => [
				'label' => 'Nested Field 1',
			],
			'nested_section_2' => [
				'label' => 'Nested Field 2',
			],
		];
	}
	
	
}
