<?php
/**
 * Admin welcome/setup page under Appearance → CCS Theme Setup.
 * Welcome message, quick-start checklist, theme info, Reset Demo Content, server requirements.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Welcome_Screen
 */
class CCS_Welcome_Screen {

	const OPTION_CHECKLIST       = 'ccs_welcome_checklist';
	const OPTION_CHECKLIST_DONE  = 'ccs_welcome_checklist_completed';
	const NONCE_ACTION_CHECKLIST = 'ccs_welcome_checklist_save';
	const PAGE_SLUG              = 'ccs-theme-setup';

	/**
	 * Checklist item keys and labels.
	 *
	 * @var array<string, string>
	 */
	private $checklist_items = array(
		'upload_logo'           => 'Upload logo (Appearance → Customize → Site Identity)',
		'set_contact_details'   => 'Set contact details (Appearance → Customize → Contact Info)',
		'connect_social'        => 'Connect social accounts (Appearance → Customize → Social Links)',
		'upload_service_images' => 'Upload real service images',
		'test_contact_form'     => 'Test contact form submission',
		'review_email_templates'=> 'Review and customise email templates',
		'setup_backups'         => 'Set up site backups',
		'review_security'       => 'Review security headers',
	);

	/**
	 * Constructor: register menu and handlers.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ), 20 );
		add_action( 'admin_init', array( $this, 'handle_checklist_save' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Register Appearance → CCS Theme Setup (replaces Theme Setup if loaded after Theme_Activation).
	 */
	public function register_menu() {
		add_theme_page(
			__( 'CCS Theme Setup', 'ccs-wp-theme' ),
			__( 'CCS Theme Setup', 'ccs-wp-theme' ),
			'manage_options',
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Enqueue CSS/JS only on the welcome page.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_assets( $hook_suffix ) {
		if ( $hook_suffix !== 'appearance_page_' . self::PAGE_SLUG ) {
			return;
		}

		$version = defined( 'THEME_VERSION' ) ? THEME_VERSION : '1.0.0';
		$theme_uri = defined( 'THEME_URL' ) ? THEME_URL : get_template_directory_uri();

		wp_enqueue_style(
			'ccs-welcome-screen',
			$theme_uri . '/assets/css/admin-welcome.css',
			array(),
			$version
		);

		wp_enqueue_script(
			'ccs-welcome-screen',
			$theme_uri . '/assets/js/admin-welcome.js',
			array(),
			$version,
			true
		);
	}

	/**
	 * Handle checklist form POST: validate nonce, update option, set completed when all ticked.
	 */
	public function handle_checklist_save() {
		if ( ! isset( $_POST['ccs_welcome_checklist_nonce'] ) || ! isset( $_POST['ccs_welcome_checklist'] ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccs_welcome_checklist_nonce'] ) ), self::NONCE_ACTION_CHECKLIST ) ) {
			wp_die( esc_html__( 'Security check failed.', 'ccs-wp-theme' ) );
		}

		$raw = wp_unslash( $_POST['ccs_welcome_checklist'] );
		if ( ! is_array( $raw ) ) {
			return;
		}

		$saved = array();
		foreach ( array_keys( $this->checklist_items ) as $key ) {
			$saved[ $key ] = ! empty( $raw[ $key ] );
		}

		update_option( self::OPTION_CHECKLIST, $saved );

		$all_done = ! in_array( false, $saved, true );
		if ( $all_done ) {
			delete_option( self::OPTION_CHECKLIST );
			update_option( self::OPTION_CHECKLIST_DONE, 1 );
		}

		wp_safe_redirect( add_query_arg( 'updated', '1', admin_url( 'themes.php?page=' . self::PAGE_SLUG ) ) );
		exit;
	}

	/**
	 * Get current checklist state (array of key => bool). If completed, returns all-true for display.
	 *
	 * @return array<string, bool>
	 */
	private function get_checklist_state() {
		if ( get_option( self::OPTION_CHECKLIST_DONE, 0 ) ) {
			return array_fill_keys( array_keys( $this->checklist_items ), true );
		}

		$stored = get_option( self::OPTION_CHECKLIST, array() );
		$state  = array();
		foreach ( array_keys( $this->checklist_items ) as $key ) {
			$state[ $key ] = ! empty( $stored[ $key ] );
		}
		return $state;
	}

	/**
	 * Whether the checklist is in "all done" state (show collapsed "You're all set up!").
	 *
	 * @return bool
	 */
	private function is_checklist_completed() {
		return (bool) get_option( self::OPTION_CHECKLIST_DONE, 0 );
	}

	/**
	 * Render the full welcome page.
	 */
	public function render_page() {
		$reset_url = wp_nonce_url(
			add_query_arg( 'ccs_reset_demo', '1', admin_url( 'themes.php?page=' . self::PAGE_SLUG ) ),
			'ccs_reset_demo'
		);
		$reset_confirm = __( 'This will delete all demo pages, the three care service posts, and the Primary/Footer menus, then recreate them. Continue?', 'ccs-wp-theme' );
		$demo_reset_message = isset( $_GET['ccs_demo_reset'] ) ? __( 'Demo content has been reset and recreated.', 'ccs-wp-theme' ) : '';
		$updated_message   = isset( $_GET['updated'] ) ? __( 'Checklist updated.', 'ccs-wp-theme' ) : '';

		$checklist_completed = $this->is_checklist_completed();
		$checklist_state     = $this->get_checklist_state();

		?>
		<div class="wrap ccs-welcome-wrap">
			<h1><?php esc_html_e( 'CCS Theme Setup', 'ccs-wp-theme' ); ?></h1>

			<?php if ( $demo_reset_message ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $demo_reset_message ); ?></p></div>
			<?php endif; ?>
			<?php if ( $updated_message ) : ?>
				<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $updated_message ); ?></p></div>
			<?php endif; ?>

			<section class="ccs-welcome-section ccs-welcome-intro">
				<h2><?php esc_html_e( 'Welcome', 'ccs-wp-theme' ); ?></h2>
				<p><?php esc_html_e( 'Use this page to complete theme setup, run through the quick start checklist, and check your server environment.', 'ccs-wp-theme' ); ?></p>
			</section>

			<section class="ccs-welcome-section ccs-welcome-checklist">
				<h2><?php esc_html_e( 'Quick start checklist', 'ccs-wp-theme' ); ?></h2>

				<?php if ( $checklist_completed ) : ?>
					<div class="ccs-welcome-all-set" role="region" aria-label="<?php esc_attr_e( 'Setup complete', 'ccs-wp-theme' ); ?>">
						<button type="button" class="ccs-welcome-all-set-toggle" aria-expanded="false" aria-controls="ccs-welcome-all-set-list">
							<span class="ccs-welcome-all-set-icon" aria-hidden="true"></span>
							<?php esc_html_e( "You're all set up!", 'ccs-wp-theme' ); ?>
						</button>
						<div id="ccs-welcome-all-set-list" class="ccs-welcome-all-set-list" hidden>
							<ul class="ccs-welcome-checklist-list ccs-welcome-checklist-list--all-done">
								<?php foreach ( $this->checklist_items as $key => $label ) : ?>
									<li class="ccs-welcome-checklist-item ccs-welcome-checklist-item--done">
										<span class="ccs-welcome-checklist-check" aria-hidden="true"></span>
										<?php echo esc_html( $label ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				<?php else : ?>
					<form method="post" action="">
						<?php wp_nonce_field( self::NONCE_ACTION_CHECKLIST, 'ccs_welcome_checklist_nonce' ); ?>
						<ul class="ccs-welcome-checklist-list">
							<?php foreach ( $this->checklist_items as $key => $label ) : ?>
								<li class="ccs-welcome-checklist-item">
									<label>
										<input type="checkbox" name="ccs_welcome_checklist[<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( ! empty( $checklist_state[ $key ] ) ); ?> />
										<?php echo esc_html( $label ); ?>
									</label>
								</li>
							<?php endforeach; ?>
						</ul>
						<p>
							<button type="submit" class="button button-primary"><?php esc_html_e( 'Save checklist', 'ccs-wp-theme' ); ?></button>
						</p>
					</form>
				<?php endif; ?>
			</section>

			<section class="ccs-welcome-section ccs-welcome-theme-info">
				<h2><?php esc_html_e( 'Theme info', 'ccs-wp-theme' ); ?></h2>
				<ul class="ccs-welcome-theme-meta">
					<li><strong><?php esc_html_e( 'Version', 'ccs-wp-theme' ); ?>:</strong> <?php echo esc_html( defined( 'THEME_VERSION' ) ? THEME_VERSION : '—' ); ?></li>
					<li>
						<strong><?php esc_html_e( 'Documentation', 'ccs-wp-theme' ); ?>:</strong>
						<?php
						$docs_path = defined( 'THEME_DIR' ) ? THEME_DIR . '/docs/CCS-THEME-AND-CONTENT-GUIDE.md' : '';
						if ( $docs_path && is_readable( $docs_path ) ) {
							$docs_url = add_query_arg( array( 'page' => self::PAGE_SLUG, 'ccs_view' => 'docs' ), admin_url( 'themes.php' ) );
							printf( '<a href="%1$s">%2$s</a>', esc_url( $docs_url ), esc_html__( 'Theme and content guide', 'ccs-wp-theme' ) );
						} else {
							esc_html_e( 'See docs/ folder in the theme (CCS-THEME-AND-CONTENT-GUIDE.md).', 'ccs-wp-theme' );
						}
						?>
					</li>
					<li><strong><?php esc_html_e( 'Changelog', 'ccs-wp-theme' ); ?>:</strong> <?php esc_html_e( 'See version control or theme readme for history.', 'ccs-wp-theme' ); ?></li>
				</ul>
			</section>

			<section class="ccs-welcome-section ccs-welcome-reset">
				<h2><?php esc_html_e( 'Demo content', 'ccs-wp-theme' ); ?></h2>
				<p><?php esc_html_e( 'On theme activation, the theme creates demo pages, service posts, menus, and configures Reading and permalinks. You can reset that demo content below and have it recreated.', 'ccs-wp-theme' ); ?></p>
				<p>
					<a href="<?php echo esc_url( $reset_url ); ?>" class="button button-primary" onclick="return confirm('<?php echo esc_js( $reset_confirm ); ?>');"><?php esc_html_e( 'Reset Demo Content', 'ccs-wp-theme' ); ?></a>
				</p>
			</section>

			<section class="ccs-welcome-section ccs-welcome-critical-css">
				<h2><?php esc_html_e( 'Critical CSS', 'ccs-wp-theme' ); ?></h2>
				<p><?php esc_html_e( 'After changing critical or design-system CSS, either run WP-CLI: wp ccs regenerate-critical-css --clear, or clear stored CSS below so the theme uses assets/css/critical.css from disk.', 'ccs-wp-theme' ); ?></p>
				<p>
					<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'ccs_clear_critical_css', '1', home_url( '/' ) ), 'ccs_clear_critical_css' ) ); ?>" class="button"><?php esc_html_e( 'Clear stored critical CSS', 'ccs-wp-theme' ); ?></a>
				</p>
			</section>

			<section class="ccs-welcome-section ccs-welcome-requirements">
				<h2><?php esc_html_e( 'Server requirements', 'ccs-wp-theme' ); ?></h2>
				<?php $this->render_requirements(); ?>
			</section>
		</div>

		<?php
		if ( isset( $_GET['ccs_view'] ) && $_GET['ccs_view'] === 'docs' && current_user_can( 'manage_options' ) ) {
			$this->render_docs_modal();
		}
	}

	/**
	 * Output server requirements (PHP version, memory, etc.).
	 */
	private function render_requirements() {
		$php_version = PHP_VERSION;
		$php_ok      = version_compare( $php_version, '7.4.0', '>=' );
		$mem         = ini_get( 'memory_limit' );
		$mem_bytes   = wp_convert_hr_to_bytes( $mem );
		$mem_ok      = $mem_bytes >= 128 * 1024 * 1024; // 128M
		$max_exec    = ini_get( 'max_execution_time' );
		$max_exec_ok = $max_exec <= 0 || (int) $max_exec >= 30;

		?>
		<table class="widefat striped">
			<tbody>
				<tr>
					<td><?php esc_html_e( 'PHP version', 'ccs-wp-theme' ); ?></td>
					<td><code><?php echo esc_html( $php_version ); ?></code> <?php echo $php_ok ? '✓' : '<span class="ccs-welcome-warn">' . esc_html__( 'Recommend 7.4+', 'ccs-wp-theme' ) . '</span>'; ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Memory limit', 'ccs-wp-theme' ); ?></td>
					<td><code><?php echo esc_html( $mem ); ?></code> <?php echo $mem_ok ? '✓' : '<span class="ccs-welcome-warn">' . esc_html__( 'Recommend 128M+', 'ccs-wp-theme' ) . '</span>'; ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'Max execution time', 'ccs-wp-theme' ); ?></td>
					<td><code><?php echo esc_html( $max_exec ); ?></code> <?php echo $max_exec_ok ? '✓' : '<span class="ccs-welcome-warn">' . esc_html__( 'Recommend 30+', 'ccs-wp-theme' ) . '</span>'; ?></td>
				</tr>
				<tr>
					<td><?php esc_html_e( 'WordPress version', 'ccs-wp-theme' ); ?></td>
					<td><code><?php echo esc_html( get_bloginfo( 'version' ) ); ?></code></td>
				</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * If viewing docs, output inline doc content (optional overlay or same page).
	 */
	private function render_docs_modal() {
		$path = defined( 'THEME_DIR' ) ? THEME_DIR . '/docs/CCS-THEME-AND-CONTENT-GUIDE.md' : '';
		if ( ! $path || ! is_readable( $path ) ) {
			return;
		}

		$content = file_get_contents( $path );
		if ( $content === false ) {
			return;
		}

		// Simple markdown-to-html for display (no full parser; basic line breaks and code).
		$content = esc_html( $content );
		$content = preg_replace( '/\n(#{1,6})\s+(.+)/', '<br><strong>$2</strong>', $content );
		$content = nl2br( $content );
		?>
		<div class="ccs-welcome-docs-overlay" id="ccs-welcome-docs" role="dialog" aria-labelledby="ccs-welcome-docs-title">
			<div class="ccs-welcome-docs-inner">
				<h2 id="ccs-welcome-docs-title"><?php esc_html_e( 'Theme and content guide', 'ccs-wp-theme' ); ?></h2>
				<div class="ccs-welcome-docs-content"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
				<p><a href="<?php echo esc_url( remove_query_arg( 'ccs_view' ) ); ?>" class="button"><?php esc_html_e( 'Close', 'ccs-wp-theme' ); ?></a></p>
			</div>
		</div>
		<?php
	}
}
