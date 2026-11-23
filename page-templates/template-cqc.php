<?php
/**
 * Template Name: CQC and Our Care
 *
 * Photo hero, then one feature: the page's own heading and text with the
 * report link, beside a photo carrying the rating on a tab. Then the CTA band.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="main" class="site-main site-main--cqc" role="main">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php get_template_part( 'template-parts/page-header' ); ?>

		<?php
		/*
		 * One photo with the rating on a tab over its corner, and the page's
		 * own heading and text beside it with the report link (Nov 2026). The
		 * user rejected the title-left intro, the centred band and the two-part
		 * verdict panel that came before; this is the photo-and-tab object she
		 * approved on the careers pages. The homepage keeps its own CQC band.
		 *
		 * The rating and date come from Customize > CQC Information, as in
		 * template-parts/home/cqc-section.php. Nothing here is invented.
		 */
		$ccs_cqc_url     = defined( 'CCS_CQC_REPORT_URL' ) ? CCS_CQC_REPORT_URL : get_theme_mod( 'ccs_cqc_url', 'https://www.cqc.org.uk/location/1-2624556588' );
		$ccs_cqc_ratings = array(
			'outstanding'          => __( 'Outstanding', 'ccs-wp-theme' ),
			'good'                 => __( 'Good', 'ccs-wp-theme' ),
			'requires-improvement' => __( 'Requires improvement', 'ccs-wp-theme' ),
			'inadequate'           => __( 'Inadequate', 'ccs-wp-theme' ),
		);
		$ccs_cqc_key     = (string) get_theme_mod( 'ccs_cqc_rating', '' );
		$ccs_cqc_key     = isset( $ccs_cqc_ratings[ $ccs_cqc_key ] ) ? $ccs_cqc_key : 'good';
		$ccs_cqc_date    = (string) get_theme_mod( 'ccs_cqc_rating_date', '6 December 2023' );
		?>
		<section class="ccs-section cqc-page" aria-labelledby="cqc-intro-heading">
			<div class="container container--lg">
				<div class="ccs-media-split ccs-media-split--flip cqc-feature">

					<article id="post-<?php the_ID(); ?>" <?php post_class( 'cqc-feature__text page-content' ); ?>>
						<h2 id="cqc-intro-heading" class="ccs-title"><?php echo ccs_copy_accent( 'cqc_page_title' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_copy_accent(). ?></h2>
						<?php if ( trim( get_the_content() ) !== '' ) : ?>
							<div class="page-body page-body--flush entry-content">
								<?php the_content(); ?>
							</div>
						<?php endif; ?>
						<a class="btn btn-quiet cqc-feature__report" href="<?php echo esc_url( $ccs_cqc_url ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Read the report on cqc.org.uk', 'ccs-wp-theme' ); ?>
							<span aria-hidden="true">&nearr;</span>
							<span class="screen-reader-text"><?php esc_html_e( '(opens in a new window)', 'ccs-wp-theme' ); ?></span>
						</a>
						<p class="cqc-feature__note">
							<?php echo ccs_doodle( 'lightbulb', 'cqc-feature__doodle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle(). ?>
							<span>
							<?php
							// Same sourced sentence as the homepage band; see the note there before editing.
							printf(
								/* translators: %s: linked phrase citing the Homecare Association analysis. */
								esc_html__( 'Only around one in six home care services in England has a current CQC rating: %s.', 'ccs-wp-theme' ),
								sprintf(
									'<a href="%1$s" target="_blank" rel="noopener noreferrer">%2$s<span class="screen-reader-text">%3$s</span></a>',
									esc_url( 'https://www.homecareassociation.org.uk/resource/unseen-and-unrated-more-than-four-in-five-community-social-care-services-in-england-have-no-current-cqc-rating-new-analysis-finds.html' ),
									esc_html__( 'most have either never been assessed or carry one that is years out of date', 'ccs-wp-theme' ),
									esc_html__( ' (source: Homecare Association analysis, June 2026, opens in a new window)', 'ccs-wp-theme' )
								)
							);
							?>
							</span>
						</p>
					</article>

					<div class="careers-benefits__media cqc-feature__media">
						<?php
						ccs_section_photo(
							'site-photos/ccs-complex-care-wheelchair-1.webp',
							array(
								'alt'   => __( 'Two women sitting together in the sun on a patio, smiling', 'ccs-wp-theme' ),
								'sizes' => '(min-width: 900px) 480px, 92vw',
								'class' => 'careers-benefits__photo',
							)
						);
						?>
						<p class="careers-benefits__badge" data-ccs-exception="rule 1: the regulator's rating, set on the photo as a label">
							<strong><?php echo esc_html( $ccs_cqc_ratings[ $ccs_cqc_key ] ); ?></strong>
							<span><?php esc_html_e( 'CQC overall rating', 'ccs-wp-theme' ); ?></span>
							<?php if ( '' !== $ccs_cqc_date ) : ?>
								<small>
									<?php
									/* translators: %s: the date the CQC rating was published. */
									echo esc_html( sprintf( __( 'Published %s', 'ccs-wp-theme' ), $ccs_cqc_date ) );
									?>
								</small>
							<?php endif; ?>
						</p>
					</div>

				</div>
			</div>
		</section>
		<?php
		/*
		 * The rest of the page (Nov 2026). It was the one feature above and
		 * nothing else: the client called it an "awful thin content page" and
		 * sent six CQC pages from other providers (Trinity Homecare, The Good
		 * Care Group and four explainers). What they share, and this page now
		 * has: what the CQC is and its four ratings, the five questions it asks
		 * with the service's rating on each, how to use a report when choosing
		 * care, and what to do if you are worried. Structure only; CCS's look.
		 *
		 * Every fact about the CQC is from cqc.org.uk ("The 5 key questions we
		 * ask" and "Our ratings and scores", read 20 Nov 2026), reworded. The
		 * five ratings below are from this service's own listing
		 * (cqc.org.uk/location/1-2624556588, report published 6 December
		 * 2023). They show only while Customize > CQC Information still holds
		 * that date, so a newer report never appears beside old ratings: when
		 * the date is changed, update $ccs_cqc_five and its date here too.
		 */
		$ccs_cqc_scale = array(
			'outstanding'          => __( 'The service is performing exceptionally well.', 'ccs-wp-theme' ),
			'good'                 => __( 'The service is performing well and meeting the CQC’s expectations.', 'ccs-wp-theme' ),
			'requires-improvement' => __( 'The service is not performing as well as it should, and has been told how it must improve.', 'ccs-wp-theme' ),
			'inadequate'           => __( 'The service is performing badly, and the CQC has taken action against whoever runs it.', 'ccs-wp-theme' ),
		);
		$ccs_cqc_five_date = '6 December 2023';
		$ccs_cqc_five_show = $ccs_cqc_five_date === trim( $ccs_cqc_date );
		$ccs_cqc_five      = array(
			array( 'shield', __( 'Safe', 'ccs-wp-theme' ), __( 'People are protected from abuse and avoidable harm.', 'ccs-wp-theme' ), 'good' ),
			array( 'check', __( 'Effective', 'ccs-wp-theme' ), __( 'Care and support achieve good outcomes and help people keep their quality of life.', 'ccs-wp-theme' ), 'good' ),
			array( 'heart', __( 'Caring', 'ccs-wp-theme' ), __( 'Staff treat people with compassion, kindness, dignity and respect.', 'ccs-wp-theme' ), 'good' ),
			array( 'clock', __( 'Responsive', 'ccs-wp-theme' ), __( 'The service is organised around what each person needs.', 'ccs-wp-theme' ), 'good' ),
			array( 'people', __( 'Well-led', 'ccs-wp-theme' ), __( 'Leadership and management make sure care is high quality, and the culture is open and fair.', 'ccs-wp-theme' ), 'good' ),
		);
		$ccs_cqc_checks = array(
			array( __( 'The date', 'ccs-wp-theme' ), __( 'A rating describes a service at the time it was assessed. Check when the report was published.', 'ccs-wp-theme' ) ),
			array( __( 'All five ratings', 'ccs-wp-theme' ), __( 'The overall rating is a summary. The five beneath it show where a service is strong and where it is not.', 'ccs-wp-theme' ) ),
			array( __( 'What people said', 'ccs-wp-theme' ), __( 'Reports include what people who use the service, and their families, told the inspectors.', 'ccs-wp-theme' ) ),
			array( __( 'Who runs it', 'ccs-wp-theme' ), __( 'The listing names the registered manager, the person accountable for the care.', 'ccs-wp-theme' ) ),
		);
		$ccs_cqc_call = function_exists( 'ccs_get_contact_info' ) ? ccs_get_contact_info() : array( 'phone' => '', 'phone_link' => '' );
		$ccs_cqc_new  = '<span class="screen-reader-text">' . esc_html__( ' (opens in a new window)', 'ccs-wp-theme' ) . '</span>';
		?>

		<section class="ccs-section ccs-section--tint cqc-about" aria-labelledby="cqc-about-heading">
			<div class="container container--lg">
				<div class="ccs-split">
					<header class="ccs-head">
						<h2 id="cqc-about-heading" class="ccs-title">
							<?php
							/* translators: %s: the letters "CQC", shown as the heading's accent. */
							printf( esc_html__( 'What the %s does', 'ccs-wp-theme' ), '<span class="ccs-accent">' . esc_html__( 'CQC', 'ccs-wp-theme' ) . '</span>' );
							?>
						</h2>
						<p class="ccs-lede"><?php esc_html_e( 'The Care Quality Commission (CQC) is the independent regulator of health and social care in England. It registers care services, inspects them and publishes what it finds, so you can compare services before you choose one.', 'ccs-wp-theme' ); ?></p>
						<?php // The line that introduces the scale sits with a clipboard doodle, the inspector's own: it fills the space under the text that was empty beside the taller scale (client, Nov 2026: "this needs restyling"). ?>
						<p class="cqc-about__lead-in">
							<?php echo ccs_doodle( 'clipboard', 'cqc-about__doodle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle(). ?>
							<span><?php esc_html_e( 'Each service it rates is given one of four overall ratings.', 'ccs-wp-theme' ); ?></span>
						</p>
					</header>
					<ol class="cqc-scale" role="list" data-ccs-exception="rule 1: the regulator's four ratings, one scale read together">
						<?php foreach ( $ccs_cqc_scale as $ccs_cqc_k => $ccs_cqc_meaning ) : ?>
							<li class="cqc-scale__row<?php echo $ccs_cqc_k === $ccs_cqc_key ? ' is-ours' : ''; ?>">
								<span class="cqc-scale__bead" aria-hidden="true"></span>
								<div>
									<p class="cqc-scale__name">
										<?php echo esc_html( $ccs_cqc_ratings[ $ccs_cqc_k ] ); ?>
										<?php if ( $ccs_cqc_k === $ccs_cqc_key ) : ?>
											<?php
											// A hand-drawn note (client, Nov 2026: "so much cuter with like a
											// little doodly arrow", then reference sites where a few pen
											// marks over clean type "just makes it look more friendly").
											// The arrow is no. 14 from the "Hand Drawn Arrows" vector pack
											// the client supplied and licensed (120 arrows; this one loops
											// once and points left). Its path is used as drawn, in the brand
											// plum. Under the label, one loose pen underline in mint (client: "instead
											// of the shine, it has a hand-drawn underline"); the packs have no
											// underline, so that one stroke is drawn here. The note is
											// tilted a little, as if jotted on.
											?>
											<span class="cqc-scale__ours">
												<svg class="cqc-scale__arrow" viewBox="0 0 338.31 160.45" width="68" height="32" fill="currentColor" aria-hidden="true" focusable="false">
													<path d="M21.37,58.94c10.26,2.48,20.51,4.96,30.77,7.44,12.08,2.92,17.24-15.71,5.14-18.64-5.57-1.35-11.15-2.7-16.72-4.05,34.8-6.6,71.22-6.51,105.68,1.82,6.47,1.56,13.04,3.6,19.46,6.14-15.35,16.16-24.18,37.63-19.8,61.34,3.35,18.18,15.07,35.37,32.08,43.2,19.61,9.03,42.47,3.7,52.45-16.32,16.69-33.49-1.93-69.63-29.37-90.79,3.44-1.71,6.98-3.15,10.54-4.29,35.15-11.23,82-6.06,109.99,19.43,9.18,8.36,22.89-5.27,13.67-13.67-31.95-29.09-82.48-36.76-123.51-25.94-10.28,2.71-20.26,7.2-29.26,13.1-43.82-20.68-97.32-21.99-144.8-13.12,2.23-2.59,4.46-5.17,6.69-7.76,8.09-9.38-5.53-23.11-13.67-13.67-6.75,7.83-13.51,15.66-20.26,23.49-5.37,6.23-13.57,13.45-9.21,22.45,3.57,7.38,13.1,8.12,20.13,9.82ZM216.69,107.01c1.93,13.85-2.61,35.16-20.27,34.32-14.89-.71-26.21-13.5-30.32-26.98-6.35-20.79,2.75-40.15,17.8-53.65,16.75,10.37,29.88,25.4,32.8,46.31Z"/>
												</svg>
												<span class="cqc-scale__note">
													<?php esc_html_e( 'our rating', 'ccs-wp-theme' ); ?>
													<svg class="cqc-scale__underline" viewBox="0 0 100 12" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true" focusable="false">
														<path vector-effect="non-scaling-stroke" d="M3 7.5C18 3.5 33 9 50 5.5S82 4 97 6.5"/>
													</svg>
												</span>
											</span>
											</span>
											</span>
										<?php endif; ?>
									</p>
									<p class="cqc-scale__meaning"><?php echo esc_html( $ccs_cqc_meaning ); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					</ol>
				</div>
			</div>
		</section>

		<section class="ccs-section cqc-five" aria-labelledby="cqc-five-heading">
			<div class="container container--lg">
				<header class="ccs-head ccs-head--center">
					<h2 id="cqc-five-heading" class="ccs-title"><?php
						/* translators: %s: the words "five questions", shown as the heading's accent. */
						printf( esc_html__( 'The %s inspectors ask', 'ccs-wp-theme' ), '<span class="ccs-accent">' . esc_html__( 'five questions', 'ccs-wp-theme' ) . '</span>' );
						?></h2>
					<p class="ccs-lede">
						<?php
						if ( $ccs_cqc_five_show ) {
							/* translators: %s: the date the CQC report was published. */
							echo esc_html( sprintf( __( 'The CQC asks the same five questions of every care service and rates each one. These are our ratings from the report published %s.', 'ccs-wp-theme' ), $ccs_cqc_five_date ) );
						} else {
							esc_html_e( 'The CQC asks the same five questions of every care service and rates each one.', 'ccs-wp-theme' );
						}
						?>
					</p>
				</header>
				<?php // Five even beads in a row, no boxes: a mark, the question's name, its rating as a handwritten note, and what it means (Nov 2026; the boxed tiles in a 2, 2, 1 grid "need restyling"). ?>
				<ul class="cqc-five__row" role="list" data-ccs-exception="rule 10: five short captions, each centred under its badge in a narrow column">
					<?php foreach ( $ccs_cqc_five as $ccs_cqc_q ) : ?>
						<li class="cqc-five__item">
							<span class="ccs-badge cqc-five__mark" aria-hidden="true"><?php echo ccs_icon( $ccs_cqc_q[0] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
							<div class="cqc-five__words">
								<h3 class="cqc-five__name"><?php echo esc_html( $ccs_cqc_q[1] ); ?></h3>
								<?php if ( $ccs_cqc_five_show ) : ?>
									<p class="cqc-five__rating"><span class="screen-reader-text"><?php esc_html_e( 'Rated', 'ccs-wp-theme' ); ?> </span><span class="cqc-five__word"><?php echo esc_html( $ccs_cqc_ratings[ $ccs_cqc_q[3] ] ); ?><svg class="cqc-scale__underline" viewBox="0 0 100 12" preserveAspectRatio="none" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" aria-hidden="true" focusable="false"><path vector-effect="non-scaling-stroke" d="M3 7.5C18 3.5 33 9 50 5.5S82 4 97 6.5"/></svg></span></p>
								<?php endif; ?>
								<p class="cqc-five__text"><?php echo esc_html( $ccs_cqc_q[2] ); ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
				<p class="cqc-five__more">
					<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_cqc_url ); ?>" target="_blank" rel="noopener noreferrer">
						<?php esc_html_e( 'See our listing on cqc.org.uk', 'ccs-wp-theme' ); ?>
						<span aria-hidden="true">&nearr;</span><?php echo $ccs_cqc_new; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above. ?>
					</a>
				</p>
			</div>
		</section>

		<section class="ccs-section ccs-section--tint cqc-use" aria-labelledby="cqc-use-heading">
			<div class="container container--lg">
				<div class="ccs-split">
					<header class="ccs-head">
						<h2 id="cqc-use-heading" class="ccs-title">
							<?php
							/* translators: %s: the word "choose", shown as the heading's accent. */
							printf( esc_html__( 'Using a report to %s care', 'ccs-wp-theme' ), '<span class="ccs-accent">' . esc_html__( 'choose', 'ccs-wp-theme' ) . '</span>' );
							?>
						</h2>
						<p class="ccs-lede"><?php esc_html_e( 'A rating is a good place to start, for us or for any provider you are weighing up.', 'ccs-wp-theme' ); ?></p>
						<p class="cqc-about__lead-in">
							<?php echo ccs_doodle( 'checklist', 'cqc-about__doodle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle(). ?>
							<span><?php esc_html_e( 'Four things are worth looking at in every report.', 'ccs-wp-theme' ); ?></span>
						</p>
					</header>
					<ol class="cqc-checks" role="list" data-ccs-exception="rule 1: four checks read as one numbered list, in the same card as the ratings scale above">
						<?php foreach ( $ccs_cqc_checks as $ccs_cqc_n => $ccs_cqc_check ) : ?>
							<li class="cqc-checks__item">
								<span class="cqc-checks__mark" aria-hidden="true"><?php echo (int) $ccs_cqc_n + 1; ?></span>
								<div>
									<h3 class="cqc-checks__title"><?php echo esc_html( $ccs_cqc_check[0] ); ?></h3>
									<p class="cqc-checks__text"><?php echo esc_html( $ccs_cqc_check[1] ); ?></p>
								</div>
							</li>
						<?php endforeach; ?>
					</ol>
				</div>
			</div>
		</section>

		<section class="ccs-section ccs-section--sm cqc-worried">
			<div class="container container--lg">
				<div class="prompt-card">
					<span class="ccs-badge" aria-hidden="true"><?php echo ccs_icon( 'question' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
					<div>
						<h2 class="prompt-card__title"><?php esc_html_e( 'Worried about someone’s care?', 'ccs-wp-theme' ); ?></h2>
						<p class="prompt-card__text">
							<?php
							printf(
								/* translators: %s: link to the CQC's feedback page. */
								esc_html__( 'Tell us first, so we can put it right. You can also %s about any care service, including ours.', 'ccs-wp-theme' ),
								'<a href="' . esc_url( 'https://www.cqc.org.uk/give-feedback-on-care' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'tell the CQC directly', 'ccs-wp-theme' ) . $ccs_cqc_new . '</a>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- parts escaped.
							);
							?>
						</p>
					</div>
					<?php if ( ! empty( $ccs_cqc_call['phone'] ) ) : ?>
						<div class="prompt-card__actions">
							<a class="btn btn-primary" href="<?php echo esc_url( $ccs_cqc_call['phone_link'] ); ?>">
								<?php
								/* translators: %s: office phone number. */
								printf( esc_html__( 'Call %s', 'ccs-wp-theme' ), esc_html( $ccs_cqc_call['phone'] ) );
								?>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	endwhile;
	?>

	<?php get_template_part( 'template-parts/cta-band' ); ?>
</main>

<?php
get_footer();
