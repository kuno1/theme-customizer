<?php

namespace Kunoichi\ThemeCustomizer;

/**
 * Class CustomizerSetting
 */
abstract class CustomizerSetting extends Singleton {
	
	use Utilities;
	
	protected $section_id = '';
	
	/**
	 * Do something inside constructor.
	 */
	protected function init() {
		add_action( 'customize_register', [ $this, 'customize_register' ] );
	}
	
	/**
	 * Register customizer setting.
	 *
	 * @param \WP_Customize_Manager $wp_customizer
	 */
	public function customize_register( $wp_customizer ) {
		// Register setting if required.
		$section_setting = $this->section_setting();
		if ( $section_setting ) {
			$wp_customizer->add_section( $this->get_section(), $section_setting );
		}
		// Register all fields.
		foreach ( $this->get_fields() as $id => $args ) {
			$this->register_field( $wp_customizer, $id, $args );
		}
	}
	
	/**
	 * Register each fields.
	 *
	 * @param \WP_Customize_Manager $wp_customizer
	 * @param string                $id
	 * @param array                 $args
	 */
	protected function register_field( &$wp_customizer, $id, $args ) {
		// Add settings.
		$wp_customizer->add_setting( $id, array_merge( $args, [
			'type' => $args[ 'stored' ] ?? 'theme_mod',
		] ) );
		// Add Control.
		$control_class = 'WP_Customize_Control';
		if ( isset( $args['control_class'] ) && class_exists( $args['control_class'] ) ) {
			$control_class = $args['control_class'];
		}
		$args['section'] = $this->get_section();
		$wp_customizer->add_control( new $control_class( $wp_customizer, $id, $args ) );
	}
	
	/**
	 * If array returned, register section.
	 *
	 * @see \WP_Customize_Manager::add_section
	 * @return array `$args` of WP_Customize_Manager::add_section
	 */
	protected function section_setting() {
		return [];
	}
	
	/**
	 * Should return fields to register.
	 *
	 * @see \WP_Customize_Manager::add_setting()
	 * @see \WP_Customize_Manager::add_control()
	 * @return array A conjunction of add_setting() and add_control()
	 */
	abstract protected function get_fields():array;
	
	/**
	 * Get section id
	 *
	 * @return string
	 */
	protected function get_section() {
		if ( $this->section_id ) {
			return $this->section_id;
		} else {
			return $this->camelize( get_called_class() );
		}
	}
}
