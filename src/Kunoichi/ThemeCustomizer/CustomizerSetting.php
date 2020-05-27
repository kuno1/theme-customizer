<?php

namespace Kunoichi\ThemeCustomizer;

use Hametuha\SingletonPattern\Singleton;

/**
 * Class CustomizerSetting
 */
abstract class CustomizerSetting extends Singleton {
	
	use Utilities;
	
	protected $panel_id = '';
	
	protected $section_id = '';
	
	/**
	 * If this setting is deprecated, set this true and this class will be obsoleted.
	 *
	 * @var bool
	 */
	protected $duplicated = false;
	
	/**
	 * Do something inside constructor.
	 */
	protected function init() {
		if ( $this->duplicated ) {
			return;
		}
		add_action( 'customize_register', [ $this, 'customize_register' ] );
		$this->after_init();
	}
	
	/**
	 * Executed just after initialized.
	 */
	protected function after_init() {
		// Do something here.
	}
	
	/**
	 * Register customizer setting.
	 *
	 * @param \WP_Customize_Manager $wp_customizer
	 */
	public function customize_register( $wp_customizer ) {
		// Register panel.
		$panel_setting = $this->panel_settings();
		if ( $panel_setting ) {
			$wp_customizer->add_panel( $this->panel_id, $panel_setting );
		}
		// Register setting if required.
		$section_setting = $this->section_setting();
		if ( $section_setting ) {
			if ( $this->panel_id ) {
				$section_setting[ 'panel' ] = $this->panel_id;
			}
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
			'type' => isset( $args[ 'stored' ] ) ? $args['stored'] : 'theme_mod',
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
	 * If proper array returned, register panel.
	 *
	 * @see \WP_Customize_Manager::add_panel
	 * @return array
	 */
	protected function panel_settings() {
		return [];
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
	abstract protected function get_fields();
	
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
	
	/**
	 * Get post type for multiple choices.
	 *
	 * @param array    $query
	 * @param string[] $exclude
	 * @return array
	 */
	protected function get_post_types( $query = [], $exclude = [] ) {
		if ( ! $query ) {
			$query = [ 'public' => true ];
		}
		$post_types = [];
		foreach ( get_post_types( $query, OBJECT ) as $post_type ) {
			if ( in_array( $post_type->name, $exclude ) ) {
				continue;
			}
			$post_types[ $post_type->name ] = $post_type->label;
		}
		return $post_types;
	}
}
