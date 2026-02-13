<?php
/**
 * Gutenberg block: Testimonial (server-rendered).
 *
 * Displays a single testimonial post with content, author, role, location, rating, photo.
 * Attributes: testimonialId, layout (card|inline|minimal), showImage, showRating.
 *
 * @package CCS_WP_Theme
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class CCS_Testimonial_Block
 */
class CCS_Testimonial_Block {

	const BLOCK_NAME = 'ccs/testimonial';

	/** Meta keys for testimonial post type. */
	const META_ROLE     = 'testimonial_role';
	const META_LOCATION = 'testimonial_location';
	const META_RATING   = 'testimonial_rating';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post_testimonial', array( $this, 'save_meta' ), 10, 2 );
	}

	/**
	 * Add meta box for testimonial (role, location, rating).
	 */
	public function add_meta_box() {
		add_meta_box(
			'ccs_testimonial_details',
			__( 'Testimonial details', 'ccs-wp-theme' ),
			array( $this, 'render_meta_box' ),
			'testimonial',
			'normal'
		);
	}

	/**
	 * Output meta box fields.
	 *
	 * @param WP_Post $post Current post.
	 */
	public function render_meta_box( $post ) {
		wp_nonce_field( 'ccs_testimonial_meta', 'ccs_testimonial_meta_nonce' );
		$role     = get_post_meta( $post->ID, self::META_ROLE, true );
		$location = get_post_meta( $post->ID, self::META_LOCATION, true );
		$rating   = get_post_meta( $post->ID, self::META_RATING, true );
		$rating   = $rating !== '' ? (int) $rating : '';
		?>
		<p>
			<label for="ccs_testimonial_role"><strong><?php esc_html_e( 'Role / title', 'ccs-wp-theme' ); ?></strong></label><br>
			<input type="text" id="ccs_testimonial_role" name="ccs_testimonial_role" value="<?php echo esc_attr( $role ); ?>" class="widefat">
		</p>
		<p>
			<label for="ccs_testimonial_location"><strong><?php esc_html_e( 'Location', 'ccs-wp-theme' ); ?></strong></label><br>
			<input type="text" id="ccs_testimonial_location" name="ccs_testimonial_location" value="<?php echo esc_attr( $location ); ?>" class="widefat">
		</p>
		<p>
			<label for="ccs_testimonial_rating"><strong><?php esc_html_e( 'Rating (1–5)', 'ccs-wp-theme' ); ?></strong></label><br>
			<input type="number" id="ccs_testimonial_rating" name="ccs_testimonial_rating" value="<?php echo esc_attr( $rating ); ?>" min="1" max="5" step="1" class="small-text">
		</p>
		<?php
	}

	/**
	 * Save testimonial meta.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public function save_meta( $post_id, $post ) {
		if ( ! isset( $_POST['ccs_testimonial_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['ccs_testimonial_meta_nonce'] ) ), 'ccs_testimonial_meta' ) ) {
			return;
		}
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		if ( isset( $_POST['ccs_testimonial_role'] ) ) {
			update_post_meta( $post_id, self::META_ROLE, sanitize_text_field( wp_unslash( $_POST['ccs_testimonial_role'] ) ) );
		}
		if ( isset( $_POST['ccs_testimonial_location'] ) ) {
			update_post_meta( $post_id, self::META_LOCATION, sanitize_text_field( wp_unslash( $_POST['ccs_testimonial_location'] ) ) );
		}
		if ( isset( $_POST['ccs_testimonial_rating'] ) ) {
			$r = (int) $_POST['ccs_testimonial_rating'];
			$r = max( 1, min( 5, $r ) );
			update_post_meta( $post_id, self::META_RATING, $r );
		}
	}

	/**
	 * Register block type and enqueue editor assets.
	 */
	public function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			self::BLOCK_NAME,
			array(
				'api_version'     => 2,
				'attributes'      => array(
					'testimonialId' => array(
						'type'    => 'number',
						'default' => 0,
					),
					'layout'        => array(
						'type'    => 'string',
						'default' => 'card',
						'enum'    => array( 'card', 'inline', 'minimal' ),
					),
					'showImage'     => array(
						'type'    => 'boolean',
						'default' => true,
					),
					'showRating'    => array(
						'type'    => 'boolean',
						'default' => true,
					),
				),
				'render_callback' => array( $this, 'render' ),
				'editor_script'   => 'ccs-testimonial-block-editor',
				'editor_style'    => 'ccs-testimonial-block-editor',
				'style'           => 'ccs-testimonial-block',
			)
		);

		wp_register_script(
			'ccs-testimonial-block-editor',
			THEME_URL . '/assets/js/blocks/testimonial-block.js',
			array(
				'wp-blocks',
				'wp-element',
				'wp-components',
				'wp-block-editor',
				'wp-server-side-render',
				'wp-i18n',
			),
			THEME_VERSION
		);

		wp_register_style(
			'ccs-testimonial-block-editor',
			THEME_URL . '/assets/css/blocks/testimonial.css',
			array(),
			THEME_VERSION
		);

		wp_register_style(
			'ccs-testimonial-block',
			THEME_URL . '/assets/css/blocks/testimonial.css',
			array(),
			THEME_VERSION
		);

		$testimonials = $this->get_testimonials_for_select();
		wp_localize_script(
			'ccs-testimonial-block-editor',
			'ccsTestimonialBlock',
			array(
				'testimonials' => $testimonials,
				'layouts'      => array(
					array( 'value' => 'card', 'label' => __( 'Card', 'ccs-wp-theme' ) ),
					array( 'value' => 'inline', 'label' => __( 'Inline', 'ccs-wp-theme' ) ),
					array( 'value' => 'minimal', 'label' => __( 'Minimal', 'ccs-wp-theme' ) ),
				),
			)
		);
	}

	/**
	 * Get list of published testimonials for dropdown (id => title).
	 *
	 * @return array Associative array of ID => post title.
	 */
	private function get_testimonials_for_select() {
		$posts = get_posts(
			array(
				'post_type'      => 'testimonial',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
			)
		);
		$out = array( array( 'value' => 0, 'label' => __( '— Select testimonial —', 'ccs-wp-theme' ) ) );
		foreach ( $posts as $post ) {
			$out[] = array(
				'value' => $post->ID,
				'label' => $post->post_title ? $post->post_title : sprintf( __( 'Testimonial #%d', 'ccs-wp-theme' ), $post->ID ),
			);
		}
		return $out;
	}

	/**
	 * Server-side render callback.
	 *
	 * @param array $attributes Block attributes.
	 * @return string HTML output.
	 */
	public function render( $attributes ) {
		$id    = isset( $attributes['testimonialId'] ) ? (int) $attributes['testimonialId'] : 0;
		$layout = isset( $attributes['layout'] ) && in_array( $attributes['layout'], array( 'card', 'inline', 'minimal' ), true )
			? $attributes['layout']
			: 'card';
		$show_image = ! empty( $attributes['showImage'] );
		$show_rating = ! empty( $attributes['showRating'] );

		if ( $id <= 0 ) {
			return '';
		}

		$post = get_post( $id );
		if ( ! $post || $post->post_type !== 'testimonial' || $post->post_status !== 'publish' ) {
			return '';
		}

		$author_name = get_the_title( $post );
		$content    = apply_filters( 'the_content', $post->post_content );
		$role       = get_post_meta( $post->ID, self::META_ROLE, true );
		$location   = get_post_meta( $post->ID, self::META_LOCATION, true );
		$rating     = (int) get_post_meta( $post->ID, self::META_RATING, true );
		$rating     = max( 0, min( 5, $rating ) );
		$thumb_id   = get_post_thumbnail_id( $post->ID );
		$photo_url  = '';
		if ( $thumb_id ) {
			$img = wp_get_attachment_image_src( $thumb_id, 'thumbnail' );
			if ( is_array( $img ) && ! empty( $img[0] ) ) {
				$photo_url = $img[0];
			}
		}

		$classes = array(
			'wp-block-ccs-testimonial',
			'ccs-testimonial',
			'ccs-testimonial--' . $layout,
		);

		ob_start();
		?>
		<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
			<?php if ( $layout === 'card' ) : ?>
				<div class="ccs-testimonial__inner">
					<?php if ( $show_image && $photo_url ) : ?>
						<div class="ccs-testimonial__media">
							<img src="<?php echo esc_url( $photo_url ); ?>" alt="" class="ccs-testimonial__photo" width="80" height="80" loading="lazy">
						</div>
					<?php endif; ?>
					<div class="ccs-testimonial__body">
						<blockquote class="ccs-testimonial__content"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></blockquote>
						<?php if ( $show_rating && $rating > 0 ) : ?>
							<div class="ccs-testimonial__rating" role="img" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'ccs-wp-theme' ), $rating ) ); ?>">
								<?php echo $this->render_stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endif; ?>
						<footer class="ccs-testimonial__footer">
							<cite class="ccs-testimonial__author"><?php echo esc_html( $author_name ); ?></cite>
							<?php if ( $role || $location ) : ?>
								<span class="ccs-testimonial__meta">
									<?php
									$meta = array_filter( array( $role, $location ) );
									echo esc_html( implode( ' · ', $meta ) );
									?>
								</span>
							<?php endif; ?>
						</footer>
					</div>
				</div>
			<?php elseif ( $layout === 'inline' ) : ?>
				<div class="ccs-testimonial__inner">
					<?php if ( $show_image && $photo_url ) : ?>
						<div class="ccs-testimonial__media">
							<img src="<?php echo esc_url( $photo_url ); ?>" alt="" class="ccs-testimonial__photo" width="64" height="64" loading="lazy">
						</div>
					<?php endif; ?>
					<div class="ccs-testimonial__body">
						<blockquote class="ccs-testimonial__content"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></blockquote>
						<footer class="ccs-testimonial__footer">
							<cite class="ccs-testimonial__author"><?php echo esc_html( $author_name ); ?></cite>
							<?php if ( $role || $location ) : ?>
								<span class="ccs-testimonial__meta"><?php echo esc_html( implode( ' · ', array_filter( array( $role, $location ) ) ) ); ?></span>
							<?php endif; ?>
							<?php if ( $show_rating && $rating > 0 ) : ?>
								<span class="ccs-testimonial__rating" role="img" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'ccs-wp-theme' ), $rating ) ); ?>">
									<?php echo $this->render_stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</span>
							<?php endif; ?>
						</footer>
					</div>
				</div>
			<?php else : ?>
				<!-- minimal -->
				<blockquote class="ccs-testimonial__content"><?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></blockquote>
				<?php if ( $show_rating && $rating > 0 ) : ?>
					<div class="ccs-testimonial__rating" role="img" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'ccs-wp-theme' ), $rating ) ); ?>">
						<?php echo $this->render_stars( $rating ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
				<footer class="ccs-testimonial__footer">
					<?php if ( $show_image && $photo_url ) : ?>
						<img src="<?php echo esc_url( $photo_url ); ?>" alt="" class="ccs-testimonial__photo ccs-testimonial__photo--small" width="40" height="40" loading="lazy">
					<?php endif; ?>
					<cite class="ccs-testimonial__author"><?php echo esc_html( $author_name ); ?></cite>
					<?php if ( $role || $location ) : ?>
						<span class="ccs-testimonial__meta"><?php echo esc_html( implode( ' · ', array_filter( array( $role, $location ) ) ) ); ?></span>
					<?php endif; ?>
				</footer>
			<?php endif; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Output star markup for rating (1–5).
	 *
	 * @param int $rating Rating 0–5.
	 * @return string HTML.
	 */
	private function render_stars( $rating ) {
		$full = (int) $rating;
		$half = ( $rating - $full ) >= 0.5 ? 1 : 0;
		$empty = 5 - $full - $half;
		$out = '';
		for ( $i = 0; $i < $full; $i++ ) {
			$out .= '<span class="ccs-testimonial__star ccs-testimonial__star--full" aria-hidden="true"></span>';
		}
		if ( $half ) {
			$out .= '<span class="ccs-testimonial__star ccs-testimonial__star--half" aria-hidden="true"></span>';
		}
		for ( $i = 0; $i < $empty; $i++ ) {
			$out .= '<span class="ccs-testimonial__star ccs-testimonial__star--empty" aria-hidden="true"></span>';
		}
		return $out;
	}
}
