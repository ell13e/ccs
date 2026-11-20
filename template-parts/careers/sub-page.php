<?php
/**
 * Body of a careers sub-page (Working with us, Professional Development).
 *
 * Both were the default long-form page: a contents list over six blocks of
 * text. Now: the page's opening paragraph beside one real photo, then the
 * editor's h2 sections as tabs. The calling template adds what is particular
 * to its page between and after (benefits, the career path).
 *
 * Args: photo (path under assets/images/), alt, title (the intro heading),
 * tabs_title (the heading over the index), id.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_sp_content = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
$ccs_sp_id      = isset( $args['id'] ) ? sanitize_html_class( $args['id'] ) : 'careers-page';
?>
<?php if ( empty( $args['skip_intro'] ) ) : // A page that opens with the homepage-style hero prints its own intro. ?>
<section class="ccs-section careers-intro-split" aria-labelledby="<?php echo esc_attr( $ccs_sp_id ); ?>-intro-heading">
	<div class="container container--lg">
		<div class="ccs-media-split">
			<div class="careers-intro-split__text">
				<h2 id="<?php echo esc_attr( $ccs_sp_id ); ?>-intro-heading" class="ccs-title"><?php echo esc_html( $args['title'] ); ?></h2>
				<?php if ( '' !== trim( wp_strip_all_tags( $ccs_sp_content['intro'] ) ) ) : ?>
					<div class="ccs-lede ccs-lede--large"><?php echo $ccs_sp_content['intro']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- the_content output, printed as the_content() would. ?></div>
				<?php endif; ?>
			</div>
			<?php
			ccs_section_photo(
				$args['photo'],
				array(
					'alt'   => $args['alt'],
					'sizes' => '(min-width: 900px) 520px, 92vw',
					'class' => 'ccs-photo--landscape',
					'position' => isset( $args['position'] ) ? $args['position'] : '',
				)
			);
			?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php // A page that lays its sections out itself passes skip_topics. ?>
<?php if ( empty( $args['skip_topics'] ) && ! empty( $ccs_sp_content['sections'] ) ) : ?>
	<section class="ccs-section ccs-section--tint careers-topics" aria-labelledby="<?php echo esc_attr( $ccs_sp_id ); ?>-topics-heading">
		<div class="container container--lg">
			<?php
			get_template_part(
				'template-parts/careers/topics',
				null,
				array(
					'id'       => $ccs_sp_id,
					'title'    => $args['tabs_title'],
					'sections' => $ccs_sp_content['sections'],
					'numbered' => ! empty( $args['numbered'] ),
				)
			);
			?>
		</div>
	</section>
<?php endif; ?>
