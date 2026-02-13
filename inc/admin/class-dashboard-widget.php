<?php
/**
 * Custom admin dashboard widget: stats, chart, recent enquiries, quick actions.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Dashboard_Widget
 */
class CCS_Dashboard_Widget {

	/**
	 * Widget ID.
	 *
	 * @var string
	 */
	const WIDGET_ID = 'ccs_care_dashboard';

	/**
	 * Constructor: register widget and enqueue assets.
	 */
	public function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'register_widget' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register the dashboard widget.
	 */
	public function register_widget() {
		wp_add_dashboard_widget(
			self::WIDGET_ID,
			__( 'Care dashboard', 'ccs-wp-theme' ),
			array( $this, 'render_widget' ),
			null,
			null,
			'normal',
			'high'
		);
	}

	/**
	 * Enqueue dashboard CSS and Chart.js only on dashboard index.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_assets( $hook_suffix ) {
		if ( $hook_suffix !== 'index.php' ) {
			return;
		}

		$theme_uri = THEME_URL;
		$version   = THEME_VERSION;

		wp_enqueue_style(
			'ccs-admin-dashboard',
			$theme_uri . '/assets/css/admin-dashboard.css',
			array(),
			$version
		);

		wp_enqueue_script(
			'chartjs',
			'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js',
			array(),
			'4.4.1',
			true
		);

		wp_enqueue_script(
			'ccs-admin-dashboard',
			$theme_uri . '/assets/js/admin-dashboard.js',
			array( 'chartjs' ),
			$version,
			true
		);

		$stats   = $this->get_dashboard_stats();
		$chart   = $this->get_enquiries_chart_data();
		$enquiries = $this->get_recent_enquiries();

		wp_localize_script(
			'ccs-admin-dashboard',
			'ccsDashboard',
			array(
				'chartLabels'   => $chart['labels'],
				'chartCounts'   => $chart['counts'],
				'stats'         => $stats,
			)
		);
	}

	/**
	 * Render the widget content.
	 */
	public function render_widget() {
		$stats     = $this->get_dashboard_stats();
		$enquiries = $this->get_recent_enquiries();
		$edit_base = admin_url( 'post.php?post=%d&action=edit' );
		$new_url   = admin_url( 'edit.php?post_type=enquiry' );
		$service_new_url = admin_url( 'post-new.php?post_type=service' );
		?>
		<div class="ccs-dashboard">
			<!-- Stats cards -->
			<div class="ccs-dashboard__stats">
				<div class="ccs-dashboard__stat-card">
					<span class="ccs-dashboard__stat-value"><?php echo esc_html( number_format_i18n( $stats['new_enquiries_this_week'] ) ); ?></span>
					<span class="ccs-dashboard__stat-label"><?php esc_html_e( 'New enquiries (this week)', 'ccs-wp-theme' ); ?></span>
					<?php $this->render_trend( $stats['new_enquiries_this_week'], $stats['new_enquiries_last_week'], 'enquiries' ); ?>
				</div>
				<div class="ccs-dashboard__stat-card">
					<span class="ccs-dashboard__stat-value"><?php echo esc_html( number_format_i18n( $stats['pending_follow_ups'] ) ); ?></span>
					<span class="ccs-dashboard__stat-label"><?php esc_html_e( 'Pending follow-ups (today)', 'ccs-wp-theme' ); ?></span>
				</div>
				<div class="ccs-dashboard__stat-card">
					<span class="ccs-dashboard__stat-value"><?php echo esc_html( number_format_i18n( $stats['conversions_30d'] ) ); ?></span>
					<span class="ccs-dashboard__stat-label"><?php esc_html_e( 'Conversions (last 30 days)', 'ccs-wp-theme' ); ?></span>
					<?php if ( $stats['total_enquiries_30d'] > 0 ) : ?>
						<span class="ccs-dashboard__stat-meta"><?php echo esc_html( number_format( $stats['conversion_rate'], 1 ) ); ?>% <?php esc_html_e( 'rate', 'ccs-wp-theme' ); ?></span>
					<?php endif; ?>
				</div>
				<div class="ccs-dashboard__stat-card">
					<span class="ccs-dashboard__stat-value">£<?php echo esc_html( number_format_i18n( $stats['revenue_this_month'], 0 ) ); ?></span>
					<span class="ccs-dashboard__stat-label"><?php esc_html_e( 'Revenue (this month)', 'ccs-wp-theme' ); ?></span>
					<?php $this->render_trend( $stats['revenue_this_month'], $stats['revenue_last_month'], 'revenue' ); ?>
				</div>
			</div>

			<!-- Chart -->
			<div class="ccs-dashboard__chart-wrap">
				<h4 class="ccs-dashboard__chart-title"><?php esc_html_e( 'Enquiries over time (last 90 days)', 'ccs-wp-theme' ); ?></h4>
				<div class="ccs-dashboard__chart-container">
					<canvas id="ccs-enquiries-chart" height="200" aria-label="<?php esc_attr_e( 'Line chart of daily enquiry count', 'ccs-wp-theme' ); ?>"></canvas>
				</div>
			</div>

			<!-- Recent enquiries table -->
			<div class="ccs-dashboard__table-wrap">
				<h4 class="ccs-dashboard__table-title"><?php esc_html_e( 'Recent enquiries', 'ccs-wp-theme' ); ?></h4>
				<table class="ccs-dashboard__table widefat striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Date', 'ccs-wp-theme' ); ?></th>
							<th><?php esc_html_e( 'Name', 'ccs-wp-theme' ); ?></th>
							<th><?php esc_html_e( 'Contact', 'ccs-wp-theme' ); ?></th>
							<th><?php esc_html_e( 'Care type', 'ccs-wp-theme' ); ?></th>
							<th><?php esc_html_e( 'Urgency', 'ccs-wp-theme' ); ?></th>
							<th><?php esc_html_e( 'Status', 'ccs-wp-theme' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( empty( $enquiries ) ) : ?>
							<tr>
								<td colspan="6"><?php esc_html_e( 'No enquiries yet.', 'ccs-wp-theme' ); ?></td>
							</tr>
						<?php else : ?>
							<?php foreach ( $enquiries as $e ) : ?>
								<tr>
									<td><?php echo esc_html( get_the_date( '', $e ) ); ?></td>
									<td><a href="<?php echo esc_url( sprintf( $edit_base, $e->ID ) ); ?>"><?php echo esc_html( get_post_meta( $e->ID, 'enquiry_name', true ) ?: '—' ); ?></a></td>
									<td>
										<?php
										$phone = get_post_meta( $e->ID, 'enquiry_phone', true );
										$email = get_post_meta( $e->ID, 'enquiry_email', true );
										if ( $phone ) {
											echo '<a href="tel:' . esc_attr( preg_replace( '/\s+/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
										} elseif ( $email ) {
											echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
										} else {
											echo '—';
										}
										?>
									</td>
									<td><?php echo esc_html( get_post_meta( $e->ID, 'enquiry_care_type', true ) ?: '—' ); ?></td>
									<td><?php $this->render_urgency_badge( get_post_meta( $e->ID, 'enquiry_urgency', true ) ); ?></td>
									<td><?php $this->render_status_badge( get_post_meta( $e->ID, 'enquiry_status', true ) ); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<!-- Quick actions -->
			<div class="ccs-dashboard__actions">
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=enquiry' ) ); ?>" class="button button-primary"><?php esc_html_e( 'Review new enquiries', 'ccs-wp-theme' ); ?></a>
				<a href="<?php echo esc_url( $service_new_url ); ?>" class="button"><?php esc_html_e( 'Add new service', 'ccs-wp-theme' ); ?></a>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=enquiry' ) ); ?>" class="ccs-dashboard__link"><?php esc_html_e( 'All enquiries', 'ccs-wp-theme' ); ?></a>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=service' ) ); ?>" class="ccs-dashboard__link"><?php esc_html_e( 'Services', 'ccs-wp-theme' ); ?></a>
				<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=location' ) ); ?>" class="ccs-dashboard__link"><?php esc_html_e( 'Locations', 'ccs-wp-theme' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Output a small trend indicator (vs previous period).
	 *
	 * @param float|int $current Current value.
	 * @param float|int $previous Previous period value.
	 * @param string   $type     'enquiries' or 'revenue'.
	 */
	private function render_trend( $current, $previous, $type = 'enquiries' ) {
		if ( $previous == 0 && $current == 0 ) {
			return;
		}
		if ( $previous == 0 ) {
			echo '<span class="ccs-dashboard__trend ccs-dashboard__trend--up">+' . esc_html__( 'new', 'ccs-wp-theme' ) . '</span>';
			return;
		}
		$change = ( (float) $current - (float) $previous ) / (float) $previous * 100;
		$class  = $change > 0 ? 'up' : ( $change < 0 ? 'down' : 'same' );
		$label  = $change > 0 ? '+' . number_format( $change, 0 ) . '%' : ( $change < 0 ? number_format( $change, 0 ) . '%' : __( 'Same', 'ccs-wp-theme' ) );
		echo '<span class="ccs-dashboard__trend ccs-dashboard__trend--' . esc_attr( $class ) . '">' . esc_html( $label ) . ' ' . esc_html__( 'vs last period', 'ccs-wp-theme' ) . '</span>';
	}

	/**
	 * Output urgency badge markup.
	 *
	 * @param string $urgency Meta value.
	 */
	private function render_urgency_badge( $urgency ) {
		if ( ! $urgency ) {
			echo '—';
			return;
		}
		$labels = array(
			'urgent'         => __( 'Urgent', 'ccs-wp-theme' ),
			'soon'           => __( 'Soon', 'ccs-wp-theme' ),
			'exploring'      => __( 'Exploring', 'ccs-wp-theme' ),
			'immediate'      => __( 'Immediate', 'ccs-wp-theme' ),
			'this_week'      => __( 'This week', 'ccs-wp-theme' ),
			'this_month'     => __( 'This month', 'ccs-wp-theme' ),
			'just_exploring' => __( 'Exploring', 'ccs-wp-theme' ),
		);
		$label = isset( $labels[ $urgency ] ) ? $labels[ $urgency ] : $urgency;
		$class = ( $urgency === 'urgent' || $urgency === 'immediate' ) ? 'ccs-dashboard__badge--urgent' : '';
		echo '<span class="ccs-dashboard__badge ' . esc_attr( $class ) . '">' . esc_html( $label ) . '</span>';
	}

	/**
	 * Output status badge markup.
	 *
	 * @param string $status Meta value.
	 */
	private function render_status_badge( $status ) {
		if ( ! $status ) {
			echo '—';
			return;
		}
		$labels = array(
			'new'                 => __( 'New', 'ccs-wp-theme' ),
			'contacted'           => __( 'Contacted', 'ccs-wp-theme' ),
			'assessment_scheduled' => __( 'Assessment scheduled', 'ccs-wp-theme' ),
			'proposal_sent'       => __( 'Proposal sent', 'ccs-wp-theme' ),
			'won'                 => __( 'Won', 'ccs-wp-theme' ),
			'lost'                => __( 'Lost', 'ccs-wp-theme' ),
			'not_right_fit'       => __( 'Not right fit', 'ccs-wp-theme' ),
		);
		$label = isset( $labels[ $status ] ) ? $labels[ $status ] : $status;
		$class = 'ccs-dashboard__badge--' . sanitize_html_class( $status );
		echo '<span class="ccs-dashboard__badge ' . esc_attr( $class ) . '">' . esc_html( $label ) . '</span>';
	}

	/**
	 * Get dashboard stats for the four cards.
	 *
	 * @return array
	 */
	public function get_dashboard_stats() {
		$this_week    = $this->count_enquiries( null, 'this_week' );
		$last_week    = $this->count_enquiries( null, 'last_week' );
		$pending      = $this->count_pending_follow_ups_today();
		$conversions = $this->count_enquiries( 'won', 'last_30_days' );
		$total_30    = $this->count_enquiries( null, 'last_30_days' );
		$rate        = $total_30 > 0 ? ( $conversions / $total_30 ) * 100 : 0;
		$rev_this    = $this->get_revenue_for_period( 'this_month' );
		$rev_last    = $this->get_revenue_for_period( 'last_month' );

		return array(
			'new_enquiries_this_week' => $this_week,
			'new_enquiries_last_week' => $last_week,
			'pending_follow_ups'      => $pending,
			'conversions_30d'         => $conversions,
			'total_enquiries_30d'     => $total_30,
			'conversion_rate'         => $rate,
			'revenue_this_month'      => $rev_this,
			'revenue_last_month'      => $rev_last,
		);
	}

	/**
	 * Get last 10 enquiries for the table.
	 *
	 * @return WP_Post[]
	 */
	public function get_recent_enquiries() {
		return get_posts( array(
			'post_type'      => 'enquiry',
			'post_status'    => 'any',
			'posts_per_page' => 10,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
	}

	/**
	 * Count enquiries by status and optional date range.
	 *
	 * @param string|null $status    Post meta enquiry_status value, or null for any.
	 * @param string      $date_range One of: 'this_week', 'last_week', 'last_30_days', 'this_month', 'last_month', or pass array with 'from' and 'to' (Y-m-d).
	 * @return int
	 */
	public function count_enquiries( $status, $date_range ) {
		$args = array(
			'post_type'      => 'enquiry',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);

		if ( $status !== null && $status !== '' ) {
			$args['meta_query'] = array(
				array(
					'key'   => 'enquiry_status',
					'value' => $status,
				),
			);
		}

		$range = $this->resolve_date_range( $date_range );
		if ( $range ) {
			$before = $range['to'];
			if ( $range['from'] === $range['to'] ) {
				$before = gmdate( 'Y-m-d', strtotime( $range['to'] . ' +1 day' ) );
			}
			$date_query = array(
				array(
					'after'     => $range['from'],
					'before'    => $before,
					'inclusive' => true,
				),
			);
			if ( ! isset( $args['meta_query'] ) ) {
				$args['meta_query'] = array();
			}
			$args['date_query'] = $date_query;
		}

		$q = new WP_Query( $args );
		return $q->found_posts;
	}

	/**
	 * Count enquiries with follow_up_date = today, status not won/lost/not_right_fit.
	 *
	 * @return int
	 */
	private function count_pending_follow_ups_today() {
		$today = gmdate( 'Y-m-d' );
		$q = new WP_Query( array(
			'post_type'      => 'enquiry',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'   => 'enquiry_follow_up_date',
					'value' => $today,
					'type'  => 'DATE',
				),
				array(
					'key'     => 'enquiry_status',
					'value'   => array( 'won', 'lost', 'not_right_fit' ),
					'compare' => 'NOT IN',
				),
			),
		) );
		return $q->found_posts;
	}

	/**
	 * Get total contract value (revenue) for won enquiries in period.
	 *
	 * @param string $period 'this_month' or 'last_month'.
	 * @return float
	 */
	private function get_revenue_for_period( $period ) {
		$range = $this->resolve_date_range( $period );
		if ( ! $range ) {
			return 0;
		}
		$posts = get_posts( array(
			'post_type'      => 'enquiry',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => 'enquiry_status',
					'value' => 'won',
				),
				array(
					'key'     => 'enquiry_converted_date',
					'value'   => array( $range['from'], $range['to'] ),
					'compare' => 'BETWEEN',
					'type'    => 'DATE',
				),
			),
		) );
		$total = 0;
		foreach ( $posts as $id ) {
			$val = get_post_meta( $id, 'enquiry_contract_value', true );
			$total += (float) $val;
		}
		return $total;
	}

	/**
	 * Resolve date range to from/to Y-m-d.
	 *
	 * @param string|array $date_range Period key or array with from/to.
	 * @return array|null Associative array with 'from' and 'to', or null.
	 */
	private function resolve_date_range( $date_range ) {
		if ( is_array( $date_range ) && isset( $date_range['from'], $date_range['to'] ) ) {
			return $date_range;
		}
		$tz = wp_timezone();
		$now = new DateTime( 'now', $tz );
		switch ( $date_range ) {
			case 'this_week':
				$from = clone $now;
				$from->setISODate( (int) $from->format( 'Y' ), (int) $from->format( 'W' ), 1 );
				$to = clone $from;
				$to->modify( '+6 days' );
				return array( 'from' => $from->format( 'Y-m-d' ), 'to' => $to->format( 'Y-m-d' ) );
			case 'last_week':
				$from = clone $now;
				$from->setISODate( (int) $from->format( 'Y' ), (int) $from->format( 'W' ) - 1, 1 );
				$to = clone $from;
				$to->modify( '+6 days' );
				return array( 'from' => $from->format( 'Y-m-d' ), 'to' => $to->format( 'Y-m-d' ) );
			case 'last_30_days':
				$to = clone $now;
				$from = clone $now;
				$from->modify( '-29 days' );
				return array( 'from' => $from->format( 'Y-m-d' ), 'to' => $to->format( 'Y-m-d' ) );
			case 'this_month':
				$from = clone $now;
				$from->modify( 'first day of this month' );
				return array( 'from' => $from->format( 'Y-m-d' ), 'to' => $now->format( 'Y-m-d' ) );
			case 'last_month':
				$from = clone $now;
				$from->modify( 'first day of last month' );
				$to = clone $now;
				$to->modify( 'last day of last month' );
				return array( 'from' => $from->format( 'Y-m-d' ), 'to' => $to->format( 'Y-m-d' ) );
			default:
				return null;
		}
	}

	/**
	 * Get chart data: last 90 days, daily enquiry count.
	 *
	 * @return array { labels: string[], counts: int[] }
	 */
	public function get_enquiries_chart_data() {
		$tz   = wp_timezone();
		$now  = new DateTime( 'now', $tz );
		$end  = clone $now;
		$start = clone $now;
		$start->modify( '-89 days' );

		$labels = array();
		$counts = array();
		$current = clone $start;
		while ( $current <= $end ) {
			$labels[] = $current->format( 'M j' );
			$counts[] = $this->count_enquiries( null, array(
				'from' => $current->format( 'Y-m-d' ),
				'to'   => $current->format( 'Y-m-d' ),
			) );
			$current->modify( '+1 day' );
		}

		return array( 'labels' => $labels, 'counts' => $counts );
	}
}
