<?php
/**
 * Gutenberg block: FAQ Accordion (server-rendered).
 *
 * Repeater of question/answer pairs, optional FAQPage schema. Attributes:
 * faqs (array of { question, answer }), showSchema (boolean).
 * Outputs FAQPage JSON-LD when showSchema is true. Accordion is keyboard-accessible.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_FAQ_Block
 */
class CCS_FAQ_Block {

	const BLOCK_NAME = 'ccs/faq';

	/**
	 * Default FAQ items for auto-insert when block is first added.
	 *
	 * @return array<int, array{question: string, answer: string}>
	 */
	public static function get_default_faqs() {
		return array(
			array(
				'question' => __( 'What services do you offer?', 'ccs-wp-theme' ),
				'answer'   => __( 'We offer a range of care services including personal care, companionship, and specialist support. Get in touch to discuss your needs.', 'ccs-wp-theme' ),
			),
			array(
				'question' => __( 'What areas do you cover?', 'ccs-wp-theme' ),
				'answer'   => __( 'We provide care across the local area. Contact us to confirm we cover your postcode.', 'ccs-wp-theme' ),
			),
			array(
				'question' => __( 'How do I start care?', 'ccs-wp-theme' ),
				'answer'   => __( 'You can start by calling us or filling in the contact form. We will arrange a free assessment and agree a care plan with you.', 'ccs-wp-theme' ),
			),
			array(
				'question' => __( 'Are you CQC regulated?', 'ccs-wp-theme' ),
				'answer'   => __( 'Yes, we are registered and regulated by the Care Quality Commission. You can view our latest report on the CQC website.', 'ccs-wp-theme' ),
			),
			array(
				'question' => __( 'What are your rates?', 'ccs-wp-theme' ),
				'answer'   => __( 'Our rates depend on the type and amount of care you need. We provide a clear quote after your assessment with no obligation.', 'ccs-wp-theme' ),
			),
		);
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	/**
	 * Register block type and assets.
	 */
	public function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		wp_register_script(
			'ccs-faq-accordion',
			THEME_URL . '/assets/js/faq-accordion.js',
			array(),
			THEME_VERSION
		);

		wp_register_script(
			'ccs-faq-block-editor',
			THEME_URL . '/assets/js/blocks/faq-block.js',
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
			'ccs-faq-block-editor',
			THEME_URL . '/assets/css/blocks/faq.css',
			array(),
			THEME_VERSION
		);

		wp_register_style(
			'ccs-faq-block',
			THEME_URL . '/assets/css/blocks/faq.css',
			array(),
			THEME_VERSION
		);

		register_block_type(
			self::BLOCK_NAME,
			array(
				'api_version'     => 2,
				'attributes'      => array(
					'faqs'       => array(
						'type'    => 'array',
						'default' => self::get_default_faqs(),
						'items'   => array(
							'type'       => 'object',
							'properties' => array(
								'question' => array( 'type' => 'string', 'default' => '' ),
								'answer'   => array( 'type' => 'string', 'default' => '' ),
							),
						),
					),
					'showSchema' => array(
						'type'    => 'boolean',
						'default' => true,
					),
				),
				'render_callback' => array( $this, 'render' ),
				'editor_script'   => 'ccs-faq-block-editor',
				'editor_style'    => 'ccs-faq-block-editor',
				'script'          => 'ccs-faq-accordion',
				'style'           => 'ccs-faq-block',
			)
		);
	}

	/**
	 * Build FAQPage schema for JSON-LD.
	 *
	 * @param array $faqs Array of { question, answer }.
	 * @return array Schema array.
	 */
	private function build_faq_schema( array $faqs ) {
		$main_entity = array();
		foreach ( $faqs as $item ) {
			$q = isset( $item['question'] ) ? trim( (string) $item['question'] ) : '';
			$a = isset( $item['answer'] ) ? trim( (string) $item['answer'] ) : '';
			if ( $q === '' && $a === '' ) {
				continue;
			}
			$main_entity[] = array(
				'@type'          => 'Question',
				'name'           => $q !== '' ? $q : __( 'Question', 'ccs-wp-theme' ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => $a !== '' ? $a : '',
				),
			);
		}
		if ( empty( $main_entity ) ) {
			return array();
		}
		return array(
			'@context'   => 'https://schema.org',
			'@type'       => 'FAQPage',
			'mainEntity'  => $main_entity,
		);
	}

	/**
	 * Server-side render callback.
	 *
	 * @param array $attributes Block attributes.
	 * @return string HTML output.
	 */
	public function render( $attributes ) {
		$faqs        = isset( $attributes['faqs'] ) && is_array( $attributes['faqs'] ) ? $attributes['faqs'] : array();
		$show_schema = ! empty( $attributes['showSchema'] );

		// Use default FAQs when none set (e.g. legacy block or empty state).
		if ( empty( $faqs ) ) {
			$faqs = self::get_default_faqs();
		}

		$schema = $show_schema ? $this->build_faq_schema( $faqs ) : array();
		$out    = '';

		if ( ! empty( $schema ) ) {
			$out .= '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>';
		}

		$out .= '<div class="wp-block-ccs-faq ccs-faq" role="region" aria-label="' . esc_attr__( 'Frequently asked questions', 'ccs-wp-theme' ) . '">';
		$out .= '<div class="ccs-faq__list">';

		foreach ( $faqs as $index => $item ) {
			$question  = isset( $item['question'] ) ? trim( (string) $item['question'] ) : '';
			$answer    = isset( $item['answer'] ) ? trim( (string) $item['answer'] ) : '';
			$id        = 'ccs-faq-' . get_the_ID() . '-' . $index;
			$id_answer = $id . '-answer';

			$out .= '<div class="ccs-faq__item">';
			$out .= '<h3 class="ccs-faq__question-wrap">';
			$out .= '<button type="button" class="ccs-faq__question" id="' . esc_attr( $id ) . '" aria-expanded="false" aria-controls="' . esc_attr( $id_answer ) . '" data-ccs-faq-toggle>';
			$out .= '<span class="ccs-faq__question-text">' . ( $question !== '' ? wp_kses_post( $question ) : '&nbsp;' ) . '</span>';
			$out .= '<span class="ccs-faq__icon" aria-hidden="true"></span>';
			$out .= '</button>';
			$out .= '</h3>';
			$out .= '<div id="' . esc_attr( $id_answer ) . '" class="ccs-faq__answer" role="region" aria-labelledby="' . esc_attr( $id ) . '" aria-hidden="true" hidden>';
			$out .= '<div class="ccs-faq__answer-inner">';
			$out .= $answer !== '' ? wp_kses_post( wpautop( $answer ) ) : '&nbsp;';
			$out .= '</div>';
			$out .= '</div>';
			$out .= '</div>';
		}

		$out .= '</div></div>';

		return $out;
	}
}
