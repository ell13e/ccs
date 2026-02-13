<?php
/**
 * Gutenberg block: Call-to-Action (server-rendered).
 *
 * Heading, subheading, primary button, optional phone button. Attributes:
 * heading, subheading, buttonText, buttonUrl, style (primary|secondary|dark), showPhone.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_CTA_Block
 */
class CCS_CTA_Block {

	const BLOCK_NAME = 'ccs/cta';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	/**
	 * Register block type and enqueue editor assets.
	 */
	public function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			self::BLOCK_NAME,
			array(
				'api_version'     => 2,
				'attributes'      => array(
					'heading'    => array(
						'type'    => 'string',
						'default' => '',
					),
					'subheading' => array(
						'type'    => 'string',
						'default' => '',
					),
					'buttonText' => array(
						'type'    => 'string',
						'default' => '',
					),
					'buttonUrl'  => array(
						'type'    => 'string',
						'default' => '',
					),
					'style'      => array(
						'type'    => 'string',
						'default' => 'primary',
						'enum'    => array( 'primary', 'secondary', 'dark' ),
					),
					'showPhone'  => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
				'render_callback' => array( $this, 'render' ),
				'editor_script'   => 'ccs-cta-block-editor',
				'editor_style'    => 'ccs-cta-block-editor',
				'style'           => 'ccs-cta-block',
			)
		);

		wp_register_script(
			'ccs-cta-block-editor',
			THEME_URL . '/assets/js/blocks/cta-block.js',
			array(
				'wp-blocks',
				'wp-element',
				'wp-components',
				'wp-block-editor',
				'wp-i18n',
			),
			THEME_VERSION
		);

		wp_register_style(
			'ccs-cta-block-editor',
			THEME_URL . '/assets/css/blocks/cta.css',
			array(),
			THEME_VERSION
		);

		wp_register_style(
			'ccs-cta-block',
			THEME_URL . '/assets/css/blocks/cta.css',
			array(),
			THEME_VERSION
		);

		$phone = get_theme_mod( 'ccs_phone', '' );
		wp_localize_script(
			'ccs-cta-block-editor',
			'ccsCtaBlock',
			array(
				'phone'      => $phone,
				'phoneHref'  => $phone ? 'tel:' . preg_replace( '/\s+/', '', $phone ) : '',
				'styles'     => array(
					array( 'value' => 'primary', 'label' => __( 'Primary', 'ccs-wp-theme' ) ),
					array( 'value' => 'secondary', 'label' => __( 'Secondary', 'ccs-wp-theme' ) ),
					array( 'value' => 'dark', 'label' => __( 'Dark', 'ccs-wp-theme' ) ),
				),
			)
		);
	}

	/**
	 * Server-side render callback.
	 *
	 * @param array $attributes Block attributes.
	 * @return string HTML output.
	 */
	public function render( $attributes ) {
		$heading    = isset( $attributes['heading'] ) ? trim( (string) $attributes['heading'] ) : '';
		$subheading = isset( $attributes['subheading'] ) ? trim( (string) $attributes['subheading'] ) : '';
		$btn_text   = isset( $attributes['buttonText'] ) ? trim( (string) $attributes['buttonText'] ) : '';
		$btn_url    = isset( $attributes['buttonUrl'] ) ? esc_url( $attributes['buttonUrl'] ) : '';
		$style      = isset( $attributes['style'] ) && in_array( $attributes['style'], array( 'primary', 'secondary', 'dark' ), true )
			? $attributes['style']
			: 'primary';
		$show_phone = ! empty( $attributes['showPhone'] );

		$phone     = get_theme_mod( 'ccs_phone', '' );
		$phone_href = $phone ? 'tel:' . preg_replace( '/\s+/', '', $phone ) : '';

		$classes = array(
			'wp-block-ccs-cta',
			'ccs-cta',
			'ccs-cta--' . $style,
		);

		ob_start();
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<div class="ccs-cta__inner">
				<?php if ( $heading !== '' ) : ?>
					<h2 class="ccs-cta__heading"><?php echo wp_kses_post( $heading ); ?></h2>
				<?php endif; ?>
				<?php if ( $subheading !== '' ) : ?>
					<p class="ccs-cta__subheading"><?php echo wp_kses_post( $subheading ); ?></p>
				<?php endif; ?>
				<div class="ccs-cta__actions">
					<?php if ( $btn_text !== '' && $btn_url !== '' ) : ?>
						<a href="<?php echo esc_url( $btn_url ); ?>" class="ccs-cta__btn ccs-cta__btn--primary">
							<?php echo esc_html( $btn_text ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $show_phone && $phone_href !== '' ) : ?>
						<a href="<?php echo esc_url( $phone_href ); ?>" class="ccs-cta__btn ccs-cta__btn--phone">
							<?php echo esc_html( $phone ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
