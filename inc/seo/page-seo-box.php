<?php
/**
 * The SEO box on pages (Pages > edit), and what its fields do on the site.
 *
 * - Meta title and description (the fields the site's
 *   titles and descriptions are read from), noindex and nofollow.
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
	// An SEO plugin brings its own box; two would be confusing.
	if ( function_exists( 'ccs_seo_plugin_active' ) && ccs_seo_plugin_active() ) {
		return;
	}
	add_meta_box(
		'ccs_page_seo',
		__( 'SEO', 'ccs-wp-theme' ),
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

	// The keys the site's titles and descriptions are read from (CCS_SEO_Optimizer),
	// so the box shows what is live. It saved to keys nothing read until Nov 2026.
	$meta_title       = get_post_meta( $post->ID, 'ccs_seo_title', true );
	$meta_description = get_post_meta( $post->ID, 'ccs_meta_description', true );
	$noindex          = get_post_meta( $post->ID, '_ccs_seo_noindex', true );
	$nofollow         = get_post_meta( $post->ID, '_ccs_seo_nofollow', true );
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

		<?php
		// No schema type picker: each template already gives its page the right
		// type (About, Contact, FAQ, service, area), and a hand-picked one
		// could contradict it. The old picker's choice was never used.
		?>

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
					content = contentEl.value;
				} else if (window.wp && wp.data && wp.data.select('core/editor')) {
					// Block editor, if pages ever move to it: there is no #content textarea.
					content = wp.data.select('core/editor').getEditedPostContent() || '';
				}
				content = content.replace(/<!--[\s\S]*?-->/g, ' ').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
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
		update_post_meta( $post_id, 'ccs_seo_title', sanitize_text_field( wp_unslash( $_POST['ccs_seo_meta_title'] ) ) );
	}
	if ( isset( $_POST['ccs_seo_meta_description'] ) ) {
		update_post_meta( $post_id, 'ccs_meta_description', sanitize_textarea_field( wp_unslash( $_POST['ccs_seo_meta_description'] ) ) );
	}
	update_post_meta( $post_id, '_ccs_seo_noindex', isset( $_POST['ccs_seo_noindex'] ) ? '1' : '' );
	update_post_meta( $post_id, '_ccs_seo_nofollow', isset( $_POST['ccs_seo_nofollow'] ) ? '1' : '' );
}
add_action( 'save_post_page', 'ccs_save_seo_meta_box' );

/**
 * The box's "Noindex" and "Nofollow" ticks: tell search engines on the page
 * itself, and keep a hidden page out of the sitemap. Left to an SEO plugin
 * when one is on (its own box replaces this one).
 *
 * @param array $robots Robots directives.
 * @return array
 */
function ccs_seo_page_robots( $robots ) {
	if ( ( function_exists( 'ccs_seo_plugin_active' ) && ccs_seo_plugin_active() ) || ! is_singular( 'page' ) ) {
		return $robots;
	}
	$id = get_queried_object_id();
	if ( '1' === get_post_meta( $id, '_ccs_seo_noindex', true ) ) {
		$robots['noindex'] = true;
		unset( $robots['max-image-preview'] );
	}
	if ( '1' === get_post_meta( $id, '_ccs_seo_nofollow', true ) ) {
		$robots['nofollow'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'ccs_seo_page_robots' );

/**
 * Pages ticked "Noindex" are left out of the sitemap.
 *
 * @param int[]  $post_ids  IDs to exclude.
 * @param string $post_type Post type.
 * @return int[]
 */
function ccs_seo_sitemap_exclude_noindex( $post_ids, $post_type ) {
	if ( 'page' !== $post_type ) {
		return $post_ids;
	}
	$hidden = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => 'publish',
			'fields'         => 'ids',
			'posts_per_page' => 200,
			'meta_key'       => '_ccs_seo_noindex', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
	return array_merge( (array) $post_ids, $hidden );
}
add_filter( 'ccs_sitemap_exclude_post_ids', 'ccs_seo_sitemap_exclude_noindex', 10, 2 );

/**
 * Carry over anything typed into the box before it was fixed: a title or
 * description saved to the old keys moves to the ones the site reads, once,
 * where the page has none there yet.
 */
function ccs_seo_box_keys_2026_11() {
	if ( get_option( 'ccs_seo_box_keys_2026_11' ) || ! add_option( 'ccs_seo_box_keys_2026_11', '1', '', 'no' ) ) {
		return;
	}
	$pairs = array(
		'_ccs_seo_meta_title'       => 'ccs_seo_title',
		'_ccs_seo_meta_description' => 'ccs_meta_description',
	);
	foreach ( $pairs as $old => $new ) {
		$ids = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'any',
				'fields'         => 'ids',
				'posts_per_page' => -1,
				'meta_key'       => $old, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			)
		);
		foreach ( $ids as $id ) {
			$value = trim( (string) get_post_meta( $id, $old, true ) );
			if ( '' !== $value && '' === trim( (string) get_post_meta( $id, $new, true ) ) ) {
				update_post_meta( $id, $new, $value );
			}
		}
	}
}
add_action( 'init', 'ccs_seo_box_keys_2026_11', 50 );
