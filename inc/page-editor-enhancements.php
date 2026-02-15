<?php
/**
 * Page editor enhancements: classic editor for pages, SEO meta box.
 *
 * - Uses classic editor for pages (filter in theme-setup.php).
 * - Adds SEO & Schema meta box: meta title, meta description, schema type, noindex/nofollow.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add SEO meta box for pages.
 */
function ccs_add_seo_meta_box() {
	add_meta_box(
		'ccs_page_seo',
		__( 'SEO & Schema', 'ccs-wp-theme' ),
		'ccs_seo_meta_box_callback',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'ccs_add_seo_meta_box' );

/**
 * Meta box callback: fields and optional character counters.
 *
 * @param \WP_Post $post Current post.
 */
function ccs_seo_meta_box_callback( $post ) {
	wp_nonce_field( 'ccs_seo_meta_nonce', 'ccs_seo_meta_nonce' );

	$meta_title       = get_post_meta( $post->ID, '_ccs_seo_meta_title', true );
	$meta_description = get_post_meta( $post->ID, '_ccs_seo_meta_description', true );
	$schema_type      = get_post_meta( $post->ID, '_ccs_seo_schema_type', true );
	$noindex          = get_post_meta( $post->ID, '_ccs_seo_noindex', true );
	$nofollow         = get_post_meta( $post->ID, '_ccs_seo_nofollow', true );

	if ( $schema_type === '' ) {
		$schema_type = 'WebPage';
	}

	$schema_types = array(
		'WebPage'       => __( 'WebPage (generic)', 'ccs-wp-theme' ),
		'HomePage'      => __( 'HomePage', 'ccs-wp-theme' ),
		'AboutPage'     => __( 'AboutPage', 'ccs-wp-theme' ),
		'ContactPage'   => __( 'ContactPage', 'ccs-wp-theme' ),
		'CollectionPage' => __( 'CollectionPage', 'ccs-wp-theme' ),
		'FAQPage'       => __( 'FAQPage', 'ccs-wp-theme' ),
	);
	?>
	<div class="ccs-seo-meta-box" style="max-width: 640px;">
		<p>
			<label for="ccs_meta_title" style="display: block; font-weight: 600; margin-bottom: 4px;">
				<?php esc_html_e( 'Meta title', 'ccs-wp-theme' ); ?>
				<span id="ccs-title-length" style="color: #646970; font-weight: normal; font-size: 12px;"><?php echo esc_html( strlen( (string) $meta_title ) . '/60' ); ?></span>
			</label>
			<input type="text" id="ccs_meta_title" name="ccs_seo_meta_title" value="<?php echo esc_attr( $meta_title ); ?>"
				placeholder="<?php echo esc_attr( get_the_title( $post->ID ) ); ?>"
				maxlength="60" style="width: 100%; padding: 8px 12px; box-sizing: border-box;"
			/>
			<span class="description" style="display: block; margin-top: 4px; font-size: 12px; color: #646970;">
				<?php esc_html_e( 'Leave blank to use page title. 50–60 characters recommended.', 'ccs-wp-theme' ); ?>
			</span>
		</p>

		<p>
			<label for="ccs_meta_description" style="display: block; font-weight: 600; margin-bottom: 4px;">
				<?php esc_html_e( 'Meta description', 'ccs-wp-theme' ); ?>
				<span id="ccs-desc-length" style="color: #646970; font-weight: normal; font-size: 12px;"><?php echo esc_html( strlen( (string) $meta_description ) . '/160' ); ?></span>
			</label>
			<textarea id="ccs_meta_description" name="ccs_seo_meta_description" rows="3" maxlength="160"
				style="width: 100%; padding: 8px 12px; box-sizing: border-box; resize: vertical;"
			><?php echo esc_textarea( $meta_description ); ?></textarea>
			<span class="description" style="display: block; margin-top: 4px; font-size: 12px; color: #646970;">
				<?php esc_html_e( '150–160 characters recommended.', 'ccs-wp-theme' ); ?>
			</span>
			<button type="button" id="ccs-suggest-description" class="button button-small" style="margin-top: 6px;">
				<?php esc_html_e( 'Suggest from content', 'ccs-wp-theme' ); ?>
			</button>
		</p>

		<p>
			<label for="ccs_schema_type" style="display: block; font-weight: 600; margin-bottom: 4px;">
				<?php esc_html_e( 'Schema type', 'ccs-wp-theme' ); ?>
			</label>
			<select id="ccs_schema_type" name="ccs_seo_schema_type" style="min-width: 200px;">
				<?php foreach ( $schema_types as $value => $label ) : ?>
					<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $schema_type, $value ); ?>><?php echo esc_html( $label ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label style="display: block; font-weight: 600; margin-bottom: 6px;">
				<?php esc_html_e( 'Indexing', 'ccs-wp-theme' ); ?>
			</label>
			<label style="display: block; margin-bottom: 4px;">
				<input type="checkbox" name="ccs_seo_noindex" value="1" <?php checked( $noindex, '1' ); ?> />
				<?php esc_html_e( 'Noindex (hide from search engines)', 'ccs-wp-theme' ); ?>
			</label>
			<label style="display: block;">
				<input type="checkbox" name="ccs_seo_nofollow" value="1" <?php checked( $nofollow, '1' ); ?> />
				<?php esc_html_e( 'Nofollow (do not follow links)', 'ccs-wp-theme' ); ?>
			</label>
		</p>
	</div>

	<script>
	(function() {
		var titleEl = document.getElementById('ccs_meta_title');
		var descEl = document.getElementById('ccs_meta_description');
		var titleLen = document.getElementById('ccs-title-length');
		var descLen = document.getElementById('ccs-desc-length');
		function updateCounts() {
			if (titleLen) titleLen.textContent = (titleEl ? titleEl.value.length : 0) + '/60';
			if (descLen) descLen.textContent = (descEl ? descEl.value.length : 0) + '/160';
		}
		if (titleEl) titleEl.addEventListener('input', updateCounts);
		if (descEl) descEl.addEventListener('input', updateCounts);

		var suggestBtn = document.getElementById('ccs-suggest-description');
		if (suggestBtn && descEl) {
			suggestBtn.addEventListener('click', function() {
				var content = '';
				var contentEl = document.getElementById('content');
				if (contentEl && contentEl.value) {
					content = contentEl.value.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
				}
				if (content.length > 160) content = content.substring(0, 157) + '...';
				descEl.value = content;
				updateCounts();
			});
		}
	})();
	</script>
	<?php
}

/**
 * Save SEO meta box data.
 *
 * @param int $post_id Post ID.
 */
function ccs_save_seo_meta_box( $post_id ) {
	if ( ! isset( $_POST['ccs_seo_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccs_seo_meta_nonce'] ) ), 'ccs_seo_meta_nonce' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$post = get_post( $post_id );
	if ( ! $post || $post->post_type !== 'page' ) {
		return;
	}

	if ( isset( $_POST['ccs_seo_meta_title'] ) ) {
		update_post_meta( $post_id, '_ccs_seo_meta_title', sanitize_text_field( wp_unslash( $_POST['ccs_seo_meta_title'] ) ) );
	}
	if ( isset( $_POST['ccs_seo_meta_description'] ) ) {
		update_post_meta( $post_id, '_ccs_seo_meta_description', sanitize_textarea_field( wp_unslash( $_POST['ccs_seo_meta_description'] ) ) );
	}
	if ( isset( $_POST['ccs_seo_schema_type'] ) ) {
		$allowed = array( 'WebPage', 'HomePage', 'AboutPage', 'ContactPage', 'CollectionPage', 'FAQPage' );
		$schema  = sanitize_text_field( wp_unslash( $_POST['ccs_seo_schema_type'] ) );
		if ( in_array( $schema, $allowed, true ) ) {
			update_post_meta( $post_id, '_ccs_seo_schema_type', $schema );
		}
	}
	update_post_meta( $post_id, '_ccs_seo_noindex', isset( $_POST['ccs_seo_noindex'] ) ? '1' : '' );
	update_post_meta( $post_id, '_ccs_seo_nofollow', isset( $_POST['ccs_seo_nofollow'] ) ? '1' : '' );
}
add_action( 'save_post_page', 'ccs_save_seo_meta_box' );
