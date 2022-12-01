<?php

namespace Kunoichi\ThemeCustomizer\Models;

use Hametuha\StringUtility\NamingConventions;

/**
 * Abstract brand
 *
 * @package Kunoichi\ThemeCustomizer\Models
 */
abstract class AbstractBrand {

	use NamingConventions;

	/**
	 * Brand priority.
	 *
	 * @return int
	 */
	final public function priority() {
		return apply_filters( 'theme_customizer_brand_priority', $this->get_priority(), $this->slug() );
	}

	/**
	 * Priority
	 *
	 * @return int
	 */
	protected function get_priority() {
		return 100;
	}

	/**
	 * Add query args to URL.
	 *
	 * @param array             $params
	 * @param string            $url
	 * @param int|\WP_Post|null $post
	 * @return string
	 */
	protected function add_query_params( $params, $url, $post = null ) {
		$filtered = [];
		foreach ( $params as $key => $val ) {
			$filtered[ $key ] = rawurlencode( $val );
		}
		return apply_filters( 'theme_customizer_share_url_params', add_query_arg( $filtered, $url ), $this->slug(), $post );
	}

	/**
	 * Get base class name. e.g. Facebook.php -> Facebook
	 *
	 * @return string
	 */
	protected function get_base_class_name() {
		$class_name = explode( '\\', get_called_class() );
		return $class_name[ count( $class_name ) - 1 ];
	}

	/**
	 * Returns slug.
	 *
	 * Default is upper camel.
	 *
	 * @return string
	 */
	public function slug() {
		return $this->camel_to_kebab( $this->get_base_class_name() );
	}

	/**
	 *
	 *
	 * @return string
	 */
	public function icon_slug() {
		return apply_filters( 'theme_customizer_brand_icon_slug', $this->slug(), $this->slug() );
	}

	/**
	 * Get icon class name.
	 *
	 * @return string
	 */
	public function icon_class() {
		return apply_filters( 'theme_customizer_brand_icon_class', 'fab fa-' . $this->icon_slug(), $this->slug() );
	}

	/**
	 * Brand icon
	 *
	 * @return string
	 */
	protected function target() {
		return apply_filters( 'theme_customizer_brand_target', '_blank', $this->slug() );
	}

	/**
	 * Link rel.
	 *
	 * @return string
	 */
	protected function rel() {
		return apply_filters( 'theme_customize_brand_link_rel', 'noopener noreferrer', $this->slug() );
	}

	/**
	 * @param null|int|\WP_Post $post
	 *
	 * @return mixed|void
	 */
	protected function link_args( $post = null ) {
		$args     = [
			'class'  => apply_filters( 'theme_customizer_brand_link_class', sprintf( 'share-buttons-link share-buttons-link-%s', $this->slug() ), $this->slug(), $post ),
			'target' => $this->target(),
			'href'   => $this->get_link( $post ),
			'rel'    => $this->rel(),
		];
		$filtered = [];
		foreach ( $args as $attr => $value ) {
			if ( ! $value ) {
				continue;
			}
			$filtered[ $attr ] = $value;
		}
		return apply_filters( 'theme_customizer_brand_link_attributes', $filtered, $this->slug(), $post );
	}

	/**
	 * Get markup HTML
	 *
	 * @param null|int|\WP_Post $post
	 * @return string
	 */
	public function get_html( $post = null ) {
		ob_start();
		$attr = [];
		foreach ( $this->link_args( $post ) as $key => $val ) {
			$attr[] = sprintf( '%s="%s"', $key, esc_attr( $val ) );
		}
		$attr = implode( ' ', $attr );
		?>
		<a <?php echo $attr; ?>>
			<?php
			$icon = $this->icon_class();
			if ( $icon ) :
				?>
				<i class="<?php echo esc_attr( $icon ); ?>"><?php echo apply_filters( 'theme_customizer_brand_icon_label', '', $this->slug(), $post ); ?></i>
				<?php
			endif;

			$label = $this->get_label( $post );
			if ( $label ) :
				?>
				<span class="<?php echo esc_attr( 'theme_customizer_brand_label_class', sprintf( 'share-buttons-label share-buttons-label-%s', $this->slug() ), $this->slug(), $post ); ?>">
					<?php echo esc_html( $label ); ?>
				</span>
			<?php endif; ?>
		</a>
		<?php
		$markup = ob_get_contents();
		ob_end_clean();
		return apply_filters( 'theme_customzier_share_button_html', $markup, $this->slug(), $post );
	}

	/**
	 * Get link URL.
	 *
	 * @param null|int|\WP_Post $post
	 * @return string
	 */
	protected function get_link( $post = null ) {
		return apply_filters( 'theme_customizer_brand_link_href', $this->get_url( $post ), $this->slug(), $post );
	}

	/**
	 * Returns link to share
	 *
	 * @param null|int|\WP_Post $post
	 *
	 * @return string
	 */
	abstract public function get_url( $post = null );

	/**
	 * Get label for link.
	 *
	 * @return string
	 */
	public function verbose_name() {
		return $this->get_base_class_name();
	}

	/**
	 * Get label to display.
	 *
	 * @param null|int|\WP_Post $post
	 *
	 * @return string
	 */
	public function get_label( $post = null ) {
		return apply_filters( 'theme_customizer_brand_link_label', $this->verbose_name(), $this->slug(), $post );
	}
}
