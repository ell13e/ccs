<?php
/**
 * Custom admin dashboard widget: enquiry stats, by-service bar chart, recent enquiries, quick actions.
 * Uses ccs_enquiry post type. Cached with transients (1 hour). No Chart.js or external deps.
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

	const WIDGET_ID   = 'ccs_care_dashboard';
	const POST_TYPE  = 'ccs_enquiry';
	const TRANSIENT  = 'ccs_dashboard_stats';
	const CACHE_TTL  = HOUR_IN_SECONDS;
	const EXPORT_ACTION = 'ccs_dashboard_export_month';
	const NONCE_KEY  = 'ccs_dashboard_export';

	/**
	 * Constructor: register widget, assets, and export handler.
	 */
	public function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'register_widget' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'admin_post_' . self::EXPORT_ACTION, array( $this, 'handle_export_csv' ) );
		add_action( 'save_post_' . self::POST_TYPE, array( $this, 'invalidate_cache' ) );
	}

	/**
	 * Invalidate dashboard stats cache when an enquiry is saved.
	 */
	public function invalidate_cache() {
		delete_transient( self::TRANSIENT );
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
	 * Enqueue dashboard CSS only on dashboard index (no Chart.js).
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_assets( $hook_suffix ) {
		if ( $hook_suffix !== 'index.php' ) {
			return;
		}
		wp_enqueue_style(
			'ccs-admin-dashboard',
			THEME_URL . '/assets/css/admin-dashboard.css',
			array(),
			THEME_VERSION
		);
	}

	/**
	 * Get dashboard data from cache or build and cache it.
	 *
	 * @return array{ this_month: int, last_month: int, pct_change: float, by_service: array, recent: WP_Post[] }
	 */
	public function get_cached_stats() {
		$data = get_transient( self::TRANSIENT );
		if ( is_array( $data ) && isset( $data['this_month'], $data['by_service'], $data['recent'] ) ) {
			return $data;
		}
		$data = $this->build_dashboard_data();
		set_transient( self::TRANSIENT, $data, self::CACHE_TTL );
		return $data;
	}

	/**
	 * Build stats, by-service counts, and recent enquiries.
	 *
	 * @return array
	 */
	private function build_dashboard_data() {
		$this_month = $this->count_enquiries_for_period( 'this_month' );
		$last_month = $this->count_enquiries_for_period( 'last_month' );
		$pct_change = $last_month > 0
			? ( ( $this_month - $last_month ) / $last_month ) * 100
			: ( $this_month > 0 ? 100.0 : 0.0 );

		$by_service = $this->get_enquiries_by_service();
		$recent     = $this->get_recent_enquiries( 5 );

		return array(
			'this_month'  => $this_month,
			'last_month'  => $last_month,
			'pct_change'  => round( $pct_change, 1 ),
			'by_service'  => $by_service,
			'recent'      => $recent,
		);
	}

	/**
	 * Count enquiries in a period (calendar month).
	 *
	 * @param string $period 'this_month' or 'last_month'.
	 * @return int
	 */
	private function count_enquiries_for_period( $period ) {
		$range = $this->get_month_range( $period );
		if ( ! $range ) {
			return 0;
		}
		$q = new WP_Query( array(
			'post_type'      => self::POST_TYPE,
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'date_query'     => array(
				array(
					'after'     => $range['from'] . ' 00:00:00',
					'before'    => $range['to'] . ' 23:59:59',
					'inclusive' => true,
				),
			),
		) );
		return $q->found_posts;
	}

	/**
	 * Get from/to dates for this_month or last_month.
	 *
	 * @param string $period 'this_month' or 'last_month'.
	 * @return array{ from: string, to: string }|null
	 */
	private function get_month_range( $period ) {
		$tz  = wp_timezone();
		$now = new DateTime( 'now', $tz );
		if ( $period === 'this_month' ) {
			$from = (clone $now)->modify( 'first day of this month' );
			return array( 'from' => $from->format( 'Y-m-d' ), 'to' => $now->format( 'Y-m-d' ) );
		}
		if ( $period === 'last_month' ) {
			$from = (clone $now)->modify( 'first day of last month' );
			$to   = (clone $now)->modify( 'last day of last month' );
			return array( 'from' => $from->format( 'Y-m-d' ), 'to' => $to->format( 'Y-m-d' ) );
		}
		return null;
	}

	/**
	 * Get enquiry counts grouped by service (enquiry_care_type). Supports comma-separated values.
	 *
	 * @return array<string, int> Service label => count.
	 */
	private function get_enquiries_by_service() {
		$posts = get_posts( array(
			'post_type'      => self::POST_TYPE,
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		) );
		$by_service = array();
		foreach ( $posts as $id ) {
			$care = get_post_meta( $id, 'enquiry_care_type', true );
			if ( ! is_string( $care ) || $care === '' ) {
				$care = __( 'Not specified', 'ccs-wp-theme' );
			}
			$services = array_map( 'trim', explode( ',', $care ) );
			$services = array_filter( $services );
			if ( empty( $services ) ) {
				$services = array( __( 'Not specified', 'ccs-wp-theme' ) );
			}
			foreach ( $services as $s ) {
				$by_service[ $s ] = isset( $by_service[ $s ] ) ? $by_service[ $s ] + 1 : 1;
			}
		}
		arsort( $by_service, SORT_NUMERIC );
		return $by_service;
	}

	/**
	 * Get recent N enquiries.
	 *
	 * @param int $n Number of posts.
	 * @return WP_Post[]
	 */
	private function get_recent_enquiries( $n = 5 ) {
		return get_posts( array(
			'post_type'      => self::POST_TYPE,
			'post_status'    => 'any',
			'posts_per_page' => $n,
			'orderby'        => 'date',
			'order'          => 'DESC',
		) );
	}

	/**
	 * Render the widget content.
	 */
	public function render_widget() {
		$data     = $this->get_cached_stats();
		$edit_url = admin_url( 'post.php?post=%d&action=edit' );
		$list_url = admin_url( 'edit.php?post_type=' . self::POST_TYPE );
		$export_url = add_query_arg( array(
			'action'   => self::EXPORT_ACTION,
			'_wpnonce' => wp_create_nonce( self::NONCE_KEY ),
		), admin_url( 'admin-post.php' ) );
		?>
		<div class="ccs-dashboard">
			<!-- 1. Stats -->
			<div class="ccs-dashboard__stats">
				<div class="ccs-dashboard__stat-card">
					<span class="ccs-dashboard__stat-value"><?php echo esc_html( number_format_i18n( $data['this_month'] ) ); ?></span>
					<span class="ccs-dashboard__stat-label"><?php esc_html_e( 'Enquiries this month', 'ccs-wp-theme' ); ?></span>
					<?php $this->render_trend( $data['this_month'], $data['last_month'] ); ?>
				</div>
				<div class="ccs-dashboard__stat-card">
					<span class="ccs-dashboard__stat-value"><?php echo esc_html( number_format_i18n( $data['last_month'] ) ); ?></span>
					<span class="ccs-dashboard__stat-label"><?php esc_html_e( 'Enquiries last month', 'ccs-wp-theme' ); ?></span>
				</div>
			</div>

			<!-- 2. Enquiries by service (HTML/CSS bar chart) -->
			<div class="ccs-dashboard__chart-wrap">
				<h4 class="ccs-dashboard__chart-title"><?php esc_html_e( 'Enquiries by service', 'ccs-wp-theme' ); ?></h4>
				<?php $this->render_bar_chart( $data['by_service'] ); ?>
			</div>

			<!-- 3. Recent 5 enquiries -->
			<div class="ccs-dashboard__table-wrap">
				<h4 class="ccs-dashboard__table-title"><?php esc_html_e( 'Recent enquiries', 'ccs-wp-theme' ); ?></h4>
				<table class="ccs-dashboard__table widefat striped">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Name', 'ccs-wp-theme' ); ?></th>
							<th><?php esc_html_e( 'Service', 'ccs-wp-theme' ); ?></th>
							<th><?php esc_html_e( 'Date', 'ccs-wp-theme' ); ?></th>
							<th><?php esc_html_e( 'Status', 'ccs-wp-theme' ); ?></th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php if ( empty( $data['recent'] ) ) : ?>
							<tr>
								<td colspan="5"><?php esc_html_e( 'No enquiries yet.', 'ccs-wp-theme' ); ?></td>
							</tr>
						<?php else : ?>
							<?php foreach ( $data['recent'] as $post ) : ?>
								<?php
								$name   = get_post_meta( $post->ID, 'enquiry_name', true ) ?: '—';
								$service = get_post_meta( $post->ID, 'enquiry_care_type', true ) ?: '—';
								$status  = get_post_meta( $post->ID, 'enquiry_status', true );
								?>
								<tr>
									<td><a href="<?php echo esc_url( sprintf( $edit_url, $post->ID ) ); ?>"><?php echo esc_html( $name ); ?></a></td>
									<td><?php echo esc_html( $service ); ?></td>
									<td><?php echo esc_html( get_the_date( '', $post ) ); ?></td>
									<td><?php $this->render_status_badge( $status ); ?></td>
									<td><a href="<?php echo esc_url( sprintf( $edit_url, $post->ID ) ); ?>" class="ccs-dashboard__link"><?php esc_html_e( 'View', 'ccs-wp-theme' ); ?></a></td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<!-- 4. Quick actions -->
			<div class="ccs-dashboard__actions">
				<a href="<?php echo esc_url( $list_url ); ?>" class="button button-primary"><?php esc_html_e( 'View All Enquiries', 'ccs-wp-theme' ); ?></a>
				<a href="<?php echo esc_url( $export_url ); ?>" class="button"><?php esc_html_e( 'Export This Month', 'ccs-wp-theme' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Output trend vs last month (percentage change).
	 *
	 * @param int $this_month Current month count.
	 * @param int $last_month Last month count.
	 */
	private function render_trend( $this_month, $last_month ) {
		if ( $last_month === 0 && $this_month === 0 ) {
			return;
		}
		if ( $last_month === 0 ) {
			echo '<span class="ccs-dashboard__trend ccs-dashboard__trend--up">+' . esc_html__( 'new', 'ccs-wp-theme' ) . '</span>';
			return;
		}
		$change = ( (float) $this_month - (float) $last_month ) / (float) $last_month * 100;
		$class  = $change > 0 ? 'up' : ( $change < 0 ? 'down' : 'same' );
		$label  = $change > 0 ? '+' . number_format( $change, 1 ) . '%' : ( $change < 0 ? number_format( $change, 1 ) . '%' : __( 'Same', 'ccs-wp-theme' ) );
		echo '<span class="ccs-dashboard__trend ccs-dashboard__trend--' . esc_attr( $class ) . '">' . esc_html( $label ) . ' ' . esc_html__( 'vs last month', 'ccs-wp-theme' ) . '</span>';
	}

	/**
	 * Render HTML/CSS bar chart: one row per service, bar width by count (max = 100%).
	 *
	 * @param array<string, int> $by_service Service name => count.
	 */
	private function render_bar_chart( $by_service ) {
		$max = ! empty( $by_service ) ? max( array_values( $by_service ) ) : 0;
		$colors = array( 'var(--ccs-primary)', 'var(--ccs-secondary)', 'var(--ccs-success)', 'var(--ccs-primary-dark)' );
		$i = 0;
		if ( empty( $by_service ) ) {
			echo '<p class="ccs-dashboard__chart-empty">' . esc_html__( 'No enquiry data yet.', 'ccs-wp-theme' ) . '</p>';
			return;
		}
		echo '<ul class="ccs-dashboard__bars" role="list" aria-label="' . esc_attr__( 'Enquiry count by service', 'ccs-wp-theme' ) . '">';
		foreach ( $by_service as $label => $count ) {
			$pct = $max > 0 ? min( 100, ( $count / $max ) * 100 ) : 0;
			$color = $colors[ $i % count( $colors ) ];
			$i++;
			?>
			<li class="ccs-dashboard__bar-row">
				<span class="ccs-dashboard__bar-label"><?php echo esc_html( $label ); ?></span>
				<span class="ccs-dashboard__bar-track" aria-hidden="true">
					<span class="ccs-dashboard__bar-fill" style="width:<?php echo esc_attr( $pct ); ?>%;background:<?php echo esc_attr( $color ); ?>;"></span>
				</span>
				<span class="ccs-dashboard__bar-count" aria-hidden="true"><?php echo esc_html( number_format_i18n( $count ) ); ?></span>
			</li>
			<?php
		}
		echo '</ul>';
	}

	/**
	 * Output status badge: New / Contacted / Client (won).
	 *
	 * @param string $status Meta value.
	 */
	private function render_status_badge( $status ) {
		$labels = array(
			'new'       => __( 'New', 'ccs-wp-theme' ),
			'contacted' => __( 'Contacted', 'ccs-wp-theme' ),
			'won'       => __( 'Client', 'ccs-wp-theme' ),
		);
		$label = isset( $labels[ $status ] ) ? $labels[ $status ] : ( $status ? $status : '—' );
		$class = 'ccs-dashboard__badge';
		if ( $status === 'new' ) {
			$class .= ' ccs-dashboard__badge--new';
		} elseif ( $status === 'contacted' ) {
			$class .= ' ccs-dashboard__badge--contacted';
		} elseif ( $status === 'won' ) {
			$class .= ' ccs-dashboard__badge--won';
		}
		echo '<span class="' . esc_attr( $class ) . '">' . esc_html( $label ) . '</span>';
	}

	/**
	 * Handle CSV export for this month: nonce, capability, output CSV (admin_post_ action).
	 */
	public function handle_export_csv() {
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_die( esc_html__( 'You do not have permission to export.', 'ccs-wp-theme' ), 403 );
		}
		if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), self::NONCE_KEY ) ) {
			wp_die( esc_html__( 'Security check failed.', 'ccs-wp-theme' ), 403 );
		}
		$range = $this->get_month_range( 'this_month' );
		if ( ! $range ) {
			return;
		}
		$posts = get_posts( array(
			'post_type'      => self::POST_TYPE,
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'date_query'     => array(
				array(
					'after'     => $range['from'] . ' 00:00:00',
					'before'    => $range['to'] . ' 23:59:59',
					'inclusive' => true,
				),
			),
		) );
		$filename = 'enquiries-' . $range['from'] . '-to-' . $range['to'] . '.csv';
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		$out = fopen( 'php://output', 'w' );
		fputcsv( $out, array(
			__( 'Date', 'ccs-wp-theme' ),
			__( 'Name', 'ccs-wp-theme' ),
			__( 'Service', 'ccs-wp-theme' ),
			__( 'Status', 'ccs-wp-theme' ),
		) );
		foreach ( $posts as $post ) {
			$status = get_post_meta( $post->ID, 'enquiry_status', true );
			$status_label = $status === 'won' ? __( 'Client', 'ccs-wp-theme' ) : ( $status === 'contacted' ? __( 'Contacted', 'ccs-wp-theme' ) : __( 'New', 'ccs-wp-theme' ) );
			fputcsv( $out, array(
				get_the_date( '', $post ),
				get_post_meta( $post->ID, 'enquiry_name', true ),
				get_post_meta( $post->ID, 'enquiry_care_type', true ),
				$status_label,
			) );
		}
		fclose( $out );
		exit;
	}
}
