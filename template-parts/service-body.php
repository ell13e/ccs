<?php
/**
 * The body of a service page (Nov 2026, seventh form: redesigned to match
 * the homepage at the client's request).
 *
 * Six earlier bodies rearranged the same kit (tick lists, lilac panels, the
 * boxed accordion, a rotating card, a side form) and were all rejected. This
 * one is built only from objects the homepage already uses, so a service
 * page reads as part of the same site:
 *
 *   1. What this care is: the service's photo with its starting price on a
 *      tab, beside the heading and opening text (the homepage's "Why choose
 *      us" composition). A service with no photo yet gets the heading and
 *      text centred.
 *   2. Each part of the page (what it includes, who it helps, how the
 *      options differ) as one panel: numbered cells with hairlines between
 *      when each point has a lead-in, ticked points when they are plain
 *      lines. The homepage's "How care starts" panel.
 *   3. How care starts: the same three steps, in the same panel.
 *   4. What it costs beside the call-back form, as one card in two halves.
 *   5. The page's remaining text as the numbered accordion.
 *
 * Where the client's copy pack has the service (inc/service-copy.php) the
 * words are the pack's; otherwise they are the page's own editor content and
 * the plain-English guide (inc/service-guides.php). Nothing is written here.
 *
 * Args: post_id, intro (HTML), lists, prose (sections), features (rows),
 * form (the call-back <form>), phone, phone_tel, hours.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_sb_id    = isset( $args['post_id'] ) ? (int) $args['post_id'] : get_the_ID();
$ccs_sb_slug  = get_post_field( 'post_name', $ccs_sb_id );
$ccs_sb_intro = isset( $args['intro'] ) ? (string) $args['intro'] : '';
$ccs_sb_prose = isset( $args['prose'] ) && is_array( $args['prose'] ) ? $args['prose'] : array();
$ccs_sb_form  = isset( $args['form'] ) ? (string) $args['form'] : '';
$ccs_sb_guide = function_exists( 'ccs_service_guide' ) ? ccs_service_guide( $ccs_sb_slug ) : array();
$ccs_sb_copy  = function_exists( 'ccs_service_copy' ) ? ccs_service_copy( $ccs_sb_slug ) : array();
$ccs_sb_name  = mb_strtolower( get_the_title( $ccs_sb_id ) );
$ccs_sb_case  = static function ( $text ) {
	$text = function_exists( 'ccs_sentence_case' ) ? ccs_sentence_case( $text ) : $text;
	// "Person-Centred" is one word in sentence case: lower the part after the hyphen.
	return preg_replace_callback(
		'/(?<=\p{Ll})-(\p{Lu})(?=\p{Ll})/u',
		static function ( $m ) {
			return '-' . mb_strtolower( $m[1] );
		},
		$text
	);
};

/*
 * One shape for both sources. $ccs_sb_about: title, paragraphs (escaped
 * HTML). $ccs_sb_parts: each a heading, paragraphs, and items as
 * [lead-in, line] pairs (an empty lead-in means a plain line).
 */
$ccs_sb_about = array( 'title' => get_the_title( $ccs_sb_id ), 'html' => '' );
$ccs_sb_parts = array();
$ccs_sb_more  = array();

if ( $ccs_sb_copy ) {
	$ccs_sb_about['title'] = $ccs_sb_case( $ccs_sb_copy['title'] );
	foreach ( $ccs_sb_copy['intro'] as $ccs_sb_para ) {
		$ccs_sb_about['html'] .= '<p>' . esc_html( $ccs_sb_para ) . '</p>';
	}
	foreach ( $ccs_sb_copy['sections'] as $ccs_sb_sec ) {
		// A section that is only a paragraph joins the opening text as a further
		// paragraph, so every page opens the same way: one heading, then text.
		if ( ! array_filter( $ccs_sb_sec['items'] ) ) {
			foreach ( $ccs_sb_sec['paras'] as $ccs_sb_para ) {
				$ccs_sb_about['html'] .= '<p>' . esc_html( $ccs_sb_para ) . '</p>';
			}
			continue;
		}
		$ccs_sb_parts[] = array(
			'heading' => $ccs_sb_case( $ccs_sb_sec['heading'] ),
			'paras'   => $ccs_sb_sec['paras'],
			'items'   => $ccs_sb_sec['items'],
		);
	}
} else {
	if ( ! empty( $ccs_sb_guide['what'] ) ) {
		$ccs_sb_about['title'] = $ccs_sb_guide['what'][0];
		$ccs_sb_about['html'] .= '<p>' . esc_html( $ccs_sb_guide['what'][1] ) . '</p>';
	}
	$ccs_sb_about['html'] .= wp_kses_post( $ccs_sb_intro );

	// The page's own lists, then the service's feature rows.
	$ccs_sb_lists = array();
	foreach ( isset( $args['lists'] ) && is_array( $args['lists'] ) ? $args['lists'] : array() as $ccs_sb_sec ) {
		if ( preg_match_all( '#<li[^>]*>(.*?)</li>#s', $ccs_sb_sec['body'], $ccs_sb_m ) ) {
			$ccs_sb_lists[] = array( $ccs_sb_sec['heading'], array_map( 'wp_strip_all_tags', $ccs_sb_m[1] ) );
		}
	}
	$ccs_sb_feature_items = array();
	foreach ( isset( $args['features'] ) && is_array( $args['features'] ) ? $args['features'] : array() as $ccs_sb_row ) {
		if ( ! empty( $ccs_sb_row['feature_text'] ) ) {
			$ccs_sb_feature_items[] = $ccs_sb_row['feature_text'];
		}
	}
	if ( $ccs_sb_feature_items ) {
		$ccs_sb_lists[] = array( ccs_copy( 'service_included_title' ), $ccs_sb_feature_items );
	}
	$ccs_sb_plain = static function ( $lines ) {
		$out = array();
		foreach ( $lines as $line ) {
			$out[] = array( '', trim( $line ) );
		}
		return $out;
	};
	if ( $ccs_sb_lists ) {
		$ccs_sb_parts[] = array( 'heading' => ucfirst( strtolower( $ccs_sb_lists[0][0] ) ), 'paras' => array(), 'items' => $ccs_sb_plain( $ccs_sb_lists[0][1] ) );
	}
	if ( ! empty( $ccs_sb_guide['who'] ) ) {
		$ccs_sb_parts[] = array( 'heading' => __( 'Who it helps', 'ccs-wp-theme' ), 'paras' => array(), 'items' => $ccs_sb_plain( $ccs_sb_guide['who'] ) );
	}
	if ( ! empty( $ccs_sb_guide['compare'] ) ) {
		$ccs_sb_parts[] = array( 'heading' => $ccs_sb_guide['compare'][0], 'paras' => array(), 'items' => $ccs_sb_guide['compare'][1] );
	}
	// The rest of the page's own content goes in the accordion.
	foreach ( array_slice( $ccs_sb_lists, 1 ) as $ccs_sb_list ) {
		$ccs_sb_body = '<ul>';
		foreach ( $ccs_sb_list[1] as $ccs_sb_item ) {
			$ccs_sb_body .= '<li>' . esc_html( trim( $ccs_sb_item ) ) . '</li>';
		}
		$ccs_sb_more[] = array( 'heading' => ucfirst( strtolower( $ccs_sb_list[0] ) ), 'body' => $ccs_sb_body . '</ul>' );
	}
	foreach ( $ccs_sb_prose as $ccs_sb_sec ) {
		$ccs_sb_more[] = array( 'heading' => $ccs_sb_sec['heading'], 'body' => $ccs_sb_sec['body'] );
	}
}

// This service's prices.
$ccs_sb_all    = function_exists( 'ccs_fee_schedule' ) ? ccs_fee_schedule() : array();
$ccs_sb_want   = function_exists( 'ccs_fee_groups_for_service' ) ? ccs_fee_groups_for_service( $ccs_sb_slug ) : array();
$ccs_sb_groups = $ccs_sb_want ? array_intersect_key( $ccs_sb_all, array_flip( $ccs_sb_want ) ) : array();
$ccs_sb_lines  = array();
/*
 * The same card on every service page (client: the pages were inconsistent):
 * two lines and a link. A service with its own rate card shows its weekday
 * and weekend daytime rates; the night rates are one tap away on the Costs
 * page. A service without one shows the two published hourly starting rates
 * (further down).
 */
foreach ( $ccs_sb_groups as $ccs_sb_rows ) {
	if ( $ccs_sb_rows[0]['night'] ) {
		continue;
	}
	$ccs_sb_lines[] = array( __( 'Weekdays, daytime', 'ccs-wp-theme' ), $ccs_sb_rows[0]['weekday'], __( 'an hour', 'ccs-wp-theme' ) );
	if ( '' !== $ccs_sb_rows[0]['weekend'] ) {
		$ccs_sb_lines[] = array( __( 'Weekends, daytime', 'ccs-wp-theme' ), $ccs_sb_rows[0]['weekend'], __( 'an hour', 'ccs-wp-theme' ) );
	}
	break;
}
$ccs_sb_first = $ccs_sb_groups ? reset( $ccs_sb_groups )[0] : null;
$ccs_sb_costs = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'costs-and-funding' ) : home_url( '/costs-and-funding/' );
$ccs_sb_photo = has_post_thumbnail( $ccs_sb_id );

/*
 * No photograph of its own yet (most services): the opening sits beside one
 * of a small set of photos of the team in training. Staff, not clients, so
 * nothing is implied about anyone's health on a page about a condition; the
 * client can replace it by giving the service a featured image.
 */
$ccs_sb_stand_ins = array(
	array( 'careers/hoist-practice.webp', __( 'Two members of the team practising with a hoist in the training room', 'ccs-wp-theme' ) ),
	array( 'careers/training-room.webp', __( 'Carers around a table during a training session', 'ccs-wp-theme' ) ),
	array( 'careers/trainer-with-learner.webp', __( 'A trainer talking something through with a carer', 'ccs-wp-theme' ) ),
	array( 'journey/cta-training-room.webp', __( 'Members of the team in the training room', 'ccs-wp-theme' ) ),
	array( 'careers/sling-practice.webp', __( 'A carer trying a sling during moving and handling training', 'ccs-wp-theme' ) ),
);
$ccs_sb_stand_ins = array_values(
	array_filter(
		$ccs_sb_stand_ins,
		static function ( $photo ) {
			return file_exists( get_template_directory() . '/assets/images/' . $photo[0] );
		}
	)
);
$ccs_sb_stand_in = ( ! $ccs_sb_photo && $ccs_sb_stand_ins ) ? $ccs_sb_stand_ins[ abs( crc32( (string) $ccs_sb_slug ) ) % count( $ccs_sb_stand_ins ) ] : null;

// An icon for each "can help with" tile, chosen by what its words are about.
$ccs_sb_tile_icon = static function ( $text ) {
	$map = array(
		'medic|clinical|nurs|health|rehab|therap'            => 'pulse',
		'night|sleep|overnight'                             => 'moon',
		'safe|fall|risk|wander|secur|prevent'                => 'shield',
		'meal|food|nutri|drink|cook|eat'                     => 'cup',
		'regulat|cqc|qualif|train|certif'                    => 'badge',
		'routine|flexib|schedul|time|hour|visit'             => 'clock',
		'home|house|familiar|surround|domestic'              => 'home',
		'famil|team|faces|carer|companion|social|together' => 'people',
	);
	foreach ( $map as $pattern => $icon ) {
		if ( preg_match( '/' . $pattern . '/i', $text ) ) {
			return $icon;
		}
	}
	return 'heart';
};

/*
 * A service with no rate card of its own shows the two published hourly
 * starting rates, named as the Costs page names them, so the card is never
 * a heading over an empty half. Nothing is claimed about which applies: the
 * note above the figures already says the quote is personal.
 */
$ccs_sb_general = false;
if ( ! $ccs_sb_lines ) {
	foreach ( $ccs_sb_all as $ccs_sb_group => $ccs_sb_rows ) {
		if ( ! $ccs_sb_rows[0]['night'] ) {
			$ccs_sb_lines[] = array( $ccs_sb_group, $ccs_sb_rows[0]['weekday'], __( 'an hour', 'ccs-wp-theme' ) );
		}
	}
	$ccs_sb_general = (bool) $ccs_sb_lines;
}
?>

<?php
/*
 * 1. What this care is: the heading and opening text beside one rounded
 * photograph (after visiting-angels.co.uk's service pages, which the client
 * pointed to: text and photo side by side, not a centred paragraph).
 */
?>
<section class="ccs-section svc-about" aria-labelledby="svc-about-heading">
	<div class="container container--lg">
		<div class="svc-intro<?php echo ( $ccs_sb_photo || $ccs_sb_stand_in ) ? '' : ' svc-intro--text'; ?>">
			<div class="svc-intro__text svc-about__text">
				<h2 id="svc-about-heading" class="ccs-title"><?php echo esc_html( $ccs_sb_about['title'] ); ?></h2>
				<?php echo $ccs_sb_about['html']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped or kses'd above. ?>
			</div>
			<?php if ( $ccs_sb_photo ) : ?>
				<?php
				/*
				 * One frame for every service page, whichever photo is in it (the
				 * pages used to differ: a small photo in its own shape with a price
				 * tab on three of them, a larger stand-in on the rest).
				 */
				?>
				<figure class="svc-intro__photo" data-ccs-exception="rule 15: one photograph in its frame">
					<?php
					echo wp_get_attachment_image(
						get_post_thumbnail_id( $ccs_sb_id ),
						'large',
						false,
						array(
							'sizes'   => '(min-width: 960px) 480px, 92vw',
							'loading' => 'eager',
						)
					);
					?>
				</figure>
			<?php elseif ( $ccs_sb_stand_in ) : ?>
				<figure class="svc-intro__photo" data-ccs-exception="rule 15: one photograph in its frame">
					<?php
					printf(
						'<img src="%1$s" alt="%2$s" loading="eager" decoding="async"%3$s>',
						esc_url( get_template_directory_uri() . '/assets/images/' . $ccs_sb_stand_in[0] ),
						esc_attr( $ccs_sb_stand_in[1] ),
						ccs_theme_image_srcset( $ccs_sb_stand_in[0], '(min-width: 960px) 480px, 92vw' ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_theme_image_srcset().
					);
					?>
				</figure>
			<?php endif; ?>
		</div>
	</div>
</section>

<?php
/*
 * 2. What it includes: every part of the page in one band, each a centred
 * heading over a two-column grid of soft tiles, an icon beside a bold title
 * and one line (Visiting Angels' "Our carers can help with"), then the one
 * action. Grounds alternate from here on through $ccs_sb_tint.
 */
$ccs_sb_tint    = true;
$ccs_sb_contact = function_exists( 'ccs_page_url' ) ? ccs_page_url( 'contact-us' ) : home_url( '/contact-us/' );
?>
<?php if ( $ccs_sb_parts ) : ?>
	<section class="ccs-section ccs-section--tint svc-help" aria-labelledby="svc-part-0">
		<div class="container container--lg">
			<?php foreach ( $ccs_sb_parts as $ccs_sb_n => $ccs_sb_part ) : ?>
				<?php $ccs_sb_items = array_values( array_filter( $ccs_sb_part['items'] ) ); ?>
				<div class="svc-help__part">
					<header class="ccs-head ccs-head--center">
						<h2 id="svc-part-<?php echo (int) $ccs_sb_n; ?>" class="ccs-title"><?php echo esc_html( $ccs_sb_part['heading'] ); ?></h2>
						<?php foreach ( $ccs_sb_part['paras'] as $ccs_sb_para ) : ?>
							<p class="ccs-lede"><?php echo esc_html( $ccs_sb_para ); ?></p>
						<?php endforeach; ?>
					</header>
					<?php if ( $ccs_sb_items ) : ?>
						<ul class="help-tiles" role="list">
							<?php foreach ( $ccs_sb_items as $ccs_sb_item ) : ?>
								<li class="help-tile" data-ccs-exception="rule 1: one thing this care includes, a tile in a grid read together">
									<span class="ccs-badge help-tile__icon" aria-hidden="true"><?php echo ccs_icon( $ccs_sb_tile_icon( $ccs_sb_item[0] . ' ' . $ccs_sb_item[1] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
									<div>
										<?php // A plain line is set as the tile's title, so every tile reads the same way. ?>
										<?php if ( '' !== $ccs_sb_item[0] ) : ?>
											<h3 class="help-tile__title"><?php echo esc_html( $ccs_sb_case( $ccs_sb_item[0] ) ); ?></h3>
											<p class="help-tile__text"><?php echo esc_html( $ccs_sb_item[1] ); ?></p>
										<?php else : ?>
											<h3 class="help-tile__title"><?php echo esc_html( rtrim( $ccs_sb_item[1], '.' ) ); ?></h3>
										<?php endif; ?>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
			<div class="ccs-section-foot ccs-actions ccs-actions--center">
				<a class="btn btn-primary" href="<?php echo esc_url( $ccs_sb_contact ); ?>"><?php esc_html_e( 'Book a free consultation', 'ccs-wp-theme' ); ?></a>
			</div>
		</div>
	</section>
	<?php $ccs_sb_tint = false; ?>
<?php endif; ?>

<?php // The rest of the page's own content, where a page has any. ?>
<?php if ( $ccs_sb_more ) : ?>
	<section class="ccs-section<?php echo $ccs_sb_tint ? ' ccs-section--tint' : ''; ?> svc-more" aria-labelledby="svc-more-heading">
		<div class="container container--lg">
			<header class="ccs-head ccs-head--center">
				<h2 id="svc-more-heading" class="ccs-title"><?php esc_html_e( 'Good to know', 'ccs-wp-theme' ); ?></h2>
			</header>
			<?php get_template_part( 'template-parts/num-acc', null, array( 'items' => $ccs_sb_more, 'name' => 'service-more', 'class' => 'num-acc--center' ) ); ?>
		</div>
	</section>
	<?php $ccs_sb_tint = ! $ccs_sb_tint; ?>
<?php endif; ?>

<?php
/*
 * 3. How care starts: three steps in a row, a numbered round mark over each
 * and an arrow between them (the references' "How it works"), not a boxed
 * panel.
 */
?>
<section class="ccs-section<?php echo $ccs_sb_tint ? ' ccs-section--tint' : ''; ?> svc-steps" aria-labelledby="svc-steps-heading">
	<div class="container container--lg">
		<header class="ccs-head ccs-head--center has-doodle">
			<?php echo ccs_doodle( 'armchair', 'doodle--by-centre' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in ccs_doodle(). ?>
			<h2 id="svc-steps-heading" class="ccs-title"><?php echo esc_html( ccs_copy( 'steps_title' ) ); ?></h2>
			<p class="ccs-lede"><?php echo esc_html( ccs_copy( 'steps_lede' ) ); ?></p>
		</header>
		<ol class="flow-steps" role="list">
			<?php foreach ( array( 1, 2, 3 ) as $ccs_sb_n ) : ?>
				<li class="flow-step">
					<span class="flow-step__num" aria-hidden="true"><?php echo (int) $ccs_sb_n; ?></span>
					<h3 class="flow-step__title"><?php echo esc_html( ccs_copy( 'steps_' . $ccs_sb_n . '_title' ) ); ?></h3>
					<p class="flow-step__text"><?php echo esc_html( ccs_copy( 'steps_' . $ccs_sb_n . '_text' ) ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
		<div class="ccs-section-foot ccs-actions ccs-actions--center">
			<a class="ccs-text-link" href="<?php echo esc_url( function_exists( 'ccs_page_url' ) ? ccs_page_url( 'getting-started' ) : home_url( '/getting-started/' ) ); ?>">
				<?php echo esc_html( ccs_copy( 'steps_button' ) ); ?> <span aria-hidden="true">&rarr;</span>
			</a>
		</div>
	</div>
</section>
<?php $ccs_sb_tint = ! $ccs_sb_tint; ?>

<?php
/*
 * 4. What it costs beside the call-back form: one card in two halves, the
 * prices on white and the form on the deep plum (this page's one dark
 * object besides the closing card). It replaced a loose heading and a
 * sentence beside a small white form card, which read as thin.
 */
?>
<section class="ccs-section<?php echo $ccs_sb_tint ? ' ccs-section--tint' : ''; ?> svc-costs-section" id="costs">
	<div class="container container--lg">
		<div class="cost-call" data-ccs-exception="rule 1: the prices and the way to ask about them, one object">
			<div class="cost-call__costs">
				<h2 class="cost-call__title">
					<?php
					// The general rates are not this service's own, so the heading does not say they are.
					/* translators: %s: service name. */
					echo esc_html( $ccs_sb_general ? __( 'What care costs', 'ccs-wp-theme' ) : sprintf( __( 'What %s costs', 'ccs-wp-theme' ), $ccs_sb_name ) );
					?>
				</h2>
				<p class="cost-call__note"><?php echo esc_html( ccs_copy( 'costs_quote_note' ) ); ?></p>
				<?php if ( $ccs_sb_lines ) : ?>
					<ul class="cost-call__lines" role="list">
						<?php foreach ( $ccs_sb_lines as $ccs_sb_line ) : ?>
							<li>
								<span><?php echo esc_html( $ccs_sb_line[0] ); ?></span>
								<?php // "from" before every figure (client, Nov 2026: "make sure it says from"). ?>
								<span class="cost-call__amount"><?php esc_html_e( 'from', 'ccs-wp-theme' ); ?> <strong><?php echo esc_html( $ccs_sb_line[1] ); ?></strong> <?php echo esc_html( $ccs_sb_line[2] ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
				<a class="btn btn-quiet" href="<?php echo esc_url( $ccs_sb_costs ); ?>">
					<?php echo esc_html( $ccs_sb_lines ? __( 'See every rate and extra charge', 'ccs-wp-theme' ) : __( 'See our published rates', 'ccs-wp-theme' ) ); ?>
					<span aria-hidden="true">&rarr;</span>
				</a>
			</div>
			<div class="cost-call__form ccs-on-dark" id="call-back">
				<h2 class="cost-call__title"><?php echo esc_html( ccs_copy( 'service_callback_title' ) ); ?></h2>
				<?php if ( ! empty( $args['phone_tel'] ) ) : ?>
					<a class="cost-call__phone" href="<?php echo esc_url( 'tel:' . $args['phone_tel'] ); ?>">
						<?php echo ccs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
						<span><span class="screen-reader-text"><?php esc_html_e( 'Call', 'ccs-wp-theme' ); ?> </span><?php echo esc_html( $args['phone'] ); ?></span>
					</a>
				<?php endif; ?>
				<?php if ( ! empty( $args['hours'] ) ) : ?>
					<p class="cost-call__hours"><?php echo esc_html( $args['hours'] ); ?></p>
				<?php endif; ?>
				<?php echo $ccs_sb_form; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup built in single-service.php. ?>
			</div>
		</div>
	</div>
</section>
