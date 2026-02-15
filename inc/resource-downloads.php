<?php
/**
 * Resource downloads: CPT ccs_resource, taxonomy, download tracking table, metaboxes.
 * No AI assistant; no Font Awesome in descriptions.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Resource CPT and category taxonomy.
 */
function ccs_register_resource_post_type() {
	$labels = array(
		'name'               => __( 'Resources', 'ccs-wp-theme' ),
		'singular_name'      => __( 'Resource', 'ccs-wp-theme' ),
		'add_new'            => __( 'Add New', 'ccs-wp-theme' ),
		'add_new_item'       => __( 'Add New Resource', 'ccs-wp-theme' ),
		'edit_item'          => __( 'Edit Resource', 'ccs-wp-theme' ),
		'new_item'           => __( 'New Resource', 'ccs-wp-theme' ),
		'view_item'          => __( 'View Resource', 'ccs-wp-theme' ),
		'search_items'       => __( 'Search Resources', 'ccs-wp-theme' ),
		'not_found'          => __( 'No resources found', 'ccs-wp-theme' ),
		'not_found_in_trash' => __( 'No resources found in Trash', 'ccs-wp-theme' ),
		'menu_name'          => __( 'Resources', 'ccs-wp-theme' ),
	);

	register_post_type(
		'ccs_resource',
		array(
			'labels'          => $labels,
			'public'          => false,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'menu_icon'       => 'dashicons-download',
			'supports'        => array( 'title', 'editor', 'thumbnail' ),
			'capability_type' => 'post',
			'map_meta_cap'    => true,
			'has_archive'     => false,
		)
	);

	register_taxonomy(
		'ccs_resource_category',
		array( 'ccs_resource' ),
		array(
			'label'        => __( 'Resource Categories', 'ccs-wp-theme' ),
			'public'       => false,
			'show_ui'      => true,
			'show_admin_column' => true,
			'hierarchical' => false,
		)
	);
}
add_action( 'init', 'ccs_register_resource_post_type' );

/**
 * Create downloads tracking table.
 */
function ccs_create_resource_downloads_table() {
	global $wpdb;
	$table = $wpdb->prefix . 'ccs_resource_downloads';
	$charset_collate = $wpdb->get_charset_collate();

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';

	$sql = "CREATE TABLE $table (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		resource_id bigint(20) unsigned NOT NULL,
		first_name varchar(100) NOT NULL,
		last_name varchar(100) NOT NULL,
		email varchar(255) NOT NULL,
		phone varchar(50) DEFAULT NULL,
		date_of_birth date DEFAULT NULL,
		consent tinyint(1) NOT NULL DEFAULT 1,
		ip_address varchar(45) DEFAULT NULL,
		user_agent text DEFAULT NULL,
		downloaded_at datetime NOT NULL,
		email_sent tinyint(1) NOT NULL DEFAULT 0,
		email_sent_at datetime DEFAULT NULL,
		download_count int(11) NOT NULL DEFAULT 0,
		last_downloaded_at datetime DEFAULT NULL,
		token_hash varchar(64) DEFAULT NULL,
		token_expires_at datetime DEFAULT NULL,
		PRIMARY KEY  (id),
		KEY resource_id (resource_id),
		KEY email (email),
		KEY downloaded_at (downloaded_at)
	) $charset_collate;";

	dbDelta( $sql );
}
add_action( 'after_switch_theme', 'ccs_create_resource_downloads_table', 40 );

/**
 * Add metaboxes for resource file, email template, settings.
 */
function ccs_resource_add_meta_boxes() {
	add_meta_box(
		'ccs_resource_file',
		__( 'Resource File', 'ccs-wp-theme' ),
		'ccs_resource_file_metabox',
		'ccs_resource',
		'normal',
		'high'
	);
	add_meta_box(
		'ccs_resource_email_template',
		__( 'Email Template', 'ccs-wp-theme' ),
		'ccs_resource_email_template_metabox',
		'ccs_resource',
		'normal',
		'default'
	);
	add_meta_box(
		'ccs_resource_settings',
		__( 'Resource Settings', 'ccs-wp-theme' ),
		'ccs_resource_settings_metabox',
		'ccs_resource',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'ccs_resource_add_meta_boxes' );

/**
 * File metabox: attachment ID.
 */
function ccs_resource_file_metabox( $post ) {
	wp_nonce_field( 'ccs_resource_meta', 'ccs_resource_meta_nonce' );
	$file_id   = (int) get_post_meta( $post->ID, '_ccs_resource_file_id', true );
	$file_url  = $file_id ? wp_get_attachment_url( $file_id ) : '';
	$file_name = $file_id ? wp_basename( get_attached_file( $file_id ) ) : '';
	?>
	<div style="margin-bottom: 20px;">
		<p><label for="ccs_resource_file_id"><strong><?php esc_html_e( 'Attachment ID', 'ccs-wp-theme' ); ?></strong></label></p>
		<p><input type="number" class="regular-text" id="ccs_resource_file_id" name="ccs_resource_file_id" value="<?php echo esc_attr( $file_id ); ?>" placeholder="0" required></p>
		<?php if ( $file_url ) : ?>
			<div style="padding: 12px; background: #f0f0f1; border-left: 4px solid #00a32a; margin: 12px 0;">
				<p style="margin: 0 0 8px;"><strong><?php esc_html_e( 'Current file:', 'ccs-wp-theme' ); ?></strong> <code><?php echo esc_html( $file_name ); ?></code></p>
				<p style="margin: 0;">
					<a href="<?php echo esc_url( $file_url ); ?>" target="_blank" rel="noopener" class="button button-small"><?php esc_html_e( 'View file', 'ccs-wp-theme' ); ?></a>
					<a href="<?php echo esc_url( admin_url( 'upload.php' ) ); ?>" class="button button-small"><?php esc_html_e( 'Media Library', 'ccs-wp-theme' ); ?></a>
				</p>
			</div>
		<?php else : ?>
			<div style="padding: 12px; background: #fff3cd; border-left: 4px solid #dba617; margin: 12px 0;">
				<p style="margin: 0;"><strong><?php esc_html_e( 'No file attached', 'ccs-wp-theme' ); ?></strong></p>
			</div>
		<?php endif; ?>
		<p class="description"><?php esc_html_e( 'Upload the file in Media Library, then enter its Attachment ID here.', 'ccs-wp-theme' ); ?></p>
	</div>
	<?php
}

/**
 * Email template metabox.
 */
function ccs_resource_email_template_metabox( $post ) {
	$subject = (string) get_post_meta( $post->ID, '_ccs_resource_email_subject', true );
	$body    = (string) get_post_meta( $post->ID, '_ccs_resource_email_body', true );
	if ( $subject === '' ) {
		$subject = __( 'Your {{resource_name}} from {{site_name}}', 'ccs-wp-theme' );
	}
	if ( $body === '' ) {
		$body = "Hi {{first_name}},\n\n" . __( 'Thanks for requesting {{resource_name}}.', 'ccs-wp-theme' ) . "\n\n" . __( 'Download:', 'ccs-wp-theme' ) . " {{download_link}}\n\n" . __( 'This link expires in {{expiry_days}} days.', 'ccs-wp-theme' );
	}
	?>
	<div style="margin-bottom: 20px;">
		<p><label for="ccs_resource_email_subject"><strong><?php esc_html_e( 'Subject', 'ccs-wp-theme' ); ?></strong></label></p>
		<p><input type="text" class="widefat" id="ccs_resource_email_subject" name="ccs_resource_email_subject" value="<?php echo esc_attr( $subject ); ?>" required></p>
	</div>
	<div style="margin-bottom: 20px;">
		<p><label for="ccs_resource_email_body"><strong><?php esc_html_e( 'Body', 'ccs-wp-theme' ); ?></strong></label></p>
		<p><textarea class="widefat" rows="12" id="ccs_resource_email_body" name="ccs_resource_email_body" required><?php echo esc_textarea( $body ); ?></textarea></p>
	</div>
	<div style="padding: 12px; background: #f0f6fc; border-left: 4px solid #2271b1; margin-bottom: 12px;">
		<p style="margin: 0 0 8px;"><strong><?php esc_html_e( 'Placeholders:', 'ccs-wp-theme' ); ?></strong> {{first_name}}, {{last_name}}, {{email}}, {{resource_name}}, {{download_link}}, {{expiry_days}}, {{site_name}}</p>
	</div>
	<?php
}

/**
 * Settings metabox: expiry days (no icon – no Font Awesome).
 */
function ccs_resource_settings_metabox( $post ) {
	$expiry_days = (int) get_post_meta( $post->ID, '_ccs_resource_expiry_days', true );
	if ( $expiry_days <= 0 ) {
		$expiry_days = 7;
	}
	?>
	<div style="margin-bottom: 16px;">
		<label for="ccs_resource_expiry_days"><strong><?php esc_html_e( 'Link expiry (days)', 'ccs-wp-theme' ); ?></strong></label>
		<input type="number" class="small-text" id="ccs_resource_expiry_days" name="ccs_resource_expiry_days" value="<?php echo esc_attr( $expiry_days ); ?>" min="1" max="30" required>
		<p class="description" style="margin-top: 6px;"><?php esc_html_e( 'How long download links remain valid (1–30 days).', 'ccs-wp-theme' ); ?></p>
	</div>
	<?php
}

/**
 * Save resource meta.
 */
function ccs_resource_save_meta( $post_id ) {
	if ( ! isset( $_POST['ccs_resource_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccs_resource_meta_nonce'] ) ), 'ccs_resource_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( get_post_type( $post_id ) !== 'ccs_resource' ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$file_id = absint( isset( $_POST['ccs_resource_file_id'] ) ? wp_unslash( $_POST['ccs_resource_file_id'] ) : 0 );
	update_post_meta( $post_id, '_ccs_resource_file_id', $file_id );

	$subject = sanitize_text_field( isset( $_POST['ccs_resource_email_subject'] ) ? wp_unslash( $_POST['ccs_resource_email_subject'] ) : '' );
	$body    = wp_kses_post( isset( $_POST['ccs_resource_email_body'] ) ? wp_unslash( $_POST['ccs_resource_email_body'] ) : '' );
	update_post_meta( $post_id, '_ccs_resource_email_subject', $subject );
	update_post_meta( $post_id, '_ccs_resource_email_body', $body );

	$expiry_days = absint( isset( $_POST['ccs_resource_expiry_days'] ) ? wp_unslash( $_POST['ccs_resource_expiry_days'] ) : 7 );
	$expiry_days = max( 1, min( 30, $expiry_days ) );
	update_post_meta( $post_id, '_ccs_resource_expiry_days', $expiry_days );
}
add_action( 'save_post', 'ccs_resource_save_meta' );
