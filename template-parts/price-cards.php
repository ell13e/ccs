<?php
/**
 * Price cards and the extra charges, from the published fee schedule
 * (Nov 2026; the layout the client approved in a mockup, after
 * helpinghandshomecare.co.uk's cost page).
 *
 * Three even cards, each with exactly three lines, so they always line up
 * (the client found cards with two, two and four rows "uneven, messy"):
 *   - an hourly group: weekdays daytime, weekends daytime, overnight from;
 *   - the nights group: sleep-in night, waking night, complex nights from.
 * Then the extra charges as four short facts in one tint strip.
 *
 * Used by the homepage costs section and the Costs and funding page.
 * Figures: ccs_fee_schedule() and ccs_fee_extras() (inc/content-sections.php).
 *
 * Args: 'links' (array group => [url, text]) for the link under each card.
 *
 * @package CCS_WP_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ccs_pc_groups = function_exists( 'ccs_fee_schedule' ) ? ccs_fee_schedule() : array();
if ( empty( $ccs_pc_groups ) ) {
	return;
}

$ccs_pc_links = isset( $args['links'] ) && is_array( $args['links'] ) ? $args['links'] : array();
$ccs_pc_icons = array( 'home', 'pulse', 'moon' );
$ccs_pc_money = static function ( $v ) {
	return (float) preg_replace( '/[^0-9.]/', '', (string) $v );
};

$ccs_pc_cards = array();
foreach ( $ccs_pc_groups as $ccs_group => $ccs_rows ) {
	$ccs_first = $ccs_rows[0];
	$ccs_lines = array();

	if ( ! $ccs_first['night'] ) {
		$ccs_lines[] = array( __( 'Weekdays, daytime', 'ccs-wp-theme' ), $ccs_first['weekday'] );
		if ( '' !== $ccs_first['weekend'] ) {
			$ccs_lines[] = array( __( 'Weekends, daytime', 'ccs-wp-theme' ), $ccs_first['weekend'] );
		}
		if ( isset( $ccs_rows[1] ) ) {
			$ccs_lines[] = array( __( 'Overnight, from', 'ccs-wp-theme' ), $ccs_rows[1]['weekday'] );
		}
	} else {
		$ccs_complex = array();
		foreach ( $ccs_rows as $ccs_row ) {
			if ( preg_match( '/complex/i', $ccs_row['label'] ) ) {
				$ccs_complex[] = $ccs_pc_money( $ccs_row['weekday'] );
			} else {
				$ccs_lines[] = array( $ccs_row['label'], $ccs_row['weekday'] );
			}
		}
		if ( $ccs_complex ) {
			$ccs_lines[] = array( __( 'Complex nights, from', 'ccs-wp-theme' ), '£' . number_format( min( $ccs_complex ), 2 ) );
		}
	}

	$ccs_pc_cards[] = array(
		'name'  => $ccs_group,
		'price' => $ccs_first['weekday'],
		'unit'  => $ccs_first['night'] ? __( 'a night', 'ccs-wp-theme' ) : __( 'an hour', 'ccs-wp-theme' ),
		'lines' => array_slice( $ccs_lines, 0, 3 ),
		'icon'  => $ccs_pc_icons[ count( $ccs_pc_cards ) % 3 ],
		'link'  => isset( $ccs_pc_links[ $ccs_group ] ) ? $ccs_pc_links[ $ccs_group ] : null,
	);
}

/*
 * The extra charges as facts: a short figure ("£30", "×2", "50p") above what
 * it is. "Double rate" and "Treble rate" become ×2 and ×3; a price that
 * starts with an amount is split into the amount and the rest.
 */
$ccs_pc_facts = array();
foreach ( function_exists( 'ccs_fee_extras' ) ? ccs_fee_extras() : array() as $ccs_extra ) {
	$ccs_price = trim( $ccs_extra['price'] );
	$ccs_value = '';
	$ccs_rest  = '';
	if ( preg_match( '/^double/i', $ccs_price ) ) {
		$ccs_value = '×2';
	} elseif ( preg_match( '/^treble/i', $ccs_price ) ) {
		$ccs_value = '×3';
	} elseif ( preg_match( '/^(£\d[\d.]*|\d+p)\s*,?\s*(.*)$/i', $ccs_price, $ccs_m ) ) {
		$ccs_value = $ccs_m[1];
		$ccs_rest  = $ccs_m[2];
	}
	$ccs_text = $ccs_extra['label'];
	if ( '' !== $ccs_rest ) {
		$ccs_text .= ', ' . $ccs_rest;
	}
	$ccs_pc_facts[] = array(
		'value' => '' !== $ccs_value ? $ccs_value : $ccs_price,
		'text'  => $ccs_text,
	);
}
?>

<?php
/*
 * 'layout' => 'photo' (Costs page, Nov 2026). Two photo-topped cards, one
 * for each kind of hourly care, after the cost page the user pointed to
 * (helpinghandshomecare.co.uk: a photo, the name, a few ticked prices, a
 * link). The starting price sits on a plum tab over the photo's corner, the
 * object she approved on the careers pages. The night rates are shared out:
 * the complex ones to the complex card, the rest to the other. It replaced
 * three white cards and then a three-row price list, both rejected.
 * 'photos' (array group => [file, alt, object-position]) supplies the photos.
 */
if ( isset( $args['layout'] ) && 'photo' === $args['layout'] ) :
	$ccs_pp_photos = isset( $args['photos'] ) && is_array( $args['photos'] ) ? $args['photos'] : array();
	$ccs_pp_cards  = array();
	$ccs_pp_nights = array();
	foreach ( $ccs_pc_groups as $ccs_group => $ccs_rows ) {
		if ( $ccs_rows[0]['night'] ) {
			$ccs_pp_nights = array_merge( $ccs_pp_nights, $ccs_rows );
			continue;
		}
		$ccs_lines = array( array( __( 'Weekdays, daytime', 'ccs-wp-theme' ), $ccs_rows[0]['weekday'], __( 'an hour', 'ccs-wp-theme' ) ) );
		if ( '' !== $ccs_rows[0]['weekend'] ) {
			$ccs_lines[] = array( __( 'Weekends, daytime', 'ccs-wp-theme' ), $ccs_rows[0]['weekend'], __( 'an hour', 'ccs-wp-theme' ) );
		}
		$ccs_pp_cards[ $ccs_group ] = array(
			'price' => $ccs_rows[0]['weekday'],
			'lines' => $ccs_lines,
		);
	}
	foreach ( $ccs_pp_nights as $ccs_row ) {
		$ccs_is_complex = (bool) preg_match( '/complex/i', $ccs_row['label'] );
		foreach ( $ccs_pp_cards as $ccs_group => $ccs_unused ) {
			if ( $ccs_is_complex === (bool) preg_match( '/complex/i', $ccs_group ) ) {
				// Every figure carries "from" (below), so a night that costs more at the weekend needs no note of its own.
				$ccs_label = ucfirst( trim( preg_replace( '/complex\s*/i', '', $ccs_row['label'] ) ) );
				$ccs_pp_cards[ $ccs_group ]['lines'][] = array( $ccs_label, $ccs_row['weekday'], __( 'a night', 'ccs-wp-theme' ) );
				break;
			}
		}
	}
	?>
	<ul class="price-photos" role="list">
		<?php foreach ( $ccs_pp_cards as $ccs_group => $ccs_card ) : ?>
			<?php
			$ccs_photo = isset( $ccs_pp_photos[ $ccs_group ] ) ? $ccs_pp_photos[ $ccs_group ] : null;
			$ccs_link  = isset( $ccs_pc_links[ $ccs_group ] ) ? $ccs_pc_links[ $ccs_group ] : null;
			?>
			<li class="price-photo" data-ccs-exception="rule 1: each card is one kind of care and its prices, compared side by side">
				<div class="careers-benefits__media price-photo__media">
					<?php
					if ( $ccs_photo && function_exists( 'ccs_section_photo' ) ) {
						ccs_section_photo(
							$ccs_photo[0],
							array(
								'alt'      => $ccs_photo[1],
								'sizes'    => '(min-width: 860px) 30rem, 92vw',
								'class'    => 'price-photo__img',
								'position' => isset( $ccs_photo[2] ) ? $ccs_photo[2] : '50% 50%',
							)
						);
					}
					?>
					<p class="careers-benefits__badge price-photo__tab" data-ccs-exception="rule 1: the starting price, set on the photo as a label">
						<strong><?php echo esc_html( $ccs_card['price'] ); ?></strong>
						<span><?php esc_html_e( 'an hour', 'ccs-wp-theme' ); ?></span>
						<small><?php esc_html_e( 'Starting rate', 'ccs-wp-theme' ); ?></small>
					</p>
				</div>
				<div class="price-photo__body">
					<h3 class="price-photo__name"><?php echo esc_html( $ccs_group ); ?></h3>
					<ul class="price-photo__lines" role="list">
						<?php foreach ( $ccs_card['lines'] as $ccs_line ) : ?>
							<li>
								<?php echo ccs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?>
								<span class="price-photo__what"><?php echo esc_html( $ccs_line[0] ); ?></span>
								<?php // "from" before every figure (client, Nov 2026: "make sure it says from"). ?>
								<span class="price-photo__amount"><?php esc_html_e( 'from', 'ccs-wp-theme' ); ?> <strong><?php echo esc_html( $ccs_line[1] ); ?></strong> <?php echo esc_html( $ccs_line[2] ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
					<?php if ( $ccs_link ) : ?>
						<a class="btn btn-quiet price-photo__link" href="<?php echo esc_url( $ccs_link[0] ); ?>"><?php echo esc_html( $ccs_link[1] ); ?> <span aria-hidden="true">&rarr;</span></a>
					<?php endif; ?>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php
	return;
endif;
?>

<ul class="price-cards" role="list">
	<?php foreach ( $ccs_pc_cards as $ccs_card ) : ?>
		<li class="price-card" data-ccs-exception="rule 1: each card is one kind of care and its prices, compared side by side">
			<div class="price-card__head">
				<span class="ccs-badge ccs-badge--sm price-card__icon" aria-hidden="true"><?php echo ccs_icon( $ccs_card['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG from ccs_icon(). ?></span>
				<h3 class="price-card__name"><?php echo esc_html( $ccs_card['name'] ); ?></h3>
			</div>
			<p class="price-card__from">
				<span class="price-card__word"><?php esc_html_e( 'from', 'ccs-wp-theme' ); ?></span>
				<span class="price-card__price"><?php echo esc_html( $ccs_card['price'] ); ?></span>
				<span class="price-card__unit"><?php echo esc_html( $ccs_card['unit'] ); ?></span>
			</p>
			<dl class="price-card__lines">
				<?php foreach ( $ccs_card['lines'] as $ccs_line ) : ?>
					<div>
						<dt><?php echo esc_html( $ccs_line[0] ); ?></dt>
						<dd><?php echo esc_html( $ccs_line[1] ); ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
			<?php if ( $ccs_card['link'] ) : ?>
				<a class="ccs-text-link price-card__link" href="<?php echo esc_url( $ccs_card['link'][0] ); ?>"><?php echo esc_html( $ccs_card['link'][1] ); ?> <span aria-hidden="true">&rarr;</span></a>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>

<?php // The Costs page lists the extra charges in its accordion instead ('facts' => false). ?>
<?php if ( ! empty( $ccs_pc_facts ) && ( ! isset( $args['facts'] ) || $args['facts'] ) ) : ?>
	<ul class="price-facts" role="list" data-ccs-exception="rule 1: the extra charges grouped as one strip, read together with the cards" aria-label="<?php esc_attr_e( 'Also charged', 'ccs-wp-theme' ); ?>">
		<?php foreach ( $ccs_pc_facts as $ccs_fact ) : ?>
			<li class="price-facts__item">
				<span class="price-facts__value"><?php echo esc_html( $ccs_fact['value'] ); ?></span>
				<span class="price-facts__text"><?php echo esc_html( $ccs_fact['text'] ); ?></span>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
