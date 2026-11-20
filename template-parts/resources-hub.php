<?php
/**
 * Resources page body (Nov 2026).
 *
 * This page was a column of prose with an "On this page" rail: a design
 * review rated it the weakest on the site (nothing to look at). It is a hub,
 * so it is now laid out as one: one photo, and beside it the page's own text
 * as rows a family can scan and tap. Structure after the rule-divided link
 * lists seen in the Pinterest and Dribbble research (name, one line, arrow).
 *
 * Nothing is written here. The rows are read from the editor content:
 * - a paragraph that opens with a link becomes a row: the link is the row's
 *   name, the rest of the sentence its line (first letter capitalised);
 * - any other paragraph stays as it was written, under its heading;
 * - a closing section with no such paragraph ("Still stuck?") becomes the
 *   site's strip.
 * So the client can add, remove or reword entries in the editor as before.
 *
 * Must be called inside the loop.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_rh        = ccs_content_sections( apply_filters( 'the_content', get_the_content() ) );
$ccs_rh_groups = array();
$ccs_rh_strip_box = null;
$ccs_rh_count  = count( $ccs_rh['sections'] );

foreach ( $ccs_rh['sections'] as $ccs_rh_i => $ccs_rh_section ) {
	$ccs_rh_rows  = array();
	$ccs_rh_prose = '';
	if ( preg_match_all( '#<p[^>]*>(.*?)</p>#is', $ccs_rh_section['body'], $ccs_rh_ps ) ) {
		foreach ( $ccs_rh_ps[1] as $ccs_rh_p ) {
			if ( preg_match( '#^\s*<a\s[^>]*href="([^"]+)"[^>]*>(.*?)</a>\s*(.*)$#is', $ccs_rh_p, $ccs_rh_m ) ) {
				$ccs_rh_line   = trim( wp_strip_all_tags( $ccs_rh_m[3] ) );
				$ccs_rh_rows[] = array(
					'url'   => $ccs_rh_m[1],
					'title' => trim( wp_strip_all_tags( $ccs_rh_m[2] ) ),
					'line'  => function_exists( 'mb_strtoupper' ) ? mb_strtoupper( mb_substr( $ccs_rh_line, 0, 1 ) ) . mb_substr( $ccs_rh_line, 1 ) : ucfirst( $ccs_rh_line ),
				);
			} else {
				$ccs_rh_prose .= '<p>' . $ccs_rh_p . '</p>';
			}
		}
	} else {
		$ccs_rh_prose = $ccs_rh_section['body'];
	}

	if ( ! $ccs_rh_rows && $ccs_rh_i === $ccs_rh_count - 1 && '' !== $ccs_rh_prose ) {
		$ccs_rh_strip_box = array(
			'title' => $ccs_rh_section['heading'],
			'text'  => preg_replace( '#</?p[^>]*>#i', ' ', $ccs_rh_prose ),
		);
		continue;
	}

	$ccs_rh_groups[] = array(
		'heading' => $ccs_rh_section['heading'],
		'prose'   => $ccs_rh_prose,
		'rows'    => $ccs_rh_rows,
	);
}

/*
 * A signpost page (Nov 2026, fourth form). The searchable hub that listed
 * every guide, question and news post here was dropped by the client: "i
 * dont think we should have a resources hub page unless its just different
 * section directing to the different pages with a like brief about what is
 * on that page". So each page linked from the editor content is one short
 * section: a photograph, the page's name, the editor's own line about it, and a
 * link. Grounds alternate. Nothing is written here; the client adds, removes
 * or rewords entries in the editor as before.
 */
$ccs_rh_rows  = array();
$ccs_rh_notes = array();
foreach ( $ccs_rh_groups as $ccs_rh_group ) {
	foreach ( $ccs_rh_group['rows'] as $ccs_rh_row ) {
		$ccs_rh_rows[] = $ccs_rh_row;
	}
	if ( ! $ccs_rh_group['rows'] && '' !== $ccs_rh_group['prose'] ) {
		$ccs_rh_notes[] = $ccs_rh_group;
	}
}
/*
 * A photograph for each page (Nov 2026: the client picked "photo sections"
 * from three sketches, after the Pinterest pattern of one section per page
 * with the photo swapping sides). Real CCS photos already in the theme, all
 * at least 1024px wide for a 520px frame, matched by what the page's
 * address is about. Which photo stands for which page is the client's call:
 * these are a first choice, changed here.
 */
$ccs_rh_photo = static function ( $url, $n ) {
	$map = array(
		'faq|question' => 'site-photos/Untitled-design-4.webp',
		'referral'     => 'site-photos/WhatsApp-Image-2021-02-10-at-15.39.32.webp',
		'guide'        => 'site-photos/Untitled-1.webp',
		'start'        => 'site-photos/467212766_9676172959062903_646005181027754761_n-copy.webp',
		'news|blog'    => 'site-photos/IMG-20241107-WA0006.webp',
	);
	foreach ( $map as $pattern => $photo ) {
		if ( preg_match( '/' . $pattern . '/i', $url ) ) {
			return $photo;
		}
	}
	$spare = array_values( $map );
	return $spare[ $n % count( $spare ) ];
};
$ccs_rh_lower = static function ( $title ) {
	return function_exists( 'mb_strtolower' ) ? mb_strtolower( mb_substr( $title, 0, 1 ) ) . mb_substr( $title, 1 ) : lcfirst( $title );
};
$ccs_rh_last = count( $ccs_rh_rows ) - 1;
?>
<?php foreach ( $ccs_rh_rows as $ccs_rh_n => $ccs_rh_row ) : ?>
	<section class="ccs-section ccs-section--sm signpost<?php echo 0 === $ccs_rh_n ? ' resources-hub' : ''; ?><?php echo $ccs_rh_n % 2 ? ' ccs-section--tint' : ''; ?>">
		<div class="container container--lg">
			<div class="signpost__row<?php echo $ccs_rh_n % 2 ? ' signpost__row--flip' : ''; ?>">
				<?php $ccs_rh_img = $ccs_rh_photo( $ccs_rh_row['url'], $ccs_rh_n ); ?>
				<figure class="signpost__photo" data-ccs-exception="rule 15: one photograph in its frame">
					<?php
					printf(
						'<img src="%1$s" alt="" loading="%2$s" decoding="async"%3$s>',
						esc_url( get_template_directory_uri() . '/assets/images/' . $ccs_rh_img ),
						0 === $ccs_rh_n ? 'eager' : 'lazy',
						ccs_theme_image_srcset( $ccs_rh_img, '(min-width: 768px) 520px, 92vw' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset().
					);
					?>
				</figure>
				<div class="signpost__about">
					<h2 class="signpost__title"><?php echo esc_html( $ccs_rh_row['title'] ); ?></h2>
					<?php if ( '' !== $ccs_rh_row['line'] ) : ?>
						<p class="signpost__text"><?php echo esc_html( $ccs_rh_row['line'] ); ?></p>
					<?php endif; ?>
					<?php // Filled on purpose: each section has one action and they never share a screen side by side (rule 8); the quiet button read as an afterthought beside a photo. ?>
					<a class="btn btn-primary signpost__go" href="<?php echo esc_url( $ccs_rh_row['url'] ); ?>">
						<?php
						/* translators: %s: the page's name, e.g. "care guides". */
						printf( esc_html__( 'Go to %s', 'ccs-wp-theme' ), esc_html( $ccs_rh_lower( $ccs_rh_row['title'] ) ) );
						?>
					</a>
				</div>
			</div>
			<?php if ( $ccs_rh_n === $ccs_rh_last ) : ?>
				<?php foreach ( $ccs_rh_notes as $ccs_rh_group ) : ?>
					<div class="hub__note">
						<h2 class="hub__note-title"><?php echo esc_html( $ccs_rh_group['heading'] ); ?></h2>
						<div class="hub__note-text entry-content"><?php echo wp_kses_post( $ccs_rh_group['prose'] ); ?></div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</section>
<?php endforeach; ?>

<?php if ( $ccs_rh_strip_box ) : ?>
	<?php $ccs_rh_call = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => '', 'phone_link' => '' ); ?>
	<section class="ccs-section ccs-section--sm<?php echo count( $ccs_rh_rows ) % 2 ? ' ccs-section--tint' : ''; ?>">
		<div class="container container--lg">
			<div class="prompt-card">
				<span class="ccs-badge" aria-hidden="true"><?php echo ccs_icon( 'question' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
				<div>
					<h2 class="prompt-card__title"><?php echo esc_html( $ccs_rh_strip_box['title'] ); ?></h2>
					<p class="prompt-card__text"><?php echo wp_kses_post( trim( $ccs_rh_strip_box['text'] ) ); ?></p>
				</div>
				<?php if ( ! empty( $ccs_rh_call['phone'] ) ) : ?>
					<div class="prompt-card__actions">
						<a class="btn btn-primary" href="<?php echo esc_url( $ccs_rh_call['phone_link'] ); ?>">
							<?php
							/* translators: %s: office phone number. */
							printf( esc_html__( 'Call %s', 'ccs-wp-theme' ), esc_html( $ccs_rh_call['phone'] ) );
							?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php endif; ?>
