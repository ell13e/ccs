<?php
/**
 * Customize Enquiries admin list table: columns, filters, bulk/row actions, quick status, export.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Enquiry_Manager
 */
class CCS_Enquiry_Manager {

	const POST_TYPE   = 'enquiry';
	const NONCE_QUICK = 'ccs_enquiry_quick_status';
	const NONCE_EXPORT = 'ccs_enquiry_export_csv';

	/**
	 * Status options for dropdown and badges.
	 *
	 * @var array
	 */
	private static $status_options = array(
		'new'                 => 'New',
		'contacted'           => 'Contacted',
		'assessment_scheduled' => 'Assessment Scheduled',
		'proposal_sent'       => 'Proposal Sent',
		'won'                 => 'Won',
		'lost'                => 'Lost',
		'not_right_fit'       => 'Not Right Fit',
	);

	/**
	 * Source options.
	 *
	 * @var array
	 */
	private static $source_options = array(
		'website_form'   => 'Website Form',
		'google_ads'     => 'Google Ads',
		'facebook_ads'   => 'Facebook Ads',
		'organic_search' => 'Organic Search',
		'referral'       => 'Referral',
		'phone_call'     => 'Phone Call',
		'email'          => 'Email',
	);

	/**
	 * Urgency options.
	 *
	 * @var array
	 */
	private static $urgency_options = array(
		'urgent'         => 'Urgent',
		'soon'           => 'Soon',
		'exploring'      => 'Exploring',
		'immediate'      => 'Immediate',
		'this_week'      => 'This Week',
		'this_month'     => 'This Month',
		'just_exploring' => 'Exploring',
	);

	/**
	 * Constructor: register hooks.
	 */
	public function __construct() {
		add_filter( 'manage_' . self::POST_TYPE . '_posts_columns', array( $this, 'columns' ) );
		add_action( 'manage_' . self::POST_TYPE . '_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
		add_filter( 'manage_edit-' . self::POST_TYPE . '_sortable_columns', array( $this, 'sortable_columns' ) );
		add_action( 'pre_get_posts', array( $this, 'sort_and_filter' ) );
		add_action( 'restrict_manage_posts', array( $this, 'filters' ), 10, 2 );
		add_filter( 'bulk_actions_edit-' . self::POST_TYPE, array( $this, 'bulk_actions' ) );
		add_filter( 'handle_bulk_actions_edit-' . self::POST_TYPE, array( $this, 'handle_bulk_actions' ), 10, 3 );
		add_filter( 'post_row_actions', array( $this, 'row_actions' ), 10, 2 );
		add_action( 'admin_footer-edit.php', array( $this, 'footer_scripts' ) );
		add_action( 'wp_ajax_ccs_enquiry_quick_view', array( $this, 'ajax_quick_view' ) );
		add_action( 'wp_ajax_ccs_enquiry_quick_status', array( $this, 'ajax_quick_status' ) );
		add_action( 'load-edit.php', array( $this, 'maybe_export_csv' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Define custom columns (replace default).
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function columns( $columns ) {
		$new = array(
			'cb'            => $columns['cb'],
			'date'          => __( 'Date', 'ccs-wp-theme' ),
			'name'          => __( 'Name', 'ccs-wp-theme' ),
			'contact'       => __( 'Contact', 'ccs-wp-theme' ),
			'care_type'     => __( 'Care Type', 'ccs-wp-theme' ),
			'location'      => __( 'Location', 'ccs-wp-theme' ),
			'urgency'       => __( 'Urgency', 'ccs-wp-theme' ),
			'status'        => __( 'Status', 'ccs-wp-theme' ),
			'source'        => __( 'Source', 'ccs-wp-theme' ),
			'assigned_to'   => __( 'Assigned To', 'ccs-wp-theme' ),
			'follow_up'     => __( 'Follow-up', 'ccs-wp-theme' ),
		);
		return $new;
	}

	/**
	 * Output column content.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Post ID.
	 */
	public function column_content( $column, $post_id ) {
		switch ( $column ) {
			case 'name':
				$name = get_post_meta( $post_id, 'enquiry_name', true );
				$msg  = get_post_meta( $post_id, 'enquiry_message', true );
				echo '<strong>' . esc_html( $name ?: '—' ) . '</strong>';
				if ( $msg ) {
					$preview = wp_trim_words( strip_tags( $msg ), 12 );
					echo ' <span class="ccs-enquiry-preview" title="' . esc_attr( $preview ) . '">&#9432;</span>';
				}
				break;
			case 'contact':
				$phone = get_post_meta( $post_id, 'enquiry_phone', true );
				$email = get_post_meta( $post_id, 'enquiry_email', true );
				if ( $phone ) {
					echo '<a href="tel:' . esc_attr( preg_replace( '/\s+/', '', $phone ) ) . '" class="ccs-contact-link" title="' . esc_attr__( 'Call', 'ccs-wp-theme' ) . '">' . esc_html( $phone ) . '</a>';
				}
				if ( $phone && $email ) {
					echo '<br>';
				}
				if ( $email ) {
					echo '<a href="mailto:' . esc_attr( $email ) . '" class="ccs-contact-link" title="' . esc_attr__( 'Email', 'ccs-wp-theme' ) . '">' . esc_html( $email ) . '</a>';
				}
				if ( ! $phone && ! $email ) {
					echo '—';
				}
				break;
			case 'care_type':
				echo esc_html( get_post_meta( $post_id, 'enquiry_care_type', true ) ?: '—' );
				break;
			case 'location':
				echo esc_html( get_post_meta( $post_id, 'enquiry_location', true ) ?: '—' );
				break;
			case 'urgency':
				$this->render_urgency_badge( get_post_meta( $post_id, 'enquiry_urgency', true ) );
				break;
			case 'status':
				$status = get_post_meta( $post_id, 'enquiry_status', true );
				$this->render_status_dropdown( $post_id, $status );
				break;
			case 'source':
				echo esc_html( $this->get_source_label( get_post_meta( $post_id, 'enquiry_source', true ) ) );
				break;
			case 'assigned_to':
				$uid = get_post_meta( $post_id, 'enquiry_assigned_to', true );
				if ( $uid && ( $user = get_userdata( (int) $uid ) ) ) {
					echo get_avatar( $user->ID, 24, '', '', array( 'class' => 'ccs-assigned-avatar' ) );
					echo ' ' . esc_html( $user->display_name );
				} else {
					echo '—';
				}
				break;
			case 'follow_up':
				$date = get_post_meta( $post_id, 'enquiry_follow_up_date', true );
				if ( ! $date ) {
					echo '—';
					break;
				}
				$ts = strtotime( $date );
				$today = gmdate( 'Y-m-d' );
				$class = '';
				if ( $date === $today ) {
					$class = 'ccs-follow-up-today';
				} elseif ( $date < $today ) {
					$class = 'ccs-follow-up-overdue';
				}
				echo '<span class="' . esc_attr( $class ) . '">' . esc_html( gmdate( 'd M Y', $ts ) ) . '</span>';
				break;
		}
	}

	/**
	 * Sortable columns.
	 *
	 * @param array $columns Column keys.
	 * @return array
	 */
	public function sortable_columns( $columns ) {
		$columns['date'] = 'date';
		$columns['name'] = 'enquiry_name';
		$columns['follow_up'] = 'enquiry_follow_up_date';
		return $columns;
	}

	/**
	 * Apply sorting and filters to the list query.
	 *
	 * @param WP_Query $query Query object.
	 */
	public function sort_and_filter( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() || $query->get( 'post_type' ) !== self::POST_TYPE ) {
			return;
		}

		$orderby = $query->get( 'orderby' );

		if ( $orderby === 'enquiry_name' ) {
			$query->set( 'meta_key', 'enquiry_name' );
			$query->set( 'orderby', 'meta_value' );
		} elseif ( $orderby === 'enquiry_follow_up_date' ) {
			$query->set( 'meta_key', 'enquiry_follow_up_date' );
			$query->set( 'orderby', 'meta_value' );
			$query->set( 'meta_type', 'DATE' );
		}

		$meta_query = array();
		if ( ! empty( $_GET['ccs_status'] ) ) {
			$meta_query[] = array(
				'key'   => 'enquiry_status',
				'value' => sanitize_text_field( wp_unslash( $_GET['ccs_status'] ) ),
			);
		}
		if ( ! empty( $_GET['ccs_source'] ) ) {
			$meta_query[] = array(
				'key'   => 'enquiry_source',
				'value' => sanitize_text_field( wp_unslash( $_GET['ccs_source'] ) ),
			);
		}
		if ( ! empty( $_GET['ccs_urgency'] ) ) {
			$meta_query[] = array(
				'key'   => 'enquiry_urgency',
				'value' => sanitize_text_field( wp_unslash( $_GET['ccs_urgency'] ) ),
			);
		}
		if ( ! empty( $_GET['ccs_date_range'] ) ) {
			$range = $this->parse_date_range( sanitize_text_field( wp_unslash( $_GET['ccs_date_range'] ) ) );
			if ( $range ) {
				$query->set( 'date_query', array(
					array(
						'after'     => $range['from'],
						'before'    => $range['to'],
						'inclusive' => true,
					),
				) );
			}
		}
		if ( ! empty( $meta_query ) ) {
			$existing = $query->get( 'meta_query' );
			$meta_query = array_merge( is_array( $existing ) ? $existing : array(), $meta_query );
			$query->set( 'meta_query', $meta_query );
		}
	}

	/**
	 * Parse date range key to from/to.
	 *
	 * @param string $key Key (e.g. last_7_days, this_month).
	 * @return array|null
	 */
	private function parse_date_range( $key ) {
		$tz = wp_timezone();
		$now = new DateTime( 'now', $tz );
		switch ( $key ) {
			case 'today':
				$d = $now->format( 'Y-m-d' );
				return array( 'from' => $d, 'to' => $d );
			case 'last_7_days':
				$to = clone $now;
				$from = clone $now;
				$from->modify( '-6 days' );
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
	 * Output filter dropdowns and Export CSV button.
	 *
	 * @param string $post_type Post type.
	 * @param string $which    'top' or 'bottom'.
	 */
	public function filters( $post_type, $which ) {
		if ( $post_type !== self::POST_TYPE || $which !== 'top' ) {
			return;
		}

		$current_status  = isset( $_GET['ccs_status'] ) ? sanitize_text_field( wp_unslash( $_GET['ccs_status'] ) ) : '';
		$current_source  = isset( $_GET['ccs_source'] ) ? sanitize_text_field( wp_unslash( $_GET['ccs_source'] ) ) : '';
		$current_urgency = isset( $_GET['ccs_urgency'] ) ? sanitize_text_field( wp_unslash( $_GET['ccs_urgency'] ) ) : '';
		$current_range   = isset( $_GET['ccs_date_range'] ) ? sanitize_text_field( wp_unslash( $_GET['ccs_date_range'] ) ) : '';

		$export_url = add_query_arg( array(
			'post_type'   => self::POST_TYPE,
			'ccs_export'  => '1',
			'ccs_status'  => $current_status,
			'ccs_source'  => $current_source,
			'ccs_urgency' => $current_urgency,
			'ccs_date_range' => $current_range,
			'_wpnonce'    => wp_create_nonce( self::NONCE_EXPORT ),
		), admin_url( 'edit.php' ) );
		?>
		<select name="ccs_status">
			<option value=""><?php esc_html_e( 'All statuses', 'ccs-wp-theme' ); ?></option>
			<?php foreach ( self::$status_options as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current_status, $value ); ?>><?php echo esc_html( __( $label, 'ccs-wp-theme' ) ); ?></option>
			<?php endforeach; ?>
		</select>
		<select name="ccs_source">
			<option value=""><?php esc_html_e( 'All sources', 'ccs-wp-theme' ); ?></option>
			<?php foreach ( self::$source_options as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current_source, $value ); ?>><?php echo esc_html( __( $label, 'ccs-wp-theme' ) ); ?></option>
			<?php endforeach; ?>
		</select>
		<select name="ccs_date_range">
			<option value=""><?php esc_html_e( 'Any date', 'ccs-wp-theme' ); ?></option>
			<option value="today" <?php selected( $current_range, 'today' ); ?>><?php esc_html_e( 'Today', 'ccs-wp-theme' ); ?></option>
			<option value="last_7_days" <?php selected( $current_range, 'last_7_days' ); ?>><?php esc_html_e( 'Last 7 days', 'ccs-wp-theme' ); ?></option>
			<option value="last_30_days" <?php selected( $current_range, 'last_30_days' ); ?>><?php esc_html_e( 'Last 30 days', 'ccs-wp-theme' ); ?></option>
			<option value="this_month" <?php selected( $current_range, 'this_month' ); ?>><?php esc_html_e( 'This month', 'ccs-wp-theme' ); ?></option>
			<option value="last_month" <?php selected( $current_range, 'last_month' ); ?>><?php esc_html_e( 'Last month', 'ccs-wp-theme' ); ?></option>
		</select>
		<select name="ccs_urgency">
			<option value=""><?php esc_html_e( 'Any urgency', 'ccs-wp-theme' ); ?></option>
			<?php foreach ( self::$urgency_options as $value => $label ) : ?>
				<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $current_urgency, $value ); ?>><?php echo esc_html( __( $label, 'ccs-wp-theme' ) ); ?></option>
			<?php endforeach; ?>
		</select>
		<a href="<?php echo esc_url( $export_url ); ?>" class="button"><?php esc_html_e( 'Export CSV', 'ccs-wp-theme' ); ?></a>
		<?php
	}

	/**
	 * Add bulk actions.
	 *
	 * @param array $actions Existing actions.
	 * @return array
	 */
	public function bulk_actions( $actions ) {
		$actions['ccs_mark_contacted'] = __( 'Mark as Contacted', 'ccs-wp-theme' );
		$actions['ccs_mark_won']      = __( 'Mark as Won', 'ccs-wp-theme' );
		$actions['ccs_mark_lost']     = __( 'Mark as Lost', 'ccs-wp-theme' );
		$actions['ccs_assign_me']     = __( 'Assign to Me', 'ccs-wp-theme' );
		$actions['ccs_export_selected'] = __( 'Export Selected', 'ccs-wp-theme' );
		return $actions;
	}

	/**
	 * Handle bulk actions.
	 *
	 * @param string $redirect_to Redirect URL.
	 * @param string $doaction   Action name.
	 * @param int[]  $post_ids   Post IDs.
	 * @return string
	 */
	public function handle_bulk_actions( $redirect_to, $doaction, $post_ids ) {
		if ( empty( $post_ids ) || ! is_array( $post_ids ) ) {
			return $redirect_to;
		}

		$handled = false;
		switch ( $doaction ) {
			case 'ccs_mark_contacted':
				foreach ( $post_ids as $id ) {
					update_post_meta( (int) $id, 'enquiry_status', 'contacted' );
				}
				$handled = true;
				$redirect_to = add_query_arg( 'ccs_bulk', 'contacted', $redirect_to );
				break;
			case 'ccs_mark_won':
				$today = gmdate( 'Y-m-d' );
				foreach ( $post_ids as $id ) {
					update_post_meta( (int) $id, 'enquiry_status', 'won' );
					update_post_meta( (int) $id, 'enquiry_converted_date', $today );
				}
				$handled = true;
				$redirect_to = add_query_arg( 'ccs_bulk', 'won', $redirect_to );
				break;
			case 'ccs_mark_lost':
				foreach ( $post_ids as $id ) {
					update_post_meta( (int) $id, 'enquiry_status', 'lost' );
				}
				$handled = true;
				$redirect_to = add_query_arg( 'ccs_bulk', 'lost', $redirect_to );
				break;
			case 'ccs_assign_me':
				$user_id = get_current_user_id();
				foreach ( $post_ids as $id ) {
					update_post_meta( (int) $id, 'enquiry_assigned_to', $user_id );
				}
				$handled = true;
				$redirect_to = add_query_arg( 'ccs_bulk', 'assigned', $redirect_to );
				break;
			case 'ccs_export_selected':
				$this->export_csv( $post_ids );
				exit;
		}

		return $handled ? $redirect_to : $redirect_to;
	}

	/**
	 * Add row actions: Call, Email, Edit, Quick View.
	 *
	 * @param string[] $actions Row actions.
	 * @param WP_Post  $post    Post.
	 * @return array
	 */
	public function row_actions( $actions, $post ) {
		if ( $post->post_type !== self::POST_TYPE ) {
			return $actions;
		}

		$new = array();
		$phone = get_post_meta( $post->ID, 'enquiry_phone', true );
		$email = get_post_meta( $post->ID, 'enquiry_email', true );
		if ( $phone ) {
			$new['call'] = '<a href="tel:' . esc_attr( preg_replace( '/\s+/', '', $phone ) ) . '">' . esc_html__( 'Call', 'ccs-wp-theme' ) . '</a>';
		}
		if ( $email ) {
			$new['email'] = '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html__( 'Email', 'ccs-wp-theme' ) . '</a>';
		}
		$new['edit'] = $actions['edit'];
		$new['quick_view'] = '<a href="#" class="ccs-quick-view" data-id="' . (int) $post->ID . '">' . esc_html__( 'Quick View', 'ccs-wp-theme' ) . '</a>';
		unset( $actions['edit'], $actions['inline hide-if-no-js'] );
		return array_merge( $new, $actions );
	}

	/**
	 * Render urgency badge (color-coded).
	 *
	 * @param string $urgency Meta value.
	 */
	private function render_urgency_badge( $urgency ) {
		if ( ! $urgency ) {
			echo '—';
			return;
		}
		$label = isset( self::$urgency_options[ $urgency ] ) ? self::$urgency_options[ $urgency ] : $urgency;
		$class = in_array( $urgency, array( 'urgent', 'immediate' ), true ) ? 'ccs-urgency-urgent' : 'ccs-urgency-normal';
		echo '<span class="ccs-urgency-badge ' . esc_attr( $class ) . '">' . esc_html( __( $label, 'ccs-wp-theme' ) ) . '</span>';
	}

	/**
	 * Render status as clickable badge with dropdown for quick edit.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $current Current status.
	 */
	private function render_status_dropdown( $post_id, $current ) {
		$label = isset( self::$status_options[ $current ] ) ? self::$status_options[ $current ] : ( $current ?: '—' );
		$nonce = wp_create_nonce( self::NONCE_QUICK );
		echo '<span class="ccs-status-wrap" data-id="' . (int) $post_id . '" data-nonce="' . esc_attr( $nonce ) . '">';
		echo '<span class="ccs-status-badge ccs-status-' . esc_attr( sanitize_html_class( $current ) ) . '" tabindex="0" role="button" aria-haspopup="true" aria-expanded="false">' . esc_html( __( $label, 'ccs-wp-theme' ) ) . '</span>';
		echo '<select class="ccs-status-select hide">';
		foreach ( self::$status_options as $value => $opt_label ) {
			echo '<option value="' . esc_attr( $value ) . '" ' . selected( $current, $value, false ) . '>' . esc_html( __( $opt_label, 'ccs-wp-theme' ) ) . '</option>';
		}
		echo '</select>';
		echo '</span>';
	}

	/**
	 * Get source label.
	 *
	 * @param string $value Meta value.
	 * @return string
	 */
	private function get_source_label( $value ) {
		if ( ! $value ) {
			return '—';
		}
		return isset( self::$source_options[ $value ] ) ? __( self::$source_options[ $value ], 'ccs-wp-theme' ) : $value;
	}

	/**
	 * Get urgency label.
	 *
	 * @param string $value Meta value.
	 * @return string
	 */
	private function get_urgency_label( $value ) {
		if ( ! $value ) {
			return '';
		}
		return isset( self::$urgency_options[ $value ] ) ? self::$urgency_options[ $value ] : $value;
	}

	/**
	 * Enqueue admin scripts/styles on edit.php for enquiry.
	 */
	public function enqueue_assets( $hook ) {
		global $typenow;
		if ( $hook !== 'edit.php' || $typenow !== self::POST_TYPE ) {
			return;
		}

		$uri = THEME_URL;
		$ver = THEME_VERSION;
		wp_enqueue_style( 'ccs-enquiry-manager', $uri . '/assets/css/admin-enquiry-manager.css', array(), $ver );
		wp_enqueue_script( 'ccs-enquiry-manager', $uri . '/assets/js/admin-enquiry-manager.js', array( 'jquery' ), $ver, true );
		wp_localize_script( 'ccs-enquiry-manager', 'ccsEnquiryManager', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( self::NONCE_QUICK ),
			'strings' => array(
				'quickView' => __( 'Quick View', 'ccs-wp-theme' ),
				'close'     => __( 'Close', 'ccs-wp-theme' ),
				'updated'   => __( 'Updated.', 'ccs-wp-theme' ),
				'error'     => __( 'Update failed.', 'ccs-wp-theme' ),
			),
		) );
	}

	/**
	 * Output Quick View modal and status-dropdown JS in footer.
	 */
	public function footer_scripts() {
		global $typenow;
		if ( $typenow !== self::POST_TYPE ) {
			return;
		}
		?>
		<div id="ccs-quick-view-modal" class="ccs-qv-modal" role="dialog" aria-modal="true" aria-labelledby="ccs-qv-title" hidden>
			<div class="ccs-qv-backdrop"></div>
			<div class="ccs-qv-content">
				<button type="button" class="ccs-qv-close" aria-label="<?php esc_attr_e( 'Close', 'ccs-wp-theme' ); ?>">&times;</button>
				<h2 id="ccs-qv-title" class="ccs-qv-title"><?php esc_html_e( 'Enquiry details', 'ccs-wp-theme' ); ?></h2>
				<div class="ccs-qv-body"></div>
			</div>
		</div>
		<?php
	}

	/**
	 * AJAX: Quick View content.
	 */
	public function ajax_quick_view() {
		check_ajax_referer( self::NONCE_QUICK, 'nonce' );
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => 'Forbidden' ) );
		}
		$id = isset( $_GET['id'] ) ? (int) $_GET['id'] : 0;
		if ( ! $id || get_post_type( $id ) !== self::POST_TYPE ) {
			wp_send_json_error( array( 'message' => 'Invalid ID' ) );
		}

		$name    = get_post_meta( $id, 'enquiry_name', true );
		$email   = get_post_meta( $id, 'enquiry_email', true );
		$phone   = get_post_meta( $id, 'enquiry_phone', true );
		$care    = get_post_meta( $id, 'enquiry_care_type', true );
		$location = get_post_meta( $id, 'enquiry_location', true );
		$urgency = get_post_meta( $id, 'enquiry_urgency', true );
		$status  = get_post_meta( $id, 'enquiry_status', true );
		$source  = get_post_meta( $id, 'enquiry_source', true );
		$message = get_post_meta( $id, 'enquiry_message', true );
		$follow  = get_post_meta( $id, 'enquiry_follow_up_date', true );
		$assigned = get_post_meta( $id, 'enquiry_assigned_to', true );
		$edit_url = admin_url( 'post.php?post=' . $id . '&action=edit' );

		$html = '<table class="ccs-qv-table"><tbody>';
		$html .= '<tr><th>' . esc_html__( 'Name', 'ccs-wp-theme' ) . '</th><td>' . esc_html( $name ?: '—' ) . '</td></tr>';
		$html .= '<tr><th>' . esc_html__( 'Email', 'ccs-wp-theme' ) . '</th><td>' . ( $email ? '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>' : '—' ) . '</td></tr>';
		$html .= '<tr><th>' . esc_html__( 'Phone', 'ccs-wp-theme' ) . '</th><td>' . ( $phone ? '<a href="tel:' . esc_attr( preg_replace( '/\s+/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>' : '—' ) . '</td></tr>';
		$html .= '<tr><th>' . esc_html__( 'Care type', 'ccs-wp-theme' ) . '</th><td>' . esc_html( $care ?: '—' ) . '</td></tr>';
		$html .= '<tr><th>' . esc_html__( 'Location', 'ccs-wp-theme' ) . '</th><td>' . esc_html( $location ?: '—' ) . '</td></tr>';
		$html .= '<tr><th>' . esc_html__( 'Urgency', 'ccs-wp-theme' ) . '</th><td>' . esc_html( isset( self::$urgency_options[ $urgency ] ) ? self::$urgency_options[ $urgency ] : ( $urgency ?: '—' ) ) . '</td></tr>';
		$html .= '<tr><th>' . esc_html__( 'Status', 'ccs-wp-theme' ) . '</th><td>' . esc_html( isset( self::$status_options[ $status ] ) ? self::$status_options[ $status ] : ( $status ?: '—' ) ) . '</td></tr>';
		$html .= '<tr><th>' . esc_html__( 'Source', 'ccs-wp-theme' ) . '</th><td>' . esc_html( $this->get_source_label( $source ) ) . '</td></tr>';
		$html .= '<tr><th>' . esc_html__( 'Follow-up', 'ccs-wp-theme' ) . '</th><td>' . esc_html( $follow ? gmdate( 'd M Y', strtotime( $follow ) ) : '—' ) . '</td></tr>';
		if ( $assigned && ( $user = get_userdata( (int) $assigned ) ) ) {
			$html .= '<tr><th>' . esc_html__( 'Assigned to', 'ccs-wp-theme' ) . '</th><td>' . esc_html( $user->display_name ) . '</td></tr>';
		}
		if ( $message ) {
			$html .= '<tr><th>' . esc_html__( 'Message', 'ccs-wp-theme' ) . '</th><td>' . nl2br( esc_html( $message ) ) . '</td></tr>';
		}
		$html .= '</tbody></table>';
		$html .= '<p><a href="' . esc_url( $edit_url ) . '" class="button button-primary">' . esc_html__( 'Edit enquiry', 'ccs-wp-theme' ) . '</a></p>';

		wp_send_json_success( array( 'html' => $html ) );
	}

	/**
	 * AJAX: Quick status update (and set converted_date when Won).
	 */
	public function ajax_quick_status() {
		check_ajax_referer( self::NONCE_QUICK, 'nonce' );
		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => 'Forbidden' ) );
		}
		$id = isset( $_POST['id'] ) ? (int) $_POST['id'] : 0;
		$status = isset( $_POST['status'] ) ? sanitize_text_field( wp_unslash( $_POST['status'] ) ) : '';
		if ( ! $id || get_post_type( $id ) !== self::POST_TYPE || ! array_key_exists( $status, self::$status_options ) ) {
			wp_send_json_error( array( 'message' => 'Invalid request' ) );
		}

		update_post_meta( $id, 'enquiry_status', $status );
		if ( $status === 'won' ) {
			update_post_meta( $id, 'enquiry_converted_date', gmdate( 'Y-m-d' ) );
		}

		wp_send_json_success( array(
			'label' => __( self::$status_options[ $status ], 'ccs-wp-theme' ),
		) );
	}

	/**
	 * Handle Export CSV (full list with current filters or selected IDs).
	 */
	public function maybe_export_csv() {
		global $typenow;
		if ( $typenow !== self::POST_TYPE ) {
			return;
		}

		if ( ! empty( $_GET['ccs_export'] ) && wp_verify_nonce( isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '', self::NONCE_EXPORT ) ) {
			$this->export_csv( null );
			exit;
		}

		if ( ! empty( $_REQUEST['post'] ) && is_array( $_REQUEST['post'] ) && ! empty( $_REQUEST['action'] ) && $_REQUEST['action'] === 'ccs_export_selected' ) {
			$ids = array_map( 'intval', wp_unslash( $_REQUEST['post'] ) );
			if ( wp_verify_nonce( isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '', 'bulk-posts' ) ) {
				$this->export_csv( $ids );
				exit;
			}
		}
	}

	/**
	 * Output CSV of enquiries. If $post_ids is null, use current query (filtered list).
	 *
	 * @param int[]|null $post_ids Post IDs or null for current filter.
	 */
	private function export_csv( $post_ids ) {
		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		if ( $post_ids === null ) {
			$query = new WP_Query( array(
				'post_type'      => self::POST_TYPE,
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'orderby'        => 'date',
				'order'          => 'DESC',
			) );
			if ( ! empty( $_GET['ccs_status'] ) ) {
				$query->query_vars['meta_query'][] = array(
					'key'   => 'enquiry_status',
					'value' => sanitize_text_field( wp_unslash( $_GET['ccs_status'] ) ),
				);
			}
			if ( ! empty( $_GET['ccs_source'] ) ) {
				$query->query_vars['meta_query'][] = array(
					'key'   => 'enquiry_source',
					'value' => sanitize_text_field( wp_unslash( $_GET['ccs_source'] ) ),
				);
			}
			if ( ! empty( $_GET['ccs_urgency'] ) ) {
				$query->query_vars['meta_query'][] = array(
					'key'   => 'enquiry_urgency',
					'value' => sanitize_text_field( wp_unslash( $_GET['ccs_urgency'] ) ),
				);
			}
			if ( ! empty( $_GET['ccs_date_range'] ) ) {
				$range = $this->parse_date_range( sanitize_text_field( wp_unslash( $_GET['ccs_date_range'] ) ) );
				if ( $range ) {
					$query->query_vars['date_query'] = array(
						array(
							'after'     => $range['from'],
							'before'    => $range['to'],
							'inclusive' => true,
						),
					);
				}
			}
			$query->get_posts();
			$post_ids = $query->posts;
		}

		$filename = 'enquiries-' . gmdate( 'Y-m-d-His' ) . '.csv';
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		$out = fopen( 'php://output', 'w' );
		fputcsv( $out, array(
			__( 'Date', 'ccs-wp-theme' ),
			__( 'Name', 'ccs-wp-theme' ),
			__( 'Email', 'ccs-wp-theme' ),
			__( 'Phone', 'ccs-wp-theme' ),
			__( 'Care type', 'ccs-wp-theme' ),
			__( 'Location', 'ccs-wp-theme' ),
			__( 'Urgency', 'ccs-wp-theme' ),
			__( 'Status', 'ccs-wp-theme' ),
			__( 'Source', 'ccs-wp-theme' ),
			__( 'Message', 'ccs-wp-theme' ),
			__( 'Follow-up', 'ccs-wp-theme' ),
		) );

		foreach ( $post_ids as $id ) {
			$post = get_post( $id );
			if ( ! $post || $post->post_type !== self::POST_TYPE ) {
				continue;
			}
			fputcsv( $out, array(
				get_the_date( '', $post ),
				get_post_meta( $id, 'enquiry_name', true ),
				get_post_meta( $id, 'enquiry_email', true ),
				get_post_meta( $id, 'enquiry_phone', true ),
				get_post_meta( $id, 'enquiry_care_type', true ),
				get_post_meta( $id, 'enquiry_location', true ),
				$this->get_urgency_label( get_post_meta( $id, 'enquiry_urgency', true ) ),
				isset( self::$status_options[ get_post_meta( $id, 'enquiry_status', true ) ] ) ? self::$status_options[ get_post_meta( $id, 'enquiry_status', true ) ] : '',
				$this->get_source_label( get_post_meta( $id, 'enquiry_source', true ) ),
				get_post_meta( $id, 'enquiry_message', true ),
				get_post_meta( $id, 'enquiry_follow_up_date', true ),
			) );
		}
		fclose( $out );
	}
