<?php

namespace Kunoichi\ThemeCustomizer\Controller;


use Hametuha\StringUtility\Path;

class MultipleCheckbox extends \WP_Customize_Control {
	
	use Path;
	
	/**
	 * The type of customize control being rendered.
	 *
	 * @var    string
	 */
	public $type = 'multiple-checkbox';
	
	/**
	 * Enqueue scripts/styles.
	 */
	public function enqueue() {
		$path = dirname( dirname( dirname( __DIR__ ) ) ) . '/assets/js/multiple-checkbox.js';
		wp_enqueue_script( 'theme-customizer-multiple-checkbox', $this->path_to_url( $path ), [ 'jquery' ], filemtime( $path ), true );
	}
	
	/**
	 * Displays the control content.
	 */
	public function render_content() {
		if ( empty( $this->choices ) ) {
			return;
		}
		?>
		
		<?php if ( ! empty( $this->label ) ) : ?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<?php endif; ?>
		
		<?php if ( !empty( $this->description ) ) : ?>
			<span class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php endif; ?>
		
		<?php $multi_values = ! is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>
		
		<ul>
			<?php foreach ( $this->choices as $value => $label ) : ?>
				<li>
					<label>
						<input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> />
						<?php echo esc_html( $label ); ?>
					</label>
				</li>
			
			<?php endforeach; ?>
		</ul>
		
		<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
		<?php
	}
}