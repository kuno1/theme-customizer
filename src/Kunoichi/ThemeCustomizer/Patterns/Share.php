<?php

namespace Kunoichi\ThemeCustomizer\Patterns;


use Kunoichi\ThemeCustomizer\CustomizerSetting;
use Kunoichi\ThemeCustomizer\Controller\MultipleCheckbox;
use Kunoichi\ThemeCustomizer\Models\AbstractBrand;

class Share extends CustomizerSetting {
	
	/**
	 * @var null|AbstractBrand[]
	 */
	private static $brands = null;
	
	protected function after_init() {
		$this->set_section_id();
	}
	
	protected function get_fields() {
		return apply_filters( 'theme_customizer_share_fields', [
			'share_buttons'  => [
				'label'         => __( 'Display Share Buttons', 'theme-customizer' ),
				'type'          => 'text',
				'stored'        => 'option',
				'choices'       => $this->get_positions(),
				'control_class' => MultipleCheckbox::class,
			],
			'share_services' => [
				'label'         => __( 'Services', 'theme-customizer' ),
				'type'          => 'text',
				'stored'        => 'option',
				'choices'       => $this->get_brands_options(),
				'control_class' => MultipleCheckbox::class,
				'description'   => __( 'If none is selected, all services are activated by default.', 'theme-customizer' ),
			],
			'share_styles'   => [
				'label'         => __( 'Share Button Styles', 'theme-customizer' ),
				'type'          => 'select',
				'stored'        => 'option',
				'choices'       => $this->get_styles(),
			],
		] );
	}
	
	protected function get_positions() {
		return apply_filters( 'theme_customizer_share_positions', [
			'after_contents' => __( 'After Contents', 'theme-customizer' ),
		] );
	}
	
	protected function get_services() {
		return apply_filters( 'theme_customizer_share_positions', [
			'after_contents' => __( 'After Contents', 'theme-customizer' ),
		] );
	}
	
	/**
	 * Get styles
	 *
	 * @return array
	 */
	protected function get_styles() {
		return apply_filters( 'theme_customizer_share_styles', [
			'' => __( 'Default', 'theme-customizer' ),
		] );
	}
	
	protected function set_section_id() {
		$this->section_id = apply_filters( 'theme_customizer_sharing_options_section', '' );
	}
	
	protected function section_setting() {
		return $this->section_id ? [] : apply_filters( 'theme_customizer_section', [
			'title'       => __( 'Sharing', 'theme-customizer' ),
			'priority'    => 99,
			'description' => __( 'Sharing options for your posts.', 'theme-customizer' ),
		] );
	}
	
	/**
	 * Get option for brand.
	 */
	protected function get_brands_options() {
		$options = [];
		foreach ( self::get_brands() as $brand ) {
			$options[ $brand->slug() ] = $brand->get_label();
		}
		return $options;
	}
	
	/**
	 * Get registered brands.
	 *
	 * @return AbstractBrand[]
	 */
	public static function get_brands() {
		if ( is_null( self::$brands ) ) {
			$brands = [];
			$dir    = dirname( __DIR__ ) . '/Models/Brand';
			if ( is_dir( $dir ) ) {
				foreach ( scandir( $dir ) as $file ) {
					if ( ! preg_match( '/^(.*)\.php$/u', $file, $match ) ) {
						continue;
					}
					$brands[] = "Kunoichi\\ThemeCustomizer\\Models\\Brand\\{$match[1]}";
				}
			}
			$class_names = apply_filters( 'theme_customizer_brand_classes', $brands );
			$instances   = array_filter( array_map( function( $brand ) {
				if ( ! class_exists( $brand ) ) {
					return false;
				}
				$refl = new \ReflectionClass( $brand );
				if ( ! $refl->isSubclassOf( AbstractBrand::class ) ) {
					return false;
				}
				return new $brand();
			}, $brands ) );
			usort( $instances, function( $a, $b ) {
				if ( $a->priority() > $b->priority() ) {
					return -1;
				} elseif ( $b->priority() > $a->priority() ) {
					return 1;
				} elseif ( $a->slug() > $b->slug() ) {
					return 1;
				} elseif ( $b->slug() > $a->slug() ) {
					return -1;
				} else {
					return 0;
				}
			} );
			self::$brands = $instances;
		}
		return self::$brands;
	}
	
	/**
	 * Get available brands.
	 *
	 * @param null|int|\WP_Post $post
	 *
	 * @return AbstractBrand[]
	 */
	public static function get_available_brands( $post = null ) {
		$available = array_filter( explode( ',', get_option( 'share_services', '' ) ) );
		$brands = self::get_brands();
		if ( $available ) {
			$brands = array_filter( $brands, function( $brand ) use ( $available ) {
				return in_array( $brand->slug(), $available );
			} );
		}
		return apply_filters( 'theme_customizer_available_brands', $brands, $post );
	}
	
	/**
	 * Get position to display
	 *
	 * @param string            $position
	 * @param int|\WP_Post|null $post
	 *
	 * @return bool
	 */
	public static function should_display( $position, $post = null ) {
		$positions = explode( ',', get_option( 'share_buttons', '' ) );
		return in_array( $position, $positions );
	}
	
	/**
	 * Render share links.
	 *
	 * @param string            $position
	 * @param null|int|\WP_Post $post
	 * @return string
	 */
	public static function render( $position, $post = null ) {
		if ( ! self::should_display( $position, $post ) ) {
			return '';
		}
		$brands = self::get_available_brands( $post );
		if ( ! $brands ) {
			return '';
		}
		$html = apply_filters( 'theme_customizer_rendered_html', '', $position, $brands, $post );
		if ( $html ) {
			return $html;
		}
		// Build html.
		$list = implode( "\n", array_map( function( $brand ) use ( $post ) {
			return sprintf( '<li class="share-buttons-item">%s</li>', $brand->get_html( $post ) );
		}, $brands ) );
		$html = <<<HTML
			<nav class="share-buttons-nav">
				<ul class="share-buttons-list">
					{$list}
				</ul>
			</nav>
HTML;
		return apply_filters( 'theme_customizer_predefined_html', $html, $brands, $post, $position );
	}
}
