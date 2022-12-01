<?php

namespace Kunoichi\ThemeCustomizer\Patterns;


use Kunoichi\ThemeCustomizer\CustomizerSetting;

/**
 * Simple SEO settings here.
 *
 * @package theme-customizer
 */
class Seo extends CustomizerSetting {

	protected $section_id = 'kunoichi_customizer_seo';

	protected $labels = [];

	protected function after_init() {
		$this->labels = $this->default_label();
		add_action( 'wp_head', [ $this, 'display_ogp' ] );
		add_action( 'wp_head', [ $this, 'display_gtag' ], 1 );
		add_action( 'wp_head', [ $this, 'display_tag_manager' ], 1 );
		add_action( 'wp_body_open', [ $this, 'display_tag_manager_iframe' ], 1 );
		add_action( 'save_post', [ $this, 'save_meta' ], 10, 2 );
		add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 10, 2 );
	}

	protected function section_setting() {
		return [
			'title'       => $this->get_label( 'sec_title' ),
			'description' => $this->get_label( 'sec_description' ),
			'priority'    => 160,
		];
	}

	/**
	 * Set labels.
	 *
	 * @param string|array $key
	 * @param string       $value
	 */
	public function set_labels( $key, $value = '' ) {
		if ( is_array( $key ) ) {
			$this->labels = array_merge( $this->labels, $key );
		} else {
			$this->labels[ $key ] = $value;
		}
	}

	/**
	 *
	 *
	 * @param string $key
	 * @return string
	 */
	public function get_label( $key ) {
		return isset( $this->labels[ $key ] ) ? $this->labels[ $key ] : '';
	}

	/**
	 * Default Label
	 *
	 * @return array
	 */
	public function default_label() {
		return [
			'sec_title'           => __( 'Meta and SEO', 'theme-customizer' ),
			'sec_description'     => __( 'In this section, you can set basic Optimization for Search Engine(SEO) feature.', 'theme-customizer' ),
			'gtag_label'          => __( 'Google Analytics Tracking ID', 'theme-customizer' ),
			'gtag_description'    => __( 'If set, tracking code(gtag.js) will be generated.', 'theme-customizer' ),
			'tm_label'            => __( 'Tag Manager Container ID', 'theme-customizer' ),
			'tm_description'      => __( 'If set, Google Tag Manger script will be generated.', 'theme-customizer' ),
			'sd_label'            => __( 'Top page description', 'theme-customizer' ),
			'sd_description'      => __( 'Used as a description of your site in search engine results. Write in 50-80 words.', 'theme-customizer' ),
			'togp_label'          => __( 'Top page OGP', 'theme-customizer' ),
			'togp_description'    => __( 'This image will be used on site top OGP image. OGP image will displayed if your site is shared on SNS.', 'theme-customizer' ),
			'dogp_label'          => __( 'Default OGP', 'theme-customizer' ),
			'dogp_description'    => __( 'This image will be used as OGP if post has no eye catch.', 'theme-customizer' ),
			'tw_label'            => __( 'Twitter Account', 'theme-customizer' ),
			'tw_description'      => __( 'Set twitter account name of your site.', 'theme-customizer' ),
			'tw_card_label'       => __( 'Twitter\'s OGP card size', 'theme-customizer' ),
			'tw_card_description' => __( 'Default twitter card layout. Overridable on each post.', 'theme-customizer' ),
			'fb_label'            => __( 'Facebook App ID', 'theme-customizer' ),
			'fb_description'      => __( 'Enter Facebook App ID. This will be displayed on Facebook wall.', 'theme-customizer' ),
			'kill_label'          => __( 'Stop all SEO feature', 'theme-customizer' ),
			'kill_description'    => __( 'If checked, all seo feature of this theme will be stopped. Useful when some SEO plugins are activated.', 'theme-customizer' ),
		];
	}

	/**
	 * Get default OGP size.
	 *
	 * @return int[]
	 */
	protected function default_opg_size() {
		return apply_filters( 'kunoichi_theme_customizer_default_ogp_size', [ 1200, 630 ] );
	}

	/**
	 * Get fields.
	 *
	 * Override this to customize.
	 *
	 * @return array
	 */
	protected function get_fields() {
		list( $width, $height ) = $this->default_opg_size();
		$fields                 = [
			'tsmed_gtag'              => [
				'label'       => $this->get_label( 'gtag_label' ),
				'description' => $this->get_label( 'gtag_description' ),
				'type'        => 'text',
				'default'     => '',
				'input_attrs' => [
					'placeholder' => 'UA-00000000000-1',
				],
			],
			'tsmed_tag_manager'       => [
				'label'       => $this->get_label( 'tm_label' ),
				'description' => $this->get_label( 'tm_description' ),
				'type'        => 'text',
				'default'     => '',
				'input_attrs' => [
					'placeholder' => 'GTM-ABC123',
				],
			],
			'tsmed_meta_site_desc'    => [
				'label'       => $this->get_label( 'sd_label' ),
				'description' => $this->get_label( 'sd_description' ),
				'type'        => 'textarea',
				'default'     => '',
			],
			'tsmed_meta_site_ogp'     => [
				'label'         => $this->get_label( 'togp_label' ),
				'description'   => $this->get_label( 'togp_description' ),
				'control_class' => 'WP_Customize_Cropped_Image_Control',
				'default'       => '',
				'width'         => $width,
				'height'        => $height,
			],
			'tsmed_meta_default_ogp'  => [
				'label'         => $this->get_label( 'dogp_label' ),
				'description'   => $this->get_label( 'dogp_description' ),
				'control_class' => 'WP_Customize_Cropped_Image_Control',
				'default'       => '',
				'width'         => $width,
				'height'        => $height,
			],
			'tsmed_meta_twitter'      => [
				'label'       => $this->get_label( 'tw_label' ),
				'description' => $this->get_label( 'tw_description' ),
				'type'        => 'text',
				'default'     => '',
				'input_attrs' => [
					'placeholder' => '@screen_name',
				],
			],
			'tsmed_meta_twitter_card' => [
				'label'       => $this->get_label( 'tw_card_label' ),
				'description' => $this->get_label( 'tw_card_description' ),
				'type'        => 'select',
				'default'     => 'summary',
				'choices'     => $this->twitter_cards_layout(),
			],
			'tsmed_meta_fbapp'        => [
				'label'       => $this->get_label( 'fb_label' ),
				'description' => $this->get_label( 'fb_description' ),
				'type'        => 'text',
				'default'     => '',
				'input_attrs' => [
					'placeholder' => 'e.g. 1234567890',
				],
			],
			'tsmed_kill_seo'          => [
				'label'       => $this->get_label( 'kill_label' ),
				'description' => $this->get_label( 'kill_description' ),
				'type'        => 'checkbox',
				'default'     => '',
			],
		];
		return $fields;
	}

	/**
	 * Get OGP information.
	 *
	 * @return array
	 */
	protected function get_ogp_information() {
		$metas = [
			''        => [],
			'og'      => [],
			'twitter' => [],
			'fb'      => [],
		];
		// Set Title
		$title                    = wp_get_document_title();
		$metas['og']['title']     = $title;
		$metas['og']['site_name'] = get_bloginfo( 'name' );
		// Type
		$metas['og']['type'] = is_front_page() ? 'website' : apply_filters( 'kunoichi_meta_type', 'article' );
		// Description and URL.
		$desc = '';
		$url  = '';
		if ( is_front_page() ) {
			$desc      = get_bloginfo( 'description' );
			$site_desc = get_theme_mod( 'tsmed_meta_site_desc' );
			if ( $site_desc ) {
				$desc = $site_desc;
			}
			$url = home_url();
		} elseif ( is_singular() ) {
			$excerpt  = get_the_excerpt( get_queried_object() );
			$alt_meta = get_post_meta( get_queried_object_id(), '_meta_description_alt', true );
			if ( $alt_meta ) {
				$excerpt = $alt_meta;
			}
			$desc = apply_filters( 'kunoichi_meta_description', $excerpt );
			$url  = get_permalink( get_queried_object() );
		} elseif ( is_category() || is_tag() || is_tax() ) {
			$desc = get_queried_object()->description;
			$url  = get_term_link( get_queried_object() );
		}
		$desc                       = preg_replace( "/[\r\n]/u", '', strip_tags( trim( $desc ) ) );
		$metas['']['description']   = $desc;
		$metas['og']['description'] = $desc;
		// Set URL.
		$metas['og']['url'] = $url;
		// Image
		$image            = '';
		$meta_default_ogp = get_theme_mod( 'tsmed_meta_default_ogp' );
		if ( $meta_default_ogp ) {
			// Default image.
			$image = wp_get_attachment_image_url( $meta_default_ogp, $this->default_opg_size() );
		}
		if ( is_singular() && has_post_thumbnail( get_queried_object() ) ) {
			// Post's thumbnail.
			$image = get_the_post_thumbnail_url( get_queried_object(), $this->default_opg_size() );
		}
		if ( is_front_page() ) {
			$meta_site_ogp = get_theme_mod( 'tsmed_meta_site_ogp' );
			// Top page.
			if ( $meta_site_ogp ) {
				$image = wp_get_attachment_image_url( $meta_site_ogp, $this->default_opg_size() );
			}
		}
		if ( $image ) {
			$metas['og']['image'] = $image;
		}
		// Keywords
		$keywords = [];
		if ( is_singular() ) {
			$taxonomies = [ 'post_tag', 'category' ];
			$post_type  = get_post_type_object( get_queried_object()->post_type );
			if ( $post_type->taxonomies ) {
				$taxonomies = array_merge( $taxonomies, $post_type->taxonomies );
			}
			foreach ( $taxonomies as $taxonomy ) {
				$terms = get_the_terms( get_queried_object(), $taxonomy );
				if ( $terms && ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						$keywords[] = $term->name;
					}
				}
			}
		}
		if ( $keywords ) {
			$metas['']['keywords'] = implode( ',', $keywords );
		}
		// Twitter
		$metas['twitter']['card'] = $this->get_twitter_card_layout( is_singular() ? get_queried_object() : null );
		$twitter                  = get_theme_mod( 'tsmed_meta_twitter' );
		if ( $twitter ) {
			$metas['twitter']['site'] = '@' . ltrim( $twitter, '@' );
		}
		if ( is_singular() ) {
			$author_twitter = apply_filters( 'kunoichi_meta_author_twitter', get_the_author_meta( 'twitter', get_queried_object()->post_author ) );
			if ( $author_twitter ) {
				$metas['twitter']['creator'] = '@' . ltrim( $author_twitter, '@' );
			}
		}
		// FB
		$app_id = get_theme_mod( 'tsmed_meta_fbapp' );
		if ( $app_id ) {
			$metas['fb']['app_id'] = $app_id;
		}
		// Copy rights and author.
		$author = get_bloginfo( 'name' );
		if ( is_singular() && ! is_front_page() ) {
			$author = get_the_author_meta( 'display_name', get_queried_object()->post_author );
			$author = apply_filters( 'kunoichi_meta_author_name', $author );
		}
		$metas['']['author']    = $author;
		$metas['']['copyright'] = apply_filters( 'kunoichi_meta_copyright', sprintf( '&copy;%d %s', date_i18n( 'Y' ), get_bloginfo( 'name' ) ) );
		// Set OGP.
		return apply_filters( 'kunoichi_ogp_information', $metas );
	}

	/**
	 * Display OGP information.
	 */
	public function display_ogp() {
		if ( $this->is_disabled() ) {
			// SEO setting is killed.
			return;
		}
		$metas = $this->get_ogp_information();
		$lines = [];
		foreach ( $metas as $name => $values ) {
			switch ( $name ) {
				case 'og':
				case 'fb':
					$prop_name = 'property';
					break;
				default:
					$prop_name = 'name';
					break;
			}
			foreach ( $values as $key => $value ) {
				if ( ! $value ) {
					continue;
				}
				if ( $name ) {
					$key = $name . ':' . $key;
				}
				$lines[] = sprintf( '<meta %s="%s" content="%s" />', $prop_name, esc_attr( $key ), esc_attr( $value ) );
			}
		}
		if ( $lines ) {
			echo "\n\t<!-- Theme SEO Setting start// -->";
			echo "\n\t" . implode( "\n\t", $lines );
			echo "\n\t<!-- // Theme SEO setting end-->\n";
		}
	}

	/**
	 * Display Google Analyitcs
	 */
	public function display_gtag() {
		$tracking_id = get_theme_mod( 'tsmed_gtag' );
		if ( ! $tracking_id ) {
			// Do nothing.
			return;
		}
		$src    = add_query_arg( [
			'id' => $tracking_id,
		], 'https://www.googletagmanager.com/gtag/js' );
		$config = (object) apply_filters( 'kunoichi_gtag_config', [] );
		?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="<?php echo esc_url( $src ); ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			<?php do_action( 'kunoichi_before_gtag' ); ?>
			gtag('config', '<?php echo esc_attr( $tracking_id ); ?>', <?php echo json_encode( $config ); ?> );
			<?php do_action( 'kunoichi_after_gtag' ); ?>
		</script>
		<?php
	}

	/**
	 * Tag manager script.
	 */
	public function display_tag_manager() {
		$container_id = get_theme_mod( 'tsmed_tag_manager' );
		if ( ! $container_id ) {
			return;
		}
		// Generate script.
		?>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
			new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
			j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
			'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo esc_js( $container_id ); ?>');</script>
		<!-- End Google Tag Manager -->
		<?php
	}

	/**
	 * Iframe for tag manager.
	 */
	public function display_tag_manager_iframe() {
		$container_id = get_theme_mod( 'tsmed_tag_manager' );
		if ( ! $container_id ) {
			return;
		}
		// Generate iframe
		?>
		<!-- Google Tag Manager (noscript) -->
		<noscript>
			<iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( $container_id ); ?>"
					height="0" width="0" style="display:none;visibility:hidden"></iframe>
		</noscript>
		<!-- End Google Tag Manager (noscript) -->
		<?php
	}

	/**
	 * Twitter cards patterns
	 *
	 * @return array
	 */
	public function twitter_cards_layout() {
		return apply_filters( 'theme_customizer_twitter_card_layouts', [
			'summary'             => __( 'Summary', 'theme-customizer' ),
			'summary_large_image' => __( 'Summary with large image', 'theme-customizer' ),
		] );
	}

	/**
	 * Save meta box info.
	 *
	 * @param int $post_id
	 * @param \WP_Post $post
	 */
	public function save_meta( $post_id, $post ) {
		if ( $this->is_disabled() || ! wp_verify_nonce( filter_input( INPUT_POST, '_themecustomizernonce' ), 'update_theme_customizer' ) ) {
			return;
		}
		foreach ( [
			'meta_description_alt',
			'meta_twitter_card',
		] as $key ) {
			update_post_meta( $post_id, '_' . $key, filter_input( INPUT_POST, $key ) );
		}
	}

	/**
	 * Display meta box.
	 *
	 * @param string $post_type
	 */
	public function add_meta_boxes( $post_type ) {
		if ( $this->is_disabled() ) {
			return;
		}
		add_meta_box( 'theme-customizer-seo-setting', __( 'SEO Setting', 'theme-customizer' ), function( $post ) {
			wp_nonce_field( 'update_theme_customizer', '_themecustomizernonce', false );
			?>
			<p>
				<label for="meta-description-alt"><?php esc_html_e( 'Meta Description', 'theme-customizer' ); ?></label>
				<textarea class="widefat" id="meta-description-al" name="meta_description_alt"><?php echo esc_textarea( get_post_meta( $post->ID, '_meta_description_alt', true ) ); ?></textarea>
				<span class="description">
					<?php esc_html_e( 'You can override meta description with text customized for search results.', 'theme-customizer' ); ?>
				</span>
			</p>
			<p>
				<label for="meta-twitter-card"><?php esc_html_e( 'Twitter Card Type', 'theme-customizer' ); ?></label>
				<select class="components-select-control" style="max-width: 100%; box-sizing: border-box" name="meta_twitter_card" id="meta-twitter-card">
					<?php
						$post_meta = get_post_meta( $post->ID, '_meta_twitter_card', true );
						$general   = get_theme_mod( 'tsmed_meta_twitter_card', 'summary' ) ?: 'summary';
					foreach ( array_merge( [
						'' => _x( 'Default', 'twitter-card', 'theme-customizer' ),
					], $this->twitter_cards_layout() ) as $value => $label ) {
						if ( $value === $general ) {
							$label .= _x( '(Default)', 'twitter-card', 'theme-customizer' );
						}
						printf( '<option value="%s"%s>%s</option>', esc_attr( $value ), selected( $value, $post_meta, false ), esc_html( $label ) );
					}
					?>
				</select>
			</p>
			<?php
		}, $post_type, 'side', 'low' );
	}

	/**
	 * Get twitter card layout
	 *
	 * @see https://developer.twitter.com/en/docs/twitter-for-websites/cards/overview/abouts-cards
	 * @param null|\WP_Post $post
	 * @return string
	 */
	public function get_twitter_card_layout( $post = null ) {
		$card = get_theme_mod( 'tsmed_meta_twitter_card', 'summary' ) ?: 'summary';
		if ( $post ) {
			$post_meta = get_post_meta( $post->ID, '_meta_twitter_card', true );
			if ( $post_meta ) {
				$card = $post_meta;
			}
		}
		return apply_filters( 'theme_customizer_twitter_card_size', $card );
	}

	/**
	 * Detect if SEO option is disabled.
	 *
	 * @return bool
	 */
	public function is_disabled() {
		return (bool) get_theme_mod( 'tsmed_kill_seo' );
	}
}
